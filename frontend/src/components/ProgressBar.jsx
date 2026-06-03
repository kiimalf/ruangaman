export default function ProgressBar({ current = 0, total = 10 }) {
  const percentage = Math.min((current / total) * 100, 100);

  return (
    <div className="w-full bg-gray-200 rounded-full h-2 overflow-hidden">
      <div
        className="bg-gradient-to-r from-primary to-secondary h-full rounded-full transition-all duration-500 ease-out"
        style={{ width: `${percentage}%` }}
      />
    </div>
  )
}
