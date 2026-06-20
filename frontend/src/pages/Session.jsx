import { useState } from 'react';
import { useNavigate, Navigate } from 'react-router-dom';
import useSessionStore from '../store/sessionStore';
import { answerQuestion } from '../services/api';
import QuestionCard from '../components/QuestionCard';
import ProgressBar from '../components/ProgressBar';
import QuickExitButton from '../components/QuickExitButton';
import AdminLoginButton from '../components/AdminLoginButton';
import HelpButton from '../components/HelpButton';

export default function Session() {
  const navigate = useNavigate();
  const { sessionToken, currentQuestion, setCurrentQuestion, setConclusion, addAnswer } = useSessionStore();
  const [isAnswering, setIsAnswering] = useState(false);
  const [errorMsg, setErrorMsg] = useState(null);
  const [depth, setDepth] = useState(1);
  const [showTransition, setShowTransition] = useState(false);

  if (!sessionToken || !currentQuestion) {
    return <Navigate to="/" replace />;
  }

  const handleAnswer = async (answerValue) => {
    setIsAnswering(true);
    setErrorMsg(null);
    try {
      const result = await answerQuestion(sessionToken, currentQuestion.id, answerValue);
      addAnswer(currentQuestion.id, answerValue);
      setDepth(prev => prev + 1);

      if (result.conclusion) {
        setShowTransition(true);
        setConclusion(result.conclusion);
        setTimeout(() => navigate('/conclusion'), 2000);
      } else if (result.next_question) {
        setCurrentQuestion(result.next_question);
      }
    } catch (err) {
      setErrorMsg('Gagal mengirim jawaban. Silakan coba lagi.');
    } finally {
      setIsAnswering(false);
    }
  };

  return (
    <div
      className="relative min-h-screen overflow-hidden flex flex-col"
      style={{ background: '#FEFAEE' }}
    >
      {/* ── Blobs dekoratif ── */}
      <div aria-hidden="true" style={{
        position: 'absolute', width: '40vw', height: '55vh',
        left: '-5vw', top: '40vh', opacity: 0.5,
        background: '#FFE85C', filter: 'blur(50px)',
        transform: 'rotate(139deg)', transformOrigin: 'top left',
        borderRadius: '50%', pointerEvents: 'none',
      }} />
      <div aria-hidden="true" style={{
        position: 'absolute', width: '50vw', height: '55vh',
        left: '30vw', bottom: '-10vh', opacity: 0.5,
        background: '#F9DBFF', filter: 'blur(50px)',
        transform: 'rotate(150deg)', transformOrigin: 'top left',
        borderRadius: '50%', pointerEvents: 'none',
      }} />
      <div aria-hidden="true" style={{
        position: 'absolute', width: '45vw', height: '58vh',
        right: '-5vw', top: '40vh', opacity: 0.5,
        background: '#DDFF82', filter: 'blur(50px)',
        borderRadius: '50%', pointerEvents: 'none',
      }} />

      <div className="relative z-10 flex flex-col min-h-screen">

        {/* ── Header: logo ruangaman ── */}
        <div className="w-full text-center pt-10 pb-4">
          <span style={{
            color: '#BE0199',
            fontSize: 24,
            fontFamily: 'Qaligo, serif',
            fontWeight: 400,
          }}>
            ruangaman
          </span>
        </div>

        {/* ── Progress Bar ── */}
        <div className="w-full px-8 pt-4 pb-1">
          <ProgressBar depth={depth} />
        </div>

        {/* ── Konten utama: pertanyaan atau transisi ── */}
        <div className="flex-1 flex flex-col items-center justify-center px-6 max-w-2xl mx-auto w-full py-8">

          {errorMsg && (
            <div
              className="text-red-600 p-3 rounded-lg mb-4 text-sm border border-red-200 w-full text-center"
              style={{ background: 'rgba(255,255,255,0.6)' }}
            >
              {errorMsg}
            </div>
          )}

          {showTransition ? (
            <TransitionView sessionToken={sessionToken} />
          ) : (
            <QuestionCard
              question={currentQuestion}
              onAnswer={handleAnswer}
              disabled={isAnswering}
            />
          )}
        </div>

        {/*
         * ── Spacer bawah ──
         * Memberi ruang agar konten tidak tertutup oleh tombol-tombol fixed di bawah.
         */}
        <div style={{ height: 88 }} />
      </div>

      {/*
       * ── 3 tombol fixed bawah ──
       *
       * Kiri   : AdminLoginButton  → /admin (Filament), tab baru, subtle
       * Tengah : QuickExitButton   → clear session + redirect google.com
       * Kanan  : HelpButton        → modal panduan penggunaan
       *
       * Semua komponen mengatur posisinya sendiri (position: fixed).
       */}
      <AdminLoginButton />
      <QuickExitButton />
      <HelpButton />
    </div>
  );
}

/* ── Komponen Transisi ─────────────────────────────────────────────────
 * Muncul selama 2 detik setelah sesi selesai, sebelum redirect ke /conclusion.
 * Tombol "Unduh BAP" di sini masih disabled karena redirect otomatis —
 * download tersedia di halaman /conclusion.
 */
function TransitionView({ sessionToken }) {
  return (
    <div className="flex flex-col items-center text-center animate-fade-in px-4">
      <p style={{
        color: '#181818',
        fontSize: 18,
        fontFamily: 'Plus Jakarta Sans, sans-serif',
        fontWeight: 700,
        lineHeight: 1.6,
        marginBottom: 16,
      }}>
        Validasi Selesai. Terima kasih atas keberanian Anda.
      </p>

      {/* Info privasi */}
      <div className="flex items-start gap-2 justify-center mb-10">
        <span style={{ flexShrink: 0, fontSize: 18, marginTop: 2 }}>💡</span>
        <p style={{
          color: '#75A000',
          fontSize: 14,
          fontFamily: 'Plus Jakarta Sans, sans-serif',
          fontWeight: 400,
          textDecoration: 'underline',
          maxWidth: 500,
          textAlign: 'center',
          lineHeight: 1.6,
        }}>
          Privasi Terjamin: Dokumen ini dibuat sekali pakai (ephemeral) dan tidak disimpan
          di dalam pangkalan data kami.
        </p>
      </div>

      {/* Tombol Unduh BAP — disabled saat transisi, aktif di /conclusion */}
      <button
        disabled
        style={{
          height: 50,
          paddingLeft: 32,
          paddingRight: 32,
          background: '#F3FDD8',
          borderRadius: 8,
          outline: '1px solid black',
          outlineOffset: '-1px',
          border: 'none',
          opacity: 0.5,
          cursor: 'default',
          display: 'inline-flex',
          alignItems: 'center',
          gap: 10,
        }}
      >
        <svg
          width="22" height="22" viewBox="0 0 24 24"
          fill="none" stroke="currentColor" strokeWidth="2"
          strokeLinecap="round" strokeLinejoin="round"
        >
          <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
          <polyline points="14 2 14 8 20 8"/>
          <line x1="12" y1="12" x2="12" y2="18"/>
          <polyline points="9 15 12 18 15 15"/>
        </svg>
        <span style={{
          color: '#000',
          fontSize: 16,
          fontFamily: 'Plus Jakarta Sans, sans-serif',
          fontWeight: 600,
        }}>
          Unduh BAP
        </span>
      </button>

      <p className="mt-6 animate-pulse" style={{ color: '#888', fontSize: 13 }}>
        Mengalihkan ke halaman hasil...
      </p>
    </div>
  );
}