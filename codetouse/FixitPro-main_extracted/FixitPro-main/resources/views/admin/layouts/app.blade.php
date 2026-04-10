<!DOCTYPE html>
<html lang="en">
@include('admin.includes.head')

<body class="bg-[#F8FAFC] min-h-screen flex flex-col antialiased selection:bg-blue-100 selection:text-blue-700"
    x-data="{ mobileMenu: false }">
    <!-- Navbar -->
    <nav class="glass-effect nav-glow sticky top-0 z-50">
        <div class="w-full mx-auto px-4 sm:px-8 lg:px-16">
            <div class="flex items-center justify-between h-20">
                <!-- Left Side: Logo & Main Navigation -->
                <div class="flex items-center gap-12">
                    <div class="flex-shrink-0">
                        <span class="text-white text-3xl font-bold tracking-tight">FixitPro</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <a href="{{ route('admin.dashboard') }}"
                            class="nav-btn-base {{ request()->routeIs('admin.dashboard') ? 'nav-active' : 'nav-inactive' }}">
                            <i class="fas fa-th-large mr-2.5"></i> Dashboard
                        </a>

                        @php
                            $permittedMenu = auth('admin')->user()->getPermittedMenu();
                        @endphp

                        @foreach ($permittedMenu as $category)
                            <div class="relative" x-data="{ open: false }">
                                <button @click="open = !open" :class="open ? 'nav-active' : 'nav-inactive'"
                                    class="nav-btn-base flex items-center gap-2">
                                    <i class="fas fa-cubes opacity-70"></i>
                                    {{ $category->name }}
                                    <i class="fas fa-chevron-down text-[10px] ml-1 transition-transform duration-300"
                                        :class="open ? 'rotate-180' : ''"></i>
                                </button>

                                <div x-show="open" @click.away="open = false"
                                    x-transition:enter="transition ease-out duration-200"
                                    x-transition:enter-start="opacity-0 translate-y-2 scale-95"
                                    x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                                    class="absolute left-0 mt-2 w-64 bg-[#1a1a1a] rounded-2xl dropdown-glow border border-white/10 py-3 z-50 overflow-hidden shadow-2xl">
                                    <div class="px-4 py-2 mb-2 border-b border-gray-50">
                                        <span
                                            class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">{{ $category->name }}
                                            Management</span>
                                    </div>
                                    @foreach ($category->modules as $module)
                                        <a href="{{ url($module->route) }}"
                                            class="flex items-center gap-3 px-5 py-3 text-sm font-semibold text-gray-300 hover:bg-white/5 hover:text-white transition-all border-l-4 border-transparent hover:border-blue-500">
                                            <i class="fas fa-circle text-[6px] opacity-40"></i>
                                            {{ $module->module_name }}
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Right Side: User Profile & Icons -->
                <div class="flex items-center gap-6">
                    <div class="hidden md:flex items-center gap-4">
                        <div
                            class="flex items-center gap-3 bg-white/5 hover:bg-white/10 px-5 py-2 rounded-2xl border border-white/10 transition-all group cursor-pointer">
                            <div
                                class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-500 to-blue-700 flex items-center justify-center text-white shadow-lg shadow-blue-500/20 group-hover:scale-105 transition-transform">
                                <i class="fas fa-user-shield"></i>
                            </div>
                            <div class="flex flex-col">
                                <span
                                    class="text-white text-sm font-bold leading-tight">{{ auth('admin')->user()->name }}</span>
                                <span
                                    class="text-blue-400 text-[10px] font-bold uppercase tracking-widest">Administrator</span>
                            </div>
                        </div>

                        <form action="{{ route('admin.logout') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit"
                                class="w-12 h-12 rounded-xl flex items-center justify-center text-gray-400 hover:text-white hover:bg-red-500/20 hover:text-red-400 transition-all border border-transparent hover:border-red-500/30"
                                title="Secure Logout">
                                <i class="fas fa-power-off text-xl"></i>
                            </button>
                        </form>
                    </div>

                    <!-- Mobile Menu Button -->
                    <div class="md:hidden">
                        <button @click="mobileMenu = !mobileMenu"
                            class="w-12 h-12 rounded-xl bg-white/5 text-gray-300 hover:text-white flex items-center justify-center transition-all">
                            <i class="fas" :class="mobileMenu ? 'fa-times' : 'fa-bars-staggered text-xl'"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div x-show="mobileMenu" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 -translate-y-8" x-transition:enter-end="opacity-100 translate-y-0"
            class="md:hidden bg-[#0A2647] border-t border-white/10 pb-8 overflow-y-auto max-h-[85vh] shadow-[0_20px_50px_rgba(0,0,0,0.5)]">
            <div class="px-6 py-6 space-y-4">
                <a href="{{ route('admin.dashboard') }}"
                    class="flex items-center gap-4 px-5 py-4 text-white font-bold rounded-2xl transition-all {{ request()->routeIs('admin.dashboard') ? 'bg-blue-600 shadow-lg shadow-blue-500/20' : 'bg-white/5' }}">
                    <i class="fas fa-th-large"></i> Dashboard
                </a>

                @foreach ($permittedMenu as $category)
                    <div x-data="{ open: false }">
                        <button @click="open = !open"
                            class="w-full flex items-center justify-between px-5 py-4 text-gray-300 font-bold rounded-2xl bg-white/5 transition-all">
                            <div class="flex items-center gap-4">
                                <i class="fas fa-layer-group opacity-60"></i>
                                {{ $category->name }}
                            </div>
                            <i class="fas fa-chevron-down text-xs transition-transform"
                                :class="open ? 'rotate-180' : ''"></i>
                        </button>
                        <div x-show="open" class="mt-2 ml-4 space-y-2 border-l-2 border-white/10 pl-4 py-2">
                            @foreach ($category->modules as $module)
                                <a href="{{ url($module->route) }}"
                                    class="block py-3 text-sm font-semibold text-gray-400 hover:text-white transition-colors">
                                    {{ $module->module_name }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endforeach

                <div class="pt-6 mt-6 border-t border-white/10">
                    <div class="flex items-center gap-4 px-5 py-4 bg-white/5 rounded-2xl">
                        <div
                            class="w-12 h-12 rounded-xl bg-blue-600 flex items-center justify-center text-white font-bold">
                            {{ substr(auth('admin')->user()->name, 0, 1) }}
                        </div>
                        <div class="flex flex-col">
                            <span class="text-white font-bold">{{ auth('admin')->user()->name }}</span>
                            <span class="text-gray-500 text-xs uppercase font-bold tracking-widest">Administrator</span>
                        </div>
                    </div>
                    <form action="{{ route('admin.logout') }}" method="POST" class="mt-4">
                        @csrf
                        <button type="submit"
                            class="w-full h-14 rounded-2xl bg-red-500/10 text-red-400 font-bold flex items-center justify-center gap-3 hover:bg-red-500 hover:text-white transition-all">
                            <i class="fas fa-power-off"></i> Secure Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <!-- Page Content -->
    <main class="py-12 flex-grow">
        <div class="w-full mx-auto px-4 sm:px-8 lg:px-16">
            @yield('content')
        </div>
    </main>

    @include('admin.layouts.drawer')
    @include('admin.includes.script')
</body>

</html>
