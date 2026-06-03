import { BrowserRouter as Router, Routes, Route } from 'react-router-dom';
import Landing from './pages/Landing';
import Session from './pages/Session';
import Conclusion from './pages/Conclusion';
import QuickExitButton from './components/QuickExitButton';

function App() {
  return (
    <Router>
      <div className="min-h-screen bg-safe-bg">
        <Routes>
          <Route path="/" element={<Landing />} />
          <Route path="/session" element={<Session />} />
          <Route path="/conclusion" element={<Conclusion />} />
        </Routes>
        <QuickExitButton />
      </div>
    </Router>
  )
}

export default App;
