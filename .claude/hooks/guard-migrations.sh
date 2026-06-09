#!/usr/bin/env bash
# db-migrator サブエージェント専用の PreToolUse(Bash) ガード。
# 破壊的マイグレーションを禁止し、新規マイグレーションでの前進を促す（exit 2）。
set -euo pipefail

input=$(cat)
cmd=$(printf '%s' "$input" | jq -r '.tool_input.command // empty' 2>/dev/null || true)
[ -z "$cmd" ] && exit 0

if printf '%s' "$cmd" | grep -Eq '(migrate:(fresh|reset|rollback)|db:wipe)'; then
  echo "db-migrator は破壊的操作を行いません。既存スキーマは触らず、新規マイグレーションで前進してください（migrate:fresh/reset/rollback, db:wipe は禁止）。" >&2
  exit 2
fi
exit 0
