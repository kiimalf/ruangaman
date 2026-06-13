export default function ProgressBar({ depth = 1, completed = false }) {
  const maxEstimatedDepth = 6;
  const rawPercentage = (depth / maxEstimatedDepth) * 100;
  const percentage = completed ? 100 : Math.min(Math.max(rawPercentage, 15), 95);

  return (
    <div className="w-full">
      <div className="flex justify-end mb-1">
        <span style={{
          color: '#75A000', fontSize: 13,
          fontFamily: 'Plus Jakarta Sans, sans-serif',
          fontWeight: 700,
        }}>
          {Math.round(percentage)}% selesai
        </span>
      </div>
      <div className="w-full bg-gray-200 h-2 overflow-hidden" role="progressbar" aria-valuenow={percentage} aria-valuemin={0} aria-valuemax={100}>
        <div
          className="h-full transition-all duration-700 ease-out"
          style={{
            width: `${percentage}%`,
            background: 'linear-gradient(to right, #FFE85C, #DDFF82)',
          }}
        />
      </div>
    </div>
  );
}