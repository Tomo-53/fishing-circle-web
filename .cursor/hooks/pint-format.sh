#!/usr/bin/env bash
# afterFileEdit: 編集された src/ 配下の PHP を Laravel Pint で整形する。
# Docker が使えれば自動整形、無ければ案内のみ（常に非ブロッキング）。
set -euo pipefail

input=$(cat)
file=$(printf '%s' "$input" | jq -r '
  .file_path // .tool_input.file_path // .tool_input.path //
  .path // empty' 2>/dev/null || true)
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

rel="${file##*/src/}"   # コンテナ内アプリルートからの相対パス

if command -v docker-compose >/dev/null 2>&1; then
  docker-compose exec -T app ./vendor/bin/pint "$rel" >/dev/null 2>&1 || true
elif command -v docker >/dev/null 2>&1; then
  docker compose exec -T app ./vendor/bin/pint "$rel" >/dev/null 2>&1 || true
fi
exit 0
