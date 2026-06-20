/**
 * AdminLoginButton.jsx
 * Pojok kiri bawah — link ke Laravel Filament admin panel.
 * Dibuat sangat subtle agar tidak mengganggu penyintas.
 */


import IconLogin from "../assets/IconLogin.svg";

export default function AdminLoginButton() {
  const handleClick = () => {
    // Buka di tab baru agar session penyintas tidak terganggu
    window.open('/admin', '_blank', 'noopener,noreferrer');
  };

  return (
    <button
      onClick={handleClick}
      title="Login Admin"
      aria-label="Login  ke panel admin"
      style={{
        position: 'fixed',
        bottom: 28,
        left: 28,
        zIndex: 9998,
        width: 40,
        height: 40,
        borderRadius: '50%',
        border: '1.5px solid rgba(0,0,0,0.15)',
        background: 'rgba(255,255,255,0.55)',
        backdropFilter: 'blur(6px)',
        WebkitBackdropFilter: 'blur(6px)',
        cursor: 'pointer',
        display: 'flex',
        alignItems: 'center',
        justifyContent: 'center',
        opacity: 0.4,
        transition: 'opacity 0.2s ease, transform 0.2s ease',
        boxShadow: '0 2px 8px rgba(0,0,0,0.08)',
      }}
      onMouseEnter={e => {
        e.currentTarget.style.opacity = '1';
        e.currentTarget.style.transform = 'scale(1.08)';
      }}
      onMouseLeave={e => {
        e.currentTarget.style.opacity = '0.4';
        e.currentTarget.style.transform = 'scale(1)';
      }}
    >
      {/* Lock icon — subtle */}
      <img
        src={IconLogin}
        alt="Lock"
        width={40}
        height={40}
      />
    </button>
  );
}