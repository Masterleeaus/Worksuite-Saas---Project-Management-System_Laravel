const ModuleObj = {
    deleteModule(url) {
        Swal.fire({
            title: 'Unregister System Module?',
            text: "This will remove the module from the system and sidebar. This action is permanent.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#065f46',
            cancelButtonColor: '#f3f4f6',
            confirmButtonText: 'Yes, Remove Module',
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
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    success: function (data) {
                        if (data.status === 1) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Module Removed!',
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
