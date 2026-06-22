interface AlertProps {
  type: "success" | "error" | "warning" | "info";
  message: string;
  onClose?: () => void;
}

const styles = {
  success: "bg-green-50 border-green-400 text-green-800",
  error: "bg-red-50 border-red-400 text-red-800",
  warning: "bg-yellow-50 border-yellow-400 text-yellow-800",
  info: "bg-blue-50 border-blue-400 text-blue-800",
};

export function Alert({ type, message, onClose }: AlertProps) {
  return (
    <div className={`border rounded-button px-4 py-3 flex items-start gap-3 ${styles[type]}`} role="alert">
      <span className="flex-1 text-sm">{message}</span>
      {onClose && (
        <button
          onClick={onClose}
          className="shrink-0 text-current opacity-60 hover:opacity-100 transition-opacity"
          aria-label="閉じる"
        >
          ×
        </button>
      )}
    </div>
  );
}
