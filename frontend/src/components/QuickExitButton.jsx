import { useEffect, useRef } from 'react';
import useSessionStore from '../store/sessionStore';
import QuitIcon from '../assets/QuitIcon.svg';

/**
 * QuickExitButton.jsx
 * Posisi: fixed, tengah bawah halaman.
 * Trigger: klik tombol ATAU tekan Esc 2× dalam 1 detik.
 * Aksi: clear session → replace ke google.com (history tidak bisa back).
 */

export default function QuickExitButton() {
  const resetSession = useSessionStore((state) => state.resetSession);
  const escapeCount = useRef(0);
  const escapeTimer = useRef(null);

  const handleQuickExit = () => {
    resetSession();
    sessionStorage.clear();
    window.location.replace('https://www.google.com');
  };

  useEffect(() => {
    const handleKeyDown = (e) => {
      if (e.key === 'Escape') {
        escapeCount.current += 1;
        if (escapeCount.current >= 2) {
          handleQuickExit();
        }
        clearTimeout(escapeTimer.current);
        escapeTimer.current = setTimeout(() => {
          escapeCount.current = 0;
        }, 1000);
      }
    };
    window.addEventListener('keydown', handleKeyDown);
    return () => {
      window.removeEventListener('keydown', handleKeyDown);
      clearTimeout(escapeTimer.current);
    };
  }, []);

  return (
    <button
      onClick={handleQuickExit}
      title="Keluar Cepat (tekan Esc 2×)"
      aria-label="Keluar cepat dari aplikasi"
        style={{
          position: 'fixed',
          bottom: 28,
          left: '50%',
          transform: 'translateX(-50%)',
          zIndex: 9999,

          border: 'none',
          background: 'transparent',
          padding: 0,

          cursor: 'pointer',

          display: 'flex',
          alignItems: 'center',
          justifyContent: 'center',
        }}
            onMouseEnter={e => {
        e.currentTarget.style.transform =
          'translateX(-50%) scale(1.05)';
      }}

      onMouseLeave={e => {
        e.currentTarget.style.transform =
          'translateX(-50%) scale(1)';
      }}
    >
      {/* X icon */}
      <img
        src={QuitIcon}
        alt="Keluar Cepat"
        style={{
          width: '64px',
          height: '64px',
          display: 'block',
        }}
      />
    </button>
  );
}