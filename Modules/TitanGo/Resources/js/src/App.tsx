// Titan Go — Root App Component
// Today-first worker execution surface.

import React from 'react';
import { Zap, Wifi, WifiOff, RefreshCw, LogOut } from 'lucide-react';
import { useAuth } from './hooks/useAuth';
import { useOnlineStatus } from './hooks/useOnlineStatus';
import { useVisits } from './hooks/useVisits';
import { LoginScreen } from './components/LoginScreen';
import { TodayScreen } from './components/TodayScreen';
import { VisitView } from './components/VisitView';
import { QuickActions } from './components/QuickActions';
import { syncService } from './services/sync.service';

function App() {
  const { session, loading: authLoading, error: authError, login, logout } = useAuth();
  const isOnline = useOnlineStatus();
  const {
    visits, nextVisit, summary, activeVisit, activeVisitId,
    loading: visitsLoading, setActiveVisitId,
    completeStep, checkIn, checkOut, complete, refresh,
  } = useVisits(isOnline);

  const [pendingCount, setPendingCount] = React.useState(() => syncService.getPendingCount());
  React.useEffect(() => syncService.subscribe(q => setPendingCount(q.filter(i => i.status === 'pending').length)), []);

  // ── Not authenticated ──────────────────────────────────────────────────────
  if (!session) {
    return <LoginScreen onLogin={login} loading={authLoading} error={authError} />;
  }

  // ── Visit detail ──────────────────────────────────────────────────────────
  if (activeVisit) {
    return (
      <div className="flex flex-col h-screen w-full bg-[#07070a] text-zinc-300 font-mono">
        {/* Status bar */}
        <div className="flex items-center justify-between px-4 py-2 bg-[#0f0f14] border-b border-zinc-800">
          <div className="flex items-center gap-2">
            <Zap size={14} className="text-amber-500" />
            <span className="text-[9px] font-black text-zinc-500 uppercase tracking-widest">
              Titan Go
            </span>
          </div>
          <div className="flex items-center gap-3">
            {pendingCount > 0 && (
              <span className="text-[9px] font-black text-amber-500 uppercase">
                {pendingCount} queued
              </span>
            )}
            {isOnline
              ? <Wifi size={12} className="text-emerald-500" />
              : <WifiOff size={12} className="text-red-500" />
            }
          </div>
        </div>

        {/* Visit view */}
        <div className="flex-1 overflow-hidden">
          <VisitView
            visit={activeVisit}
            isOnline={isOnline}
            onBack={() => setActiveVisitId(null)}
            onCheckIn={checkIn}
            onCheckOut={checkOut}
            onComplete={complete}
            onStepComplete={completeStep}
          />
        </div>

        {/* Quick-action bar */}
        <QuickActions
          visitId={activeVisit.id}
          isOnline={isOnline}
        />
      </div>
    );
  }

  // ── Today screen ──────────────────────────────────────────────────────────
  return (
    <div className="flex flex-col h-screen w-full bg-[#07070a] text-zinc-300 font-mono">
      {/* Header */}
      <header className="flex items-center justify-between px-5 py-4 bg-[#0f0f14] border-b border-zinc-800">
        <div className="flex items-center gap-3">
          <div className="w-7 h-7 bg-amber-500 rounded-lg flex items-center justify-center">
            <Zap size={14} className="text-black" fill="black" />
          </div>
          <div>
            <p className="text-[9px] font-black text-zinc-600 uppercase tracking-widest">
              {session.worker.name}
            </p>
            <p className="text-[11px] font-black text-white uppercase tracking-wider">
              Titan Go
            </p>
          </div>
        </div>

        <div className="flex items-center gap-3">
          {pendingCount > 0 && (
            <button
              onClick={() => isOnline && syncService.flush()}
              className="flex items-center gap-1 text-[9px] font-black text-amber-400 uppercase"
              title="Flush offline queue"
            >
              <RefreshCw size={10} /> {pendingCount}
            </button>
          )}
          {isOnline
            ? <Wifi size={14} className="text-emerald-500" />
            : <WifiOff size={14} className="text-red-500" />
          }
          <button
            onClick={logout}
            className="text-zinc-600 hover:text-white transition-colors"
            title="Logout"
          >
            <LogOut size={14} />
          </button>
        </div>
      </header>

      {/* Main content */}
      <main className="flex-1 overflow-y-auto p-4">
        <TodayScreen
          visits={visits}
          nextVisit={nextVisit}
          summary={summary}
          onSelectVisit={setActiveVisitId}
          onRefresh={refresh}
          loading={visitsLoading}
        />
      </main>
    </div>
  );
}

export default App;
