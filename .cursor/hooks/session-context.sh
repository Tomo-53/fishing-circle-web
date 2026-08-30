#!/usr/bin/env bash
# sessionStart: 現在のブランチ等の動的コンテキストを通知する（best-effort）。
set -euo pipefail

branch=$(git rev-parse --abbrev-ref HEAD 2>/dev/null || echo "unknown")

msg="現在のブランチ: ${branch}。"
case "$branch" in
  main|dev)
    msg="${msg} ⚠ 保護ブランチです。直接コミットせず、dev から feature/ ブランチを切ってください。" ;;
esac
msg="${msg} 主要コマンド: テスト=docker-compose exec app php artisan test / 整形=docker-compose exec app ./vendor/bin/pint。"

jq -cn --arg ctx "$msg" '{additional_context:$ctx}'
exit 0
