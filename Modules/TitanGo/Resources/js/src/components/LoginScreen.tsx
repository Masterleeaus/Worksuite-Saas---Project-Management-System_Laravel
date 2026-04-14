// Titan Go — LoginScreen
import React, { useState } from 'react';
import { Zap } from 'lucide-react';

interface Props {
  onLogin: (email: string, password: string) => void;
  loading: boolean;
  error: string | null;
}

export function LoginScreen({ onLogin, loading, error }: Props) {
  const [email,    setEmail]    = useState('');
  const [password, setPassword] = useState('');

  const submit = (e: React.FormEvent) => {
    e.preventDefault();
    if (email && password) onLogin(email, password);
  };

  return (
    <div className="min-h-screen flex items-center justify-center bg-[#07070a] px-6">
      <div className="w-full max-w-sm space-y-8">
        {/* Logo */}
        <div className="flex flex-col items-center gap-3">
          <div className="w-14 h-14 bg-amber-500 rounded-2xl flex items-center justify-center">
            <Zap size={28} className="text-black" fill="black" />
          </div>
          <div className="text-center">
            <h1 className="text-xl font-black text-white uppercase tracking-widest">Titan Go</h1>
            <p className="text-[10px] text-zinc-500 uppercase tracking-widest mt-1">
              Field Execution Client
            </p>
          </div>
        </div>

        {/* Form */}
        <form onSubmit={submit} className="space-y-4">
          <div>
            <label className="block text-[10px] font-black uppercase text-zinc-500 tracking-widest mb-1">
              Email
            </label>
            <input
              type="email"
              value={email}
              onChange={e => setEmail(e.target.value)}
              required
              className="w-full bg-zinc-900 border border-zinc-800 rounded-lg px-4 py-3 text-sm text-white focus:outline-none focus:border-amber-500"
            />
          </div>
          <div>
            <label className="block text-[10px] font-black uppercase text-zinc-500 tracking-widest mb-1">
              Password
            </label>
            <input
              type="password"
              value={password}
              onChange={e => setPassword(e.target.value)}
              required
              className="w-full bg-zinc-900 border border-zinc-800 rounded-lg px-4 py-3 text-sm text-white focus:outline-none focus:border-amber-500"
            />
          </div>

          {error && (
            <p className="text-red-400 text-xs text-center">{error}</p>
          )}

          <button
            type="submit"
            disabled={loading}
            className="w-full bg-amber-500 hover:bg-amber-400 disabled:opacity-50 text-black font-black text-xs uppercase tracking-widest py-4 rounded-xl transition-all active:scale-95"
          >
            {loading ? 'Signing in…' : 'Sign In'}
          </button>
        </form>
      </div>
    </div>
  );
}
