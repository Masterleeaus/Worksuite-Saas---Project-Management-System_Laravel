<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script src="{{ asset('admin_assets/js/manager.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: "{{ session('success') }}",
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true
            });
        @endif

        @if (session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Access Denied',
                text: "{{ session('error') }}",
                confirmButtonColor: '#3b82f6'
            });
        @endif
    });
</script>

<footer class="bg-white border-t border-gray-100 py-8">
    <div class="w-full mx-auto px-4 sm:px-8 lg:px-16">
        <p class="text-center text-gray-500 text-sm font-medium">
            &copy; {{ date('Y') }} FixitPro Admin. All rights reserved.
        </p>
    </div>
</footer>
@yield('scripts')
