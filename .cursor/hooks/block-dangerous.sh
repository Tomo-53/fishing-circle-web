#!/usr/bin/env bash
# beforeShellExecution: 破滅的に危険なシェルコマンドを遮断/確認する。
# 通常の開発コマンドは妨げない。stdin に Cursor の JSON、stdout に判定 JSON を返す。
set -euo pipefail

input=$(cat)
cmd=$(printf '%s' "$input" | jq -r '.command // empty' 2>/dev/null || true)
[ -z "$cmd" ] && { echo '{ "permission": "allow" }'; exit 0; }

deny() {
  jq -cn --arg m "$1" \
    '{permission:"deny", user_message:$m, agent_message:$m}'
  exit 0
}
ask() {
  jq -cn --arg m "$1" \
    '{permission:"ask", user_message:$m, agent_message:$m}'
  exit 0
}

# 1) ルート/ホーム配下の再帰強制削除
if printf '%s' "$cmd" | grep -Eq 'rm[[:space:]]+-[a-zA-Z]*[rR][a-zA-Z]*[fF]?[a-zA-Z]*[[:space:]]+(-[a-zA-Z]+[[:space:]]+)*(/|/\*|~|\$HOME|\$\{HOME\})([[:space:]]|$)'; then
  deny "ルート/ホーム配下の再帰削除 (rm -rf /, ~ 等) は禁止です"
fi

# 2) 保護ブランチ(main/dev)への force push
if printf '%s' "$cmd" | grep -Eq 'git[[:space:]]+push' \
   && printf '%s' "$cmd" | grep -Eq '(--force([[:space:]=]|$)|--force-with-lease|[[:space:]]-f([[:space:]]|$))' \
   && printf '%s' "$cmd" | grep -Eq '(^|[[:space:]])(main|dev)([[:space:]]|$|:)'; then
  deny "main/dev への force push は禁止です（PR 経由でマージしてください）"
fi

# 3) .env を git に add（機密混入防止。.env.example は許可）
if printf '%s' "$cmd" | grep -Eq 'git[[:space:]]+add' \
   && printf '%s' "$cmd" | grep -Eq '(^|[[:space:]/])\.env([[:space:]]|$)'; then
  deny ".env のコミットは禁止です（.env.example のみ管理対象）"
fi

# 4) 破壊的マイグレーション（fresh/reset/rollback, db:wipe）は手動確認を要求
if printf '%s' "$cmd" | grep -Eq '(migrate:(fresh|reset|rollback)|db:wipe)'; then
  ask "破壊的なマイグレーション操作です。既存スキーマは触らず新規マイグレーションで前進するのが原則。実行する場合は内容を確認してください。"
fi

echo '{ "permission": "allow" }'
exit 0
