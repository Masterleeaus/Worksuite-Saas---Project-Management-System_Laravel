@extends('admin.layouts.app')

@section('title', 'Executive Dashboard')

@section('content')
    {{-- Hero Banner --}}
    <div
        class="bg-gradient-to-r from-slate-900 via-blue-900 to-slate-900 text-white rounded-3xl shadow-2xl p-10 mb-10 relative overflow-hidden">
        <div class="relative z-10">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-3 h-3 rounded-full bg-emerald-400 animate-pulse"></div>
                <span class="text-emerald-400 text-xs font-bold uppercase tracking-widest">Platform Mission Control</span>
            </div>
            <h1 class="text-4xl font-black mb-3 tracking-tight">Executive Dashboard</h1>
            <p class="text-blue-200 text-lg opacity-80">Real-time pulse of your Field Service Management platform.</p>
        </div>
        <div class="absolute right-0 top-0 bottom-0 opacity-5 flex items-center p-8 pointer-events-none">
            <i class="fas fa-chart-line text-[200px] -rotate-12 translate-x-16"></i>
        </div>
    </div>

    {{-- KPI Cards Row --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
        {{-- MRR Card --}}
        <div
            class="bg-white p-8 rounded-3xl border border-gray-100 shadow-sm hover:shadow-2xl hover:-translate-y-2 transition-all duration-500 group relative overflow-hidden">
            <div
                class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-emerald-500/10 to-transparent rounded-bl-full">
            </div>
            <div class="flex items-center justify-between mb-6 relative z-10">
                <div
                    class="w-16 h-16 bg-gradient-to-br from-emerald-500 to-emerald-700 text-white rounded-2xl flex items-center justify-center shadow-lg shadow-emerald-500/30 group-hover:scale-110 transition-transform duration-500">
                    <i class="fas fa-dollar-sign text-2xl"></i>
                </div>
                <div
                    class="text-emerald-600 bg-emerald-50 px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest border border-emerald-100">
                    Revenue</div>
            </div>
            <div class="relative z-10">
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Monthly Recurring Revenue</p>
                <h3 class="text-4xl font-black text-gray-900">${{ number_format($stats['mrr'], 0) }}</h3>
                <p class="text-xs text-gray-400 mt-2 font-bold">
                    <span class="text-emerald-600">From {{ $stats['active_tenants'] }} active subscriptions</span>
                </p>
            </div>
        </div>

        {{-- Active Tenants Card --}}
        <div
            class="bg-white p-8 rounded-3xl border border-gray-100 shadow-sm hover:shadow-2xl hover:-translate-y-2 transition-all duration-500 group relative overflow-hidden">
            <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-blue-500/10 to-transparent rounded-bl-full">
            </div>
            <div class="flex items-center justify-between mb-6 relative z-10">
                <div
                    class="w-16 h-16 bg-gradient-to-br from-blue-500 to-blue-700 text-white rounded-2xl flex items-center justify-center shadow-lg shadow-blue-500/30 group-hover:scale-110 transition-transform duration-500">
                    <i class="fas fa-building text-2xl"></i>
                </div>
                <div
                    class="text-blue-600 bg-blue-50 px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest border border-blue-100">
                    Tenants</div>
            </div>
            <div class="relative z-10">
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Active Businesses</p>
                <h3 class="text-4xl font-black text-gray-900">{{ $stats['active_tenants'] }}</h3>
                <p class="text-xs text-gray-400 mt-2 font-bold">
                    <span class="text-amber-600">+{{ $stats['trial_tenants'] }} on trial</span>
                </p>
            </div>
        </div>

        {{-- Job Volume Card --}}
        <div
            class="bg-white p-8 rounded-3xl border border-gray-100 shadow-sm hover:shadow-2xl hover:-translate-y-2 transition-all duration-500 group relative overflow-hidden">
            <div
                class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-violet-500/10 to-transparent rounded-bl-full">
            </div>
            <div class="flex items-center justify-between mb-6 relative z-10">
                <div
                    class="w-16 h-16 bg-gradient-to-br from-violet-500 to-violet-700 text-white rounded-2xl flex items-center justify-center shadow-lg shadow-violet-500/30 group-hover:scale-110 transition-transform duration-500">
                    <i class="fas fa-wrench text-2xl"></i>
                </div>
                <div
                    class="text-violet-600 bg-violet-50 px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest border border-violet-100">
                    Jobs</div>
            </div>
            <div class="relative z-10">
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Today's Job Volume</p>
                <h3 class="text-4xl font-black text-gray-900">{{ $stats['today_jobs'] }}</h3>
                <p class="text-xs text-gray-400 mt-2 font-bold">
                    <span class="text-violet-600">{{ $stats['total_jobs'] }} total jobs</span>
                </p>
            </div>
        </div>

        {{-- Growth Rate Card --}}
        <div
            class="bg-white p-8 rounded-3xl border border-gray-100 shadow-sm hover:shadow-2xl hover:-translate-y-2 transition-all duration-500 group relative overflow-hidden">
            <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-rose-500/10 to-transparent rounded-bl-full">
            </div>
            <div class="flex items-center justify-between mb-6 relative z-10">
                <div
                    class="w-16 h-16 bg-gradient-to-br from-rose-500 to-rose-700 text-white rounded-2xl flex items-center justify-center shadow-lg shadow-rose-500/30 group-hover:scale-110 transition-transform duration-500">
                    <i class="fas fa-chart-line text-2xl"></i>
                </div>
                <div
                    class="text-rose-600 bg-rose-50 px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest border border-rose-100">
                    Growth</div>
            </div>
            <div class="relative z-10">
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">30-Day Growth Rate</p>
                <h3 class="text-4xl font-black text-gray-900 flex items-center gap-2">
                    {{ $stats['growth_rate'] >= 0 ? '+' : '' }}{{ $stats['growth_rate'] }}%
                    @if ($stats['growth_rate'] >= 0)
                        <i class="fas fa-arrow-up text-emerald-500 text-xl"></i>
                    @else
                        <i class="fas fa-arrow-down text-red-500 text-xl"></i>
                    @endif
                </h3>
                <p class="text-xs text-gray-400 mt-2 font-bold">
                    <span class="text-gray-500">vs previous 30 days</span>
                </p>
            </div>
        </div>
    </div>

    {{-- Charts Row --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-10">
        {{-- Signup Trend Chart --}}
        <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-8 py-6 border-b border-gray-100 flex items-center justify-between bg-gray-50/50">
                <div>
                    <h2 class="text-xl font-black text-gray-900">Business Signups</h2>
                    <p class="text-xs text-gray-400 font-bold mt-1">Last 30 days trend</p>
                </div>
                <div class="w-12 h-12 bg-blue-50 rounded-2xl flex items-center justify-center text-blue-500">
                    <i class="fas fa-user-plus text-lg"></i>
                </div>
            </div>
            <div class="p-6">
                <div id="signupChart" class="h-80"></div>
            </div>
        </div>

        {{-- MRR Trend Chart --}}
        <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-8 py-6 border-b border-gray-100 flex items-center justify-between bg-gray-50/50">
                <div>
                    <h2 class="text-xl font-black text-gray-900">MRR Growth</h2>
                    <p class="text-xs text-gray-400 font-bold mt-1">Revenue progression</p>
                </div>
                <div class="w-12 h-12 bg-emerald-50 rounded-2xl flex items-center justify-center text-emerald-500">
                    <i class="fas fa-chart-area text-lg"></i>
                </div>
            </div>
            <div class="p-6">
                <div id="mrrChart" class="h-80"></div>
            </div>
        </div>
    </div>

    {{-- Recent Signups & Quick Actions --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        {{-- Recent Tenant Signups --}}
        <div class="lg:col-span-2 bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-8 py-6 border-b border-gray-100 flex items-center justify-between bg-gray-50/50">
                <div>
                    <h2 class="text-xl font-black text-gray-900">Recent Business Signups</h2>
                    <p class="text-xs text-gray-400 font-bold mt-1">Newest tenants on the platform</p>
                </div>
                <span
                    class="text-[10px] font-black text-blue-600 bg-blue-50 px-4 py-2 rounded-full uppercase tracking-widest border border-blue-100">Live
                    Feed</span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-white">
                            <th class="px-8 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Business
                            </th>
                            <th class="px-8 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Plan</th>
                            <th class="px-8 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Status</th>
                            <th class="px-8 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Joined</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach ($recentSignups as $tenant)
                            <tr class="hover:bg-blue-50/30 transition-colors group">
                                <td class="px-8 py-5">
                                    <div class="flex items-center gap-4">
                                        <div
                                            class="w-12 h-12 rounded-2xl bg-gradient-to-br from-blue-500 to-blue-700 text-white flex items-center justify-center font-black text-lg shadow-lg shadow-blue-500/20 group-hover:scale-105 transition-transform">
                                            {{ substr($tenant->business_name, 0, 1) }}
                                        </div>
                                        <div>
                                            <span
                                                class="block font-bold text-gray-900 leading-tight">{{ $tenant->business_name }}</span>
                                            <span
                                                class="text-[10px] text-gray-400 font-bold">{{ $tenant->owner_name }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-8 py-5">
                                    <span
                                        class="inline-flex items-center px-3 py-1 rounded-lg text-[10px] font-black uppercase tracking-widest
                                {{ $tenant->plan_type === 'enterprise' ? 'bg-purple-50 text-purple-600 border border-purple-100' : '' }}
                                {{ $tenant->plan_type === 'professional' ? 'bg-blue-50 text-blue-600 border border-blue-100' : '' }}
                                {{ $tenant->plan_type === 'starter' ? 'bg-gray-50 text-gray-600 border border-gray-100' : '' }}
                            ">
                                        {{ $tenant->plan_type }}
                                    </span>
                                </td>
                                <td class="px-8 py-5">
                                    <span
                                        class="inline-flex items-center gap-2 text-[10px] font-black uppercase tracking-widest
                                {{ $tenant->status === 'active' ? 'text-emerald-600' : '' }}
                                {{ $tenant->status === 'trial' ? 'text-amber-600' : '' }}
                            ">
                                        <span
                                            class="w-2 h-2 rounded-full {{ $tenant->status === 'active' ? 'bg-emerald-500' : 'bg-amber-500' }}"></span>
                                        {{ $tenant->status }}
                                    </span>
                                </td>
                                <td class="px-8 py-5 text-sm text-gray-500 font-bold">
                                    {{ $tenant->created_at->diffForHumans() }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Platform Stats Panel --}}
        <div class="space-y-6">
            <div
                class="bg-gradient-to-br from-slate-900 to-slate-800 rounded-3xl p-8 text-white relative overflow-hidden shadow-2xl">
                <h2 class="text-lg font-black mb-6 relative z-10 flex items-center gap-2">
                    <i class="fas fa-server text-blue-400"></i> Platform Health
                </h2>
                <div class="space-y-5 relative z-10">
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm font-bold text-gray-300">Total Tenants</span>
                            <span class="text-xl font-black text-white">{{ $stats['total_tenants'] }}</span>
                        </div>
                        <div class="w-full bg-gray-700 h-2 rounded-full overflow-hidden">
                            <div class="bg-blue-500 h-full transition-all duration-1000" style="width: 100%"></div>
                        </div>
                    </div>
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm font-bold text-gray-300">Pending Jobs</span>
                            <span class="text-xl font-black text-amber-400">{{ $stats['pending_jobs'] }}</span>
                        </div>
                        <div class="w-full bg-gray-700 h-2 rounded-full overflow-hidden">
                            <div class="bg-amber-500 h-full transition-all duration-1000"
                                style="width: {{ min(($stats['pending_jobs'] / max($stats['total_jobs'], 1)) * 100, 100) }}%">
                            </div>
                        </div>
                    </div>
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm font-bold text-gray-300">System Status</span>
                            <span class="text-sm font-black text-emerald-400 flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                                All Systems Operational
                            </span>
                        </div>
                    </div>
                </div>
                <div class="absolute -right-8 -bottom-8 opacity-10">
                    <i class="fas fa-cogs text-[100px]"></i>
                </div>
            </div>

            {{-- Dashboard Insights --}}
            <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-8">
                <h2 class="text-lg font-black text-gray-900 mb-6 flex items-center gap-2">
                    <i class="fas fa-chart-line text-green-500"></i> Dashboard Insights
                </h2>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div class="p-4 bg-gray-50 rounded-2xl border border-gray-100 flex flex-col items-center">
                        <i class="fas fa-users text-2xl text-gray-400 mb-2"></i>
                        <span class="text-sm font-bold text-gray-600">Total Admin</span>
                        <span class="text-xl font-extrabold text-gray-900">{{ $admins ?? 0 }}</span>
                    </div>
                    <div class="p-4 bg-gray-50 rounded-2xl border border-gray-100 flex flex-col items-center">
                        <i class="fas fa-box-open text-2xl text-gray-400 mb-2"></i>
                        <span class="text-sm font-bold text-gray-600">Total Modules</span>
                        <span class="text-xl font-extrabold text-gray-900">{{ $modules ?? 0 }}</span>
                    </div>
                    <div class="p-4 bg-gray-50 rounded-2xl border border-gray-100 flex flex-col items-center">
                        <i class="fas fa-user-shield text-2xl text-gray-400 mb-2"></i>
                        <span class="text-sm font-bold text-gray-600">Roles</span>
                        <span class="text-xl font-extrabold text-gray-900">{{ $roles ?? 0 }}</span>
                    </div>
                    <div class="p-4 bg-gray-50 rounded-2xl border border-gray-100 flex flex-col items-center">
                        <i class="fas fa-layer-group text-2xl text-gray-400 mb-2"></i>
                        <span class="text-sm font-bold text-gray-600">Categories</span>
                        <span class="text-xl font-extrabold text-gray-900">{{ $categories ?? 0 }}</span>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Signup Trend Chart
            const signupData = @json($signupTrend);
            const signupOptions = {
                series: [{
                    name: 'New Signups',
                    data: signupData.map(item => item.count)
                }],
                chart: {
                    type: 'area',
                    height: 320,
                    fontFamily: 'Outfit, sans-serif',
                    toolbar: {
                        show: false
                    },
                    animations: {
                        enabled: true,
                        easing: 'easeinout',
                        speed: 1000
                    }
                },
                colors: ['#3b82f6'],
                fill: {
                    type: 'gradient',
                    gradient: {
                        shadeIntensity: 1,
                        opacityFrom: 0.4,
                        opacityTo: 0.05,
                        stops: [0, 90, 100]
                    }
                },
                stroke: {
                    curve: 'smooth',
                    width: 3
                },
                dataLabels: {
                    enabled: false
                },
                xaxis: {
                    categories: signupData.map(item => item.date),
                    labels: {
                        style: {
                            colors: '#94a3b8',
                            fontWeight: 600
                        },
                        rotate: -45,
                        rotateAlways: false
                    },
                    tickAmount: 10
                },
                yaxis: {
                    labels: {
                        style: {
                            colors: '#94a3b8',
                            fontWeight: 600
                        }
                    }
                },
                grid: {
                    borderColor: '#f1f5f9',
                    strokeDashArray: 4
                },
                tooltip: {
                    theme: 'dark',
                    style: {
                        fontFamily: 'Outfit'
                    }
                }
            };
            new ApexCharts(document.querySelector("#signupChart"), signupOptions).render();

            // MRR Trend Chart
            const mrrData = @json($mrrTrend);
            const mrrOptions = {
                series: [{
                    name: 'MRR',
                    data: mrrData.map(item => item.amount)
                }],
                chart: {
                    type: 'area',
                    height: 320,
                    fontFamily: 'Outfit, sans-serif',
                    toolbar: {
                        show: false
                    },
                    animations: {
                        enabled: true,
                        easing: 'easeinout',
                        speed: 1000
                    }
                },
                colors: ['#10b981'],
                fill: {
                    type: 'gradient',
                    gradient: {
                        shadeIntensity: 1,
                        opacityFrom: 0.4,
                        opacityTo: 0.05,
                        stops: [0, 90, 100]
                    }
                },
                stroke: {
                    curve: 'smooth',
                    width: 3
                },
                dataLabels: {
                    enabled: false
                },
                xaxis: {
                    categories: mrrData.map(item => item.date),
                    labels: {
                        style: {
                            colors: '#94a3b8',
                            fontWeight: 600
                        },
                        rotate: -45,
                        rotateAlways: false
                    },
                    tickAmount: 10
                },
                yaxis: {
                    labels: {
                        style: {
                            colors: '#94a3b8',
                            fontWeight: 600
                        },
                        formatter: function(val) {
                            return '$' + val.toLocaleString();
                        }
                    }
                },
                grid: {
                    borderColor: '#f1f5f9',
                    strokeDashArray: 4
                },
                tooltip: {
                    theme: 'dark',
                    style: {
                        fontFamily: 'Outfit'
                    },
                    y: {
                        formatter: function(val) {
                            return '$' + val.toLocaleString();
                        }
                    }
                }
            };
            new ApexCharts(document.querySelector("#mrrChart"), mrrOptions).render();
        });
    </script>
@endsection
