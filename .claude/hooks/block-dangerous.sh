#!/usr/bin/env bash
# PreToolUse(Bash): 破滅的に危険なコマンドのみをブロックする（exit 2）。
# 通常の開発コマンドは妨げない。
set -euo pipefail

input=$(cat)
cmd=$(printf '%s' "$input" | jq -r '.tool_input.command // empty' 2>/dev/null || true)
[ -z "$cmd" ] && exit 0

deny() { echo "ブロックしました: $1" >&2; exit 2; }

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

# 3) 本番想定(--force)での破壊的マイグレーション
if printf '%s' "$cmd" | grep -Eq '(migrate:(fresh|reset|rollback)|db:wipe)' \
   && printf '%s' "$cmd" | grep -Eq '(^|[[:space:]])--force([[:space:]]|$)'; then
  deny "--force 付きの破壊的マイグレーション(migrate:fresh/reset/rollback, db:wipe)は手動確認が必要です"
fi

# 4) .env を git に add（機密混入防止。.env.example は許可）
if printf '%s' "$cmd" | grep -Eq 'git[[:space:]]+add' \
   && printf '%s' "$cmd" | grep -Eq '(^|[[:space:]/])\.env([[:space:]]|$)'; then
  deny ".env のコミットは禁止です（.env.example のみ管理対象）"
fi

exit 0
