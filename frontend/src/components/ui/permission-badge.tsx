import type { PermissionLevel } from "@/types";
import { PERMISSION_COLOR, PERMISSION_LABEL } from "@/types";

interface PermissionBadgeProps {
  level: PermissionLevel;
  isPending?: boolean;
}

export function PermissionBadge({ level, isPending }: PermissionBadgeProps) {
  return (
    <span className="inline-flex items-center gap-1.5">
      <span
        className={`inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${PERMISSION_COLOR[level]}`}
      >
        {PERMISSION_LABEL[level]}
      </span>
      {isPending && (
        <span className="text-yellow-600 text-xs">（承認待ち）</span>
      )}
    </span>
  );
}
