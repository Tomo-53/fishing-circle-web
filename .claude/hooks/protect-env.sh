#!/usr/bin/env bash
# PreToolUse(Edit|Write): 機密 .env ファイルへの書き込みをブロックする（exit 2）。
# .env.example は許可。
set -euo pipefail

input=$(cat)
file=$(printf '%s' "$input" | jq -r '.tool_input.file_path // empty' 2>/dev/null || true)
[ -z "$file" ] && exit 0

base=$(basename "$file")
case "$base" in
  .env.example) exit 0 ;;       # テンプレートは許可
  .env|.env.*)
    echo "ブロックしました: $file は機密情報です。実値は編集せず、.env.example にキーのみ追加してください。" >&2
    exit 2 ;;
esac
exit 0
