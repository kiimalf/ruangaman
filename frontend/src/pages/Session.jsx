import { useEffect, useState } from 'react';
import { useNavigate, Navigate } from 'react-router-dom';
import useSessionStore from '../store/sessionStore';
import { answerQuestion } from '../services/api';
import QuestionCard from '../components/QuestionCard';
import ProgressBar from '../components/ProgressBar';

export default function Session() {
  const navigate = useNavigate();
  const { sessionToken, currentQuestion, setCurrentQuestion, setConclusion, addAnswer } = useSessionStore();
  const [isAnswering, setIsAnswering] = useState(false);
  const [errorMsg, setErrorMsg] = useState(null);
  const [depth, setDepth] = useState(1);

  // Jika tidak ada session token atau pertanyaan pertama (langsung akses /session)
  if (!sessionToken || !currentQuestion) {
    return <Navigate to="/" replace />;
  }

  const handleAnswer = async (answerValue) => {
    setIsAnswering(true);
    setErrorMsg(null);
    
    try {
      // Panggil API
      const result = await answerQuestion(sessionToken, currentQuestion.id, answerValue);
      
      // Simpan jawaban ke store lokal (opsional untuk rekam jejak UI jika butuh)
      addAnswer(currentQuestion.id, answerValue);
      setDepth(prev => prev + 1);

      if (result.conclusion) {
        // Selesai, masuk konklusi
        setConclusion(result.conclusion);
        navigate('/conclusion');
      } else if (result.next_question) {
        // Lanjut pertanyaan berikutnya
        setCurrentQuestion(result.next_question);
      }
    } catch (err) {
      setErrorMsg('Gagal mengirim jawaban. Silakan coba lagi.');
    } finally {
      setIsAnswering(false);
    }
  };

  return (
    <div className="flex flex-col items-center min-h-screen p-4 pb-24 max-w-md mx-auto pt-8">
      
      {/* Header & Progress */}
      <div className="w-full mb-8">
        <div className="flex justify-between items-end mb-2">
          <span className="text-sm font-semibold text-safe-muted">Sesi Evaluasi</span>
          <span className="text-xs text-safe-muted opacity-70">100% Anonim</span>
        </div>
        <ProgressBar depth={depth} />
      </div>

      {/* Error Message */}
      {errorMsg && (
        <div className="bg-red-50 text-red-600 p-3 rounded-lg mb-4 text-sm w-full border border-red-100">
          {errorMsg}
        </div>
      )}

      {/* Question Card */}
      <QuestionCard 
        question={currentQuestion} 
        onAnswer={handleAnswer} 
        disabled={isAnswering} 
      />

    </div>
  );
}
