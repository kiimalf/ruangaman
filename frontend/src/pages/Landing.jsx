import { useNavigate } from 'react-router-dom';
import useSessionStore from '../store/sessionStore';
import { startSession } from '../services/api';
import { useState, useEffect } from 'react';

export default function Landing() {
  const navigate = useNavigate();
  const setSessionToken = useSessionStore((state) => state.setSessionToken);
  const setCurrentQuestion = useSessionStore((state) => state.setCurrentQuestion);
  const [isStarting, setIsStarting] = useState(false);
  const [errorMsg, setErrorMsg] = useState(null);
  const [showSplash, setShowSplash] = useState(true);
  const [splashFading, setSplashFading] = useState(false);

  useEffect(() => {
    const fadeTimer = setTimeout(() => setSplashFading(true), 1800);
    const hideTimer = setTimeout(() => setShowSplash(false), 2300);
    return () => {
      clearTimeout(fadeTimer);
      clearTimeout(hideTimer);
    };
  }, []);

  const handleStart = async () => {
    setIsStarting(true);
    setErrorMsg(null);
    try {
      const data = await startSession();
      setSessionToken(data.session_token);
      setCurrentQuestion(data.first_question);
      navigate('/session');
    } catch (err) {
      setErrorMsg('Gagal memulai sesi. Pastikan server berjalan.');
      setIsStarting(false);
    }
  };

  // ─── SPLASH SCREEN ───────────────────────────────────────────────
  if (showSplash) {
    return (
      <div
        className="fixed inset-0 flex items-center justify-center transition-opacity duration-500"
        style={{ background: '#FEFAEE', opacity: splashFading ? 0 : 1 }}
      >
        <span style={{
          color: '#BE0199',
          fontSize: 'clamp(32px, 4vw, 96px)',
          fontFamily: 'Qaligo, serif',
          fontWeight: 400,
        }}>
          ruangaman
        </span>
      </div>
    );
  }

  // ─── HALAMAN UTAMA ────────────────────────────────────────────────
  return (
    <div
      className="relative min-h-screen overflow-hidden flex flex-col animate-fade-in"
      style={{ background: '#FEFAEE' }}
    >
      {/* Blob kiri hijau */}
      <div aria-hidden="true" style={{
        position: 'absolute', width: '45vw', height: '55vh',
        left: '-10vw', top: '15vh', opacity: 0.5,
        background: '#DDFF82', filter: 'blur(50px)',
        borderRadius: '50%', pointerEvents: 'none',
      }} />
      {/* Blob kanan atas kuning */}
      <div aria-hidden="true" style={{
        position: 'absolute', width: '40vw', height: '50vh',
        right: '-8vw', top: '5vh', opacity: 0.5,
        background: '#FFE85C', filter: 'blur(50px)',
        transform: 'rotate(-22deg)', transformOrigin: 'top left',
        borderRadius: '50%', pointerEvents: 'none',
      }} />
      {/* Blob kanan bawah pink */}
      <div aria-hidden="true" style={{
        position: 'absolute', width: '45vw', height: '50vh',
        right: '-12vw', bottom: '0', opacity: 0.5,
        background: '#F9DBFF', filter: 'blur(50px)',
        transform: 'rotate(150deg)', transformOrigin: 'top left',
        borderRadius: '50%', pointerEvents: 'none',
      }} />

      <div className="relative z-10 flex flex-col items-center min-h-screen">

        {/* Logo */}
        <div className="w-full text-center pt-10 pb-2">
          <span style={{
            color: '#BE0199', fontSize: 24,
            fontFamily: 'Qaligo, serif', fontWeight: 400,
          }}>
            ruangaman
          </span>
        </div>

        {/* Konten tengah */}
        <div className="flex-1 flex flex-col items-center justify-center px-8 max-w-xl mx-auto w-full py-10">

          {/* Tagline Figma */}
          <p className="text-center mb-8" style={{
            color: '#181818',
            fontSize: 'clamp(16px, 2.5vw, 22px)',
            fontFamily: 'Plus Jakarta Sans, sans-serif',
            fontWeight: 500, lineHeight: 1.6,
          }}>
            Anda tidak sendirian dalam perjalanan ini. Kami siap memahami dan melangkah bersama Anda.
          </p>

          {/* Divider tipis */}
          <div style={{ width: '100%', height: 1, background: 'rgba(0,0,0,0.08)', marginBottom: 28 }} />

          {/* Judul lama */}
          <h2 className="text-center mb-2" style={{
            color: '#181818',
            fontSize: 18,
            fontFamily: 'Plus Jakarta Sans, sans-serif',
            fontWeight: 700,
          }}>
            Apakah Anda Mengalami Kekerasan Seksual?
          </h2>

          <p className="text-center mb-6" style={{
            color: '#444444',
            fontSize: 14,
            fontFamily: 'Plus Jakarta Sans, sans-serif',
            fontWeight: 400, lineHeight: 1.6,
          }}>
            RuangAman membantu Anda mengidentifikasi apakah kejadian yang dialami memenuhi unsur tindak pidana menurut <strong>UU TPKS</strong> (Undang-Undang Tindak Pidana Kekerasan Seksual).
          </p>

          {/* Bullet list */}
          <ul className="w-full mb-8 space-y-3">
            {[
              { label: '100% Anonim', desc: 'Kami tidak meminta nama, email, atau data pribadi apapun.' },
              { label: 'Aman', desc: "Terdapat tombol 'Keluar Cepat' untuk menutup layar seketika." },
              { label: 'Dokumen BAP', desc: 'Dapatkan draf kronologis yang bisa diunduh untuk laporan.' },
            ].map(({ label, desc }) => (
              <li key={label} className="flex items-start gap-3">
                <span style={{
                  flexShrink: 0,
                  width: 22, height: 22,
                  background: '#F3FDD8',
                  border: '1px solid #000',
                  borderRadius: 4,
                  display: 'inline-flex',
                  alignItems: 'center',
                  justifyContent: 'center',
                  fontSize: 12,
                  fontWeight: 700,
                  marginTop: 1,
                }}>✓</span>
                <span style={{
                  fontSize: 14,
                  fontFamily: 'Plus Jakarta Sans, sans-serif',
                  color: '#181818', lineHeight: 1.5,
                }}>
                  <strong>{label}:</strong> {desc}
                </span>
              </li>
            ))}
          </ul>

          {errorMsg && (
            <div className="text-red-600 p-3 rounded-lg mb-4 text-sm border border-red-100 w-full text-center"
              style={{ background: 'rgba(255,255,255,0.6)' }}>
              {errorMsg}
            </div>
          )}

          {/* Tombol Figma */}
          <button
            onClick={handleStart}
            disabled={isStarting}
            style={{
              width: 'auto', minWidth: 161, height: 50,
              paddingLeft: 48, paddingRight: 48,
              paddingTop: 10, paddingBottom: 10,
              background: '#F3FDD8', borderRadius: 8,
              outline: '1px solid black', outlineOffset: '-1px',
              border: 'none',
              cursor: isStarting ? 'not-allowed' : 'pointer',
              display: 'inline-flex',
              justifyContent: 'center', alignItems: 'center',
              gap: 10, opacity: isStarting ? 0.7 : 1,
              transition: 'opacity 0.2s',
            }}
          >
            <span style={{
              color: '#000', fontSize: 16,
              fontFamily: 'Plus Jakarta Sans, sans-serif',
              fontWeight: 600, whiteSpace: 'nowrap',
            }}>
              {isStarting ? 'Memulai...' : 'Mulai Sesi Anonim'}
            </span>
          </button>

          {/* Footer darurat */}
          <p className="text-center mt-8" style={{
            fontSize: 12,
            fontFamily: 'Plus Jakarta Sans, sans-serif',
            color: '#888', lineHeight: 1.5,
          }}>
            Jika Anda dalam keadaan darurat, segera hubungi polisi di <strong>110</strong> atau SAPA <strong>129</strong>.
          </p>
        </div>

        {/* Icon bawah kiri & kanan */}
        <div className="w-full flex justify-between items-end px-12 pb-8">
          <span style={{ fontSize: 28 }} role="img" aria-label="sad face">😞</span>
          <span style={{ fontSize: 28 }} role="img" aria-label="help">❓</span>
        </div>

      </div>
    </div>
  );
}