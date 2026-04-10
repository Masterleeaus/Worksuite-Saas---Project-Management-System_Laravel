<div x-data="{ open: false }" 
     @open-drawer.window="open = true"
     @close-drawer.window="open = false"
     class="relative z-[100]" 
     x-show="open" 
     style="display: none;">
    
    <!-- Backdrop -->
    <div x-show="open" 
         x-transition:enter="ease-in-out duration-500" 
         x-transition:enter-start="opacity-0" 
         x-transition:enter-end="opacity-100" 
         x-transition:leave="ease-in-out duration-500" 
         x-transition:leave-start="opacity-100" 
         x-transition:leave-end="opacity-0" 
         class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" 
         @click="open = false"></div>

    <div class="fixed inset-0 overflow-hidden">
        <div class="absolute inset-0 overflow-hidden">
            <div class="pointer-events-none fixed inset-y-0 right-0 flex max-w-full pl-10 sm:pl-16">
                <div x-show="open" 
                     x-transition:enter="transform transition ease-in-out duration-500 sm:duration-700" 
                     x-transition:enter-start="translate-x-full" 
                     x-transition:enter-end="translate-x-0" 
                     x-transition:leave="transform transition ease-in-out duration-500 sm:duration-700" 
                     x-transition:leave-start="translate-x-0" 
                     x-transition:leave-end="translate-x-full" 
                     class="pointer-events-auto w-screen max-w-2xl"
                     @click.away="open = false">
                    
                    <div class="flex h-full flex-col overflow-y-scroll bg-white shadow-2xl rounded-l-[3rem] border-l border-gray-100">
                        <!-- Header -->
                        <div class="px-8 py-6 bg-gradient-to-r from-gray-50 to-white border-b border-gray-100 flex items-center justify-between sticky top-0 z-20">
                            <h2 class="text-xl font-black text-gray-900 tracking-tight" id="drawer-title">Admin Management</h2>
                            <button @click="open = false" class="w-10 h-10 flex items-center justify-center bg-gray-100 rounded-2xl text-gray-400 hover:text-red-500 hover:bg-red-50 transition-all">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>

                        <!-- Content Area -->
                        <div class="relative flex-1 p-0" id="drawer-content">
                            <!-- AJAX Content will be injected here via jQuery -->
                            <div class="flex items-center justify-center p-12">
                                <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
