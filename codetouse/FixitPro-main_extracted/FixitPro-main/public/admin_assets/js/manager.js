const AdminManager = {
    openDrawer(url, title = 'Management') {
        $('#drawer-title').text(title);
        $('#drawer-content').html(`
            <div class="flex flex-col items-center justify-center h-64 gap-4">
                <div class="w-12 h-12 border-4 border-blue-600 border-t-transparent rounded-full animate-spin"></div>
                <p class="text-xs font-black text-gray-400 uppercase tracking-widest">Synchronizing Data...</p>
            </div>
        `);

        window.dispatchEvent(new CustomEvent('open-drawer'));

        $.ajax({
            url: url,
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            success: function (html) {
                $('#drawer-content').html(html);
                AdminManager.initFormSubmissions();
            },
            error: function () {
                Swal.fire('Error', 'Failed to load content', 'error');
            }
        });
    },

    initFormSubmissions() {
        $('#drawer-content form').on('submit', function (e) {
            e.preventDefault();
            AdminManager.submitForm(this);
        });
    },

    submitForm(form) {
        const $form = $(form);
        const formData = new FormData(form);
        const $submitBtn = $form.find('button[type="submit"]');
        const originalBtnHtml = $submitBtn.html();

        $submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Processing...');

        $.ajax({
            url: $form.attr('action'),
            method: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (data) {
                if (data.status === 1) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
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
                        window.dispatchEvent(new CustomEvent('close-drawer'));
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
            error: function (xhr) {
                let errorMsg = 'Something went wrong.';
                if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.errors) {
                    errorMsg = '';
                    $.each(xhr.responseJSON.errors, function (key, value) {
                        errorMsg += value[0] + '<br>';
                    });
                } else if (xhr.responseJSON && xhr.responseJSON.msg) {
                    errorMsg = xhr.responseJSON.msg;
                }
                Swal.fire({
                    icon: 'error',
                    title: 'Validation Error',
                    html: errorMsg,
                    confirmButtonColor: '#065f46' // Consistent with the new theme
                });
            },
            complete: function () {
                $submitBtn.prop('disabled', false).html(originalBtnHtml);
            }
        });
    },

    closeDrawer() {
        window.dispatchEvent(new CustomEvent('close-drawer'));
    }
};
