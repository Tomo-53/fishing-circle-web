#!/usr/bin/env bash
# PostToolUse(Edit|Write): 編集された src/ 配下の PHP を Laravel Pint で整形する。
# Docker が使えれば自動整形、無ければ整形を促すだけ（常に非ブロッキング）。
set -euo pipefail

input=$(cat)
file=$(printf '%s' "$input" | jq -r '.tool_input.file_path // empty' 2>/dev/null || true)
[ -z "$file" ] && exit 0

# PHP かつ src/ 配下のみ対象
case "$file" in
  *.php) ;;
  *) exit 0 ;;
esac
case "$file" in
  *"/src/"*|src/*) ;;
  *) exit 0 ;;
esac

cd "${CLAUDE_PROJECT_DIR:-.}" 2>/dev/null || true
rel="${file##*/src/}"   # コンテナ内アプリルートからの相対パス

if command -v docker-compose >/dev/null 2>&1; then
  docker-compose exec -T app ./vendor/bin/pint "$rel" >/dev/null 2>&1 || true
  printf '{"systemMessage":"Pint: %s を整形しました（対象外なら無視）"}\n' "$rel"
elif command -v docker >/dev/null 2>&1; then
  docker compose exec -T app ./vendor/bin/pint "$rel" >/dev/null 2>&1 || true
  printf '{"systemMessage":"Pint: %s を整形しました（対象外なら無視）"}\n' "$rel"
else
  printf '{"systemMessage":"PHP を編集しました。コミット前に整形してください: docker-compose exec app ./vendor/bin/pint"}\n'
fi
exit 0
