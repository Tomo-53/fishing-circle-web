#!/usr/bin/env bash
# SessionStart: 現在のブランチ等の動的コンテキストを注入する。
set -euo pipefail

cd "${CLAUDE_PROJECT_DIR:-.}" 2>/dev/null || true
branch=$(git rev-parse --abbrev-ref HEAD 2>/dev/null || echo "unknown")

msg="現在のブランチ: ${branch}。"
case "$branch" in
  main|dev)
    msg="${msg} ⚠ 保護ブランチです。直接コミットせず、dev から feature/ ブランチを切ってください。" ;;
esac
msg="${msg} 主要コマンド: テスト=docker-compose exec app php artisan test / 整形=docker-compose exec app ./vendor/bin/pint。"

# JSON を安全に生成（jq でエスケープ）
jq -cn --arg ctx "$msg" '{hookSpecificOutput:{hookEventName:"SessionStart",additionalContext:$ctx}}'
exit 0
