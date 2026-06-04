import { useState, useEffect } from 'react';

export default function QuestionCard({ question, onAnswer, disabled }) {
  const [animationClass, setAnimationClass] = useState('animate-fade-in');

  // Trigger animation when question changes
  useEffect(() => {
    setAnimationClass('');
    const timer = setTimeout(() => {
      setAnimationClass('animate-fade-in');
    }, 10);
    return () => clearTimeout(timer);
  }, [question?.id]);

  if (!question) return null;

  return (
    <div className={`card w-full mb-6 ${animationClass}`}>
      <h3 className="text-xl font-medium text-safe-text mb-4 leading-relaxed">
        {question.text}
      </h3>
      
      {question.help_text && (
        <div className="bg-blue-50 border-l-4 border-primary p-3 mb-6 rounded-r-lg">
          <p className="text-sm text-primary-700">
            <strong>Bantuan:</strong> {question.help_text}
          </p>
        </div>
      )}

      <div className="flex flex-col space-y-3">
        <button
          className="btn-primary"
          onClick={() => onAnswer('YA')}
          disabled={disabled}
        >
          Ya
        </button>
        <button
          className="btn-outline"
          onClick={() => onAnswer('TIDAK')}
          disabled={disabled}
        >
          Tidak
        </button>
        <button
          className="bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium py-3 px-6 rounded-xl transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-gray-300"
          onClick={() => onAnswer('SAYA_TIDAK_YAKIN')}
          disabled={disabled}
        >
          Saya Tidak Yakin
        </button>
      </div>
    </div>
  );
}
