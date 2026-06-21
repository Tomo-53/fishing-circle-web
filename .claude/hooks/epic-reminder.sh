#!/usr/bin/env bash
# Stop: feature ブランチでの作業終了時、案件ノートがあれば知識昇格を促す（非ブロッキング）。
set -euo pipefail

cd "${CLAUDE_PROJECT_DIR:-.}" 2>/dev/null || true
cat >/dev/null 2>&1 || true   # stdin を読み捨て（使わない）

branch=$(git rev-parse --abbrev-ref HEAD 2>/dev/null || echo "")
case "$branch" in
  feature/*) ;;
  *) exit 0 ;;   # 案件作業中（feature/）以外では何もしない
esac

# 案件ノートが1つでも存在する場合のみ促す
if ls .claude/epics/*/_epic.md >/dev/null 2>&1; then
  printf '{"systemMessage":"作業を終える前に、決定事項・学び・未解決事項を案件ノート(.claude/epics/<slug>/_epic.md)へ昇格しましたか？ 必要なら /epic-done を実行してください。"}\n'
fi
exit 0
