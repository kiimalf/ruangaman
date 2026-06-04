export default function ProgressBar({ depth = 1 }) {
  // PRD: estimate progress based on tree depth (NOT exact question count)
  // Max depth is around 5-6 questions
  // We cap at 95% until actually concluded to prevent false completion feeling
  const maxEstimatedDepth = 6;
  const rawPercentage = (depth / maxEstimatedDepth) * 100;
  
  // Make sure it's at least 15% to show some progress, but max 95% during questions
  const percentage = Math.min(Math.max(rawPercentage, 15), 95);

  return (
    <div className="w-full bg-gray-200 rounded-full h-2 overflow-hidden" role="progressbar" aria-valuenow={percentage} aria-valuemin={0} aria-valuemax={100}>
      <div
        className="bg-gradient-to-r from-primary to-secondary h-full rounded-full transition-all duration-700 ease-out"
        style={{ width: `${percentage}%` }}
      />
    </div>
  )
}

