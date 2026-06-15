<style>
    .login-wrapper {
    max-width: 100%;
    -webkit-box-align: center;
    -ms-flex-align: center;
    align-items: center;
    justify-content: center;
    display: -ms-flexbox;
    display: flex;
    flex-wrap: wrap;
    -webkit-flex-wrap: wrap;
}
@media (max-width: 767.98px) {
    .student-course, .how-it-works, .new-course, .trend-course, .share-knowledge {
        padding: 10px 0;
    }
}
</style>

@if(!empty($user))
<div class="login-wrapper" style="height: 100%;">
    <div class="loginbox p-4">
        <div class="w-100">
            <div class="row align-items-center justify-content-center">
                <div class="col-md-5 col-12">
                    <div class="knowledge-img aos" data-aos="fade-up">
                        <img src="/frontend/assets/img/share.png" alt="Img" class="img-fluid">
                    </div>
                </div>
                <div class="col-md-6 col-12">
                    @if (session('error'))
                        <div class="alert alert-danger" role="alert">
                            {{ session('error') }}
                        </div>
                    @endif
                    <h1 class="fw-bold text-center">Verify Your Mobile</h1>
                    <form action="{{ route('verify.confirmation.login', ['user' => $user->route_key]) }}" method="POST" enctype="multipart/form-data" autocomplete="off" id="verifymobile-form">
                        @csrf
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="input-block">
                                    <label class="form-control-label" for="mobile">Mobile*</label>
                                    <input type="text" class="form-control" id="mobile" name="mobile"
                                        placeholder="Enter your mobile" value="{{ $user->mobile }}" readonly onKeyPress="if(this.value.length==12) return false;" oninput="this.value=this.value.replace(/[^0-9]/g,'');">
                                </div>
                                <div id="mobile-error" style="color:red"></div>
                            </div>
                            <div class="col-lg-12">
                                <div class="input-block">
                                    <label class="form-control-label" for="mobile">OTP Code*</label>
                                    <input type="text" class="form-control" id="password" name="password" placeholder="Enter the otp code ..." required onKeyPress="if(this.value.length==4) return false;" oninput="this.value=this.value.replace(/[^0-9]/g,'');">
                                </div>
                                <div id="password-error" style="color:red"></div>
                            </div>
                            <div class="col-lg-12 payment-btn mt-4">
                                <button class="btn btn-primary" type="submit" style="padding: 10px 20px; font-size: 16px;">Verify</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@else
<div class="container mt-5">
    <div class="alert alert-danger text-center" role="alert">
        <h1 class="display-4">Invalid User</h1>
        <p class="lead">It seems like the mobile number you entered is not valid. Please check and try again.</p>
        <hr>
        <a href="{{ url()->previous() }}" class="btn btn-primary btn-lg mt-3">Go Back</a>
    </div>
</div>
@endif


<script>
    $(document).on('submit', '#verifymobile-form', function (e) {
        e.preventDefault(); // Prevent default form submission
        $('div[id$="-error"]').empty(); // Clear previous error messages

        const url = $(this).attr('action'); // Get the form action URL
        const loadingToast = toastr.info('Processing your request...', { timeOut: 0, extendedTimeOut: 0 }); // Show loading toast

        // Show the loading modal
        $('#modal-loading').modal('show');

        $.ajax({
            type: "POST",
            url: url,
            data: new FormData(this),
            contentType: false,
            cache: false,
            processData: false,
            success: function (response) {
                toastr.clear(loadingToast); // Clear loading toast
                $('#modal-loading').modal('hide'); // Hide the loading modal
                if (response.status === 'success') {
                    toastr.success(response.message, '', {
                        showMethod: "slideDown",
                        hideMethod: "slideUp",
                        timeOut: 1000,
                        closeButton: true,
                       
                    });
                    setTimeout(function() {
                        window.location.href = "/admin/student-dashboard";
                    }, 1000);
                } else {
                    toastr.error('There is some error!', '', {
                        showMethod: "slideDown",
                        hideMethod: "slideUp",
                        timeOut: 1500,
                        closeButton: true,
                    });
                }
              
            },
            error: function (xhr) {
                toastr.clear(loadingToast);
                $('#modal-loading').modal('hide');
                if (xhr.status == 401 || xhr.status == 403 || xhr.status == 404 || xhr.status == 422) {
                    var errorMessage = xhr.responseJSON ? xhr.responseJSON.message : xhr.statusText;
                    $("#password-error").html(errorMessage);
                }
                if (xhr.status === 422) {
                    const errors = xhr.responseJSON.errors;
                    let firstErrorField = null;

                    $.each(errors, function (key, value) {
                        console.log(key ,value);
                        const errorDiv = $('#' + key + '-error');
                        if (errorDiv.length) {
                            errorDiv.html(value[0]);
                        } else {
                            const inputField = $('[name="' + key + '"]');
                            if (inputField.length) {
                                inputField.after('<div id="' + key + '-error" class="text-danger">' + value[0] + '</div>');
                            }
                        }

                        if (!firstErrorField) {
                            firstErrorField = $('[name="' + key + '"]');
                        }
                    });

                    if (firstErrorField) {
                        $('html, body').animate({
                            scrollTop: firstErrorField.offset().top - 100
                        }, 500);
                        firstErrorField.focus();
                    }
                } else {
                    toastr.error('There was an error submitting the form. Please try again.', '', {
                        showMethod: "slideDown",
                        hideMethod: "slideUp",
                        timeOut: 1500,
                        closeButton: true,
                    });
                }
            }
        });
    });
</script>


