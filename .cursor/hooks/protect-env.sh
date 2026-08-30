#!/usr/bin/env bash
# preToolUse(Write|Edit|StrReplace): 機密 .env ファイルへの書き込みを遮断する。
# .env.example は許可。stdin に Cursor の JSON、stdout に判定 JSON を返す。
set -euo pipefail

input=$(cat)
# ツールにより格納先が異なるため複数候補から取得
file=$(printf '%s' "$input" | jq -r '
  .tool_input.file_path // .tool_input.path // .tool_input.target_file //
  .file_path // .path // empty' 2>/dev/null || true)
[ -z "$file" ] && { echo '{ "permission": "allow" }'; exit 0; }

base=$(basename "$file")
case "$base" in
  .env.example)
    echo '{ "permission": "allow" }'; exit 0 ;;
  .env|.env.*)
    msg="$file は機密情報です。実値は編集せず、.env.example にキーのみ追加してください。"
    jq -cn --arg m "$msg" '{permission:"deny", user_message:$m, agent_message:$m}'
    exit 0 ;;
esac

echo '{ "permission": "allow" }'
exit 0
