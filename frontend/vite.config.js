import { defineConfig } from 'vite'
import react from '@vitejs/plugin-react'
export default defineConfig({
  plugins: [react()],
  server: {
    allowedHosts: true,
    // Tambahkan konfigurasi proxy ini
    proxy: {
      '/api': {
        target: 'http://127.0.0.1:8000', // URL backend Laravel Anda
        changeOrigin: true,
        secure: false,
      }
    }
  }
})