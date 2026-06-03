export default function QuickExitButton() {
  const handleQuickExit = () => {
    // 1. Hapus semua session storage
    sessionStorage.clear();
    // 2. Replace history agar tombol back tidak kembali ke app
    window.location.replace('https://www.google.com');
  };

  return (
    <button
      className="quick-exit-btn"
      onClick={handleQuickExit}
      title="Keluar Cepat (tekan Esc 2x)"
      aria-label="Keluar cepat dari aplikasi"
    >
      ✕ Keluar Cepat
    </button>
  )
}
