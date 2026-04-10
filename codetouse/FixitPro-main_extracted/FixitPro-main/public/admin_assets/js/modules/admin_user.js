const AdminUserModule = {
    deleteUser(url) {
        Swal.fire({
            title: 'Revoke Administrator Access?',
            text: "This administrator will be moved to the archive. You can restore them later if needed.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#065f46',
            cancelButtonColor: '#f3f4f6',
            confirmButtonText: 'Yes, Revoke Access',
            cancelButtonText: 'Cancel',
            customClass: {
                popup: 'premium-swal-popup',
                confirmButton: 'font-black uppercase tracking-widest text-[10px] px-8 py-4 rounded-2xl shadow-xl shadow-emerald-500/20',
                cancelButton: 'font-bold text-gray-400 uppercase tracking-widest text-[10px] px-8 py-4'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: url,
                    method: 'GET', // Or DELETE if routes are configured for it
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    success: function (data) {
                        if (data.status === 1) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Access Revoked!',
                                text: data.msg,
                                timer: 2500,
                                timerProgressBar: true,
                                showConfirmButton: false,
                                iconColor: '#ffffff',
                                background: '#ffffff',
                                customClass: {
                                    popup: 'premium-swal-success',
                                    icon: 'premium-swal-success-icon',
                                    title: 'premium-swal-title'
                                },
                            }).then(() => {
                                window.location.reload();
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: data.msg,
                                confirmButtonColor: '#065f46'
                            });
                        }
                    },
                    error: function () {
                        Swal.fire('Error', 'Something went wrong during deletion.', 'error');
                    }
                });
            }
        });
    }
};
