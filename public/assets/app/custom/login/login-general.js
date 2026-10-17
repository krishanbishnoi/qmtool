"use strict";

// Class Definition
var KTLoginGeneral = function() {

    var login = $('#kt_login');

    var showErrorMsg = function(form, type, msg) {
        var alert = $('<div class="kt-alert kt-alert--outline alert alert-' + type + ' alert-dismissible" role="alert">\
			<button type="button" class="close" data-dismiss="alert" aria-label="Close"></button>\
			<span></span>\
		</div>');

        form.find('.alert').remove();
        alert.prependTo(form);
        //alert.animateClass('fadeIn animated');
        KTUtil.animateClass(alert[0], 'fadeIn animated');
        alert.find('span').html(msg);
    }

    // Private Functions
    var displaySignUpForm = function() {
        login.removeClass('kt-login--forgot');
        login.removeClass('kt-login--signin');
        login.removeClass('kt-login--verify-user');
        login.removeClass('kt-login--verify-otp');

        login.addClass('kt-login--signup');
        KTUtil.animateClass(login.find('.kt-login__signup')[0], 'flipInX animated');
    }

    var displaySignInForm = function() {
        login.removeClass('kt-login--forgot');
        login.removeClass('kt-login--signup');
        login.removeClass('kt-login--verify-user');
        login.removeClass('kt-login--verify-otp');

        login.addClass('kt-login--signin');
        KTUtil.animateClass(login.find('.kt-login__signin')[0], 'flipInX animated');
        //login.find('.kt-login__signin').animateClass('flipInX animated');
    }

    var displayForgotForm = function() {
        login.removeClass('kt-login--signin');
        login.removeClass('kt-login--signup');
        login.removeClass('kt-login--verify-user');
        login.removeClass('kt-login--verify-otp');

        login.addClass('kt-login--forgot');
        //login.find('.kt-login--forgot').animateClass('flipInX animated');
        KTUtil.animateClass(login.find('.kt-login__forgot')[0], 'flipInX animated');

    }

    var displayVerifyUserForm = function() {
        login.removeClass('kt-login--signin');
        login.removeClass('kt-login--signup');
        login.removeClass('kt-login--forget');
        login.removeClass('kt-login--verify-otp');

        login.addClass('kt-login--verify-user');
        //login.find('.kt-login__forget-password').animateClass('flipInX animated');
        KTUtil.animateClass(login.find('.kt-login__verify-user')[0], 'flipInX animated');

    }

    var displayVerifyOtpForm = function() {
        login.removeClass('kt-login--signin');
        login.removeClass('kt-login--signup');
        login.removeClass('kt-login--forget');
        login.removeClass('kt-login--verify-user');

        login.addClass('kt-login--verify-otp');
        KTUtil.animateClass(login.find('.kt-login__verify-otp')[0], 'flipInX animated');

    }

    var handleFormSwitch = function() {
        $('#kt_login_forgot').click(function(e) {
            e.preventDefault();
            displayForgotForm();
            // displayVerifyUserForm();

        });

        $('#kt_login_forgot_cancel').click(function(e) {
            e.preventDefault();
            displaySignInForm();
        });

        $('#kt_login_signup').click(function(e) {
            e.preventDefault();
            displaySignUpForm();
        });

        $('#kt_login_signup_cancel').click(function(e) {
            e.preventDefault();
            displaySignInForm();
        });

        // $('#kt_login_otp_verify').click(function(e) {
        //     e.preventDefault();
        //     displayVerifyOtpForm();
        // });
    }

    var handleSignInFormSubmit = function() {
        $('#kt_login_signin_submit').click(function(e) {
            e.preventDefault();
            var btn = $(this);
            var form = $(this).closest('form');           

            form.validate({
                rules: {
                    email: {
                        required: true,
                        email: true
                    },
                    password: {
                        required: true
                    }
                }
            });

            if (!form.valid()) {
                return;
            }

            btn.addClass('kt-spinner kt-spinner--right kt-spinner--sm kt-spinner--light').attr('disabled', true);

            form.ajaxSubmit({

                url: 'login',
                success: function(response, status, xhr, $form) {
                    btn.removeClass('kt-spinner kt-spinner--right kt-spinner--sm kt-spinner--light').attr('disabled', false);
                    if (xhr.status==201) {
                        showErrorMsg(form, 'success', 'You Have 2FA Enabled OTP Sent to Your Mail...');
                        setTimeout(function() {
                            displayVerifyOtpForm();
                        }, 2000);
                        
                    } else {
                        showErrorMsg(form, 'success', 'Login successfully...');
                        setTimeout(function() {
                            window.location="/home";
                        }, 2000);
                    }
                },
                error: function (xhr, ajaxOptions, thrownError, $form) {
                    btn.removeClass('kt-spinner kt-spinner--right kt-spinner--sm kt-spinner--light').attr('disabled', false);
                    if(xhr.status==423)
                    {
                        var temp_email = form.find('input[name="email"]').val();
                        form.clearForm();
                        form.validate().resetForm();

                        displayVerifyUserForm();
                        var verifyUser = login.find('.kt-login__verify-user form');
                        verifyUser.clearForm();
                        verifyUser.validate().resetForm();
                        verifyUser.find('input[name="email"]').val(temp_email);

                        showErrorMsg(verifyUser, 'warning', "Email not verified, please verify your email...");
                    }else if(xhr.status==424)
                    {   
                        form.clearForm();
                        form.validate().resetForm();

                        showErrorMsg(form, 'danger', "Your account is deactivated by admin, please contact login.");
                        setTimeout(function() {
                            window.location="/contact";
                        }, 2000);
                    }else
                    {
                        var errors = xhr.responseJSON.errors;
                        let errorsHtml='';
                        btn.removeClass('kt-spinner kt-spinner--right kt-spinner--sm kt-spinner--light').attr('disabled', false);
                        $.each( errors, function( key, value ) {
                            errorsHtml += '<li>' + value + '</li>'; //showing only the first error.
                        });
                         
                        showErrorMsg(form, 'danger', errorsHtml);
                    }

                }

            });
        });
    }

    var handleSignUpFormSubmit = function() {
        $('#kt_login_signup_submit').click(function(e) {
            e.preventDefault();

            var btn = $(this);
            var form = $(this).closest('form');

            form.validate({
                rules: {
                    _type: {
                        required: true
                    },
                    name: {
                        required: true
                    },
                    email: {
                        required: true,
                        email: true
                    },
                    mobile: {
                        required: true
                    },
                    password: {
                        required: true
                    },
                    password_confirmation: {
                        required: true
                    },
                    agree: {
                        required: true
                    }
                }
            });

            if (!form.valid()) {
                return;
            }

            btn.addClass('kt-spinner kt-spinner--right kt-spinner--sm kt-spinner--light').attr('disabled', true);

            form.ajaxSubmit({

                url: 'register',
                success: function(response, status, xhr, $form) {
                    
                    // similate 2s delay
                    setTimeout(function() {
                        btn.removeClass('kt-spinner kt-spinner--right kt-spinner--sm kt-spinner--light').attr('disabled', false);
                        form.clearForm();
                        form.validate().resetForm();

                        // display signup form
                        displaySignInForm();
                        var signInForm = login.find('.kt-login__signin form');
                        signInForm.clearForm();
                        signInForm.validate().resetForm();

                        showErrorMsg(signInForm, 'success', response.message);
                    }, 2000);
                },
                error: function (xhr, ajaxOptions, thrownError, $form) {
                    var errors = xhr.responseJSON.errors;
                    let errorsHtml='';
                    $.each( errors, function( key, value ) {
                        errorsHtml += '<li>' + value + '</li>'; //showing only the first error.
                    });
                    
                     btn.removeClass('kt-spinner kt-spinner--right kt-spinner--sm kt-spinner--light').attr('disabled', false);
                     showErrorMsg(form, 'danger', errorsHtml);
                 }
            });
        });
    }

    var handleForgotFormSubmit = function() {
        $('#kt_login_forgot_submit').click(function(e) {
            e.preventDefault();

            var btn = $(this);
            var form = $(this).closest('form');

            form.validate({
                rules: {
                    email: {
                        required: true,
                        email: true
                    }
                }
            });

            if (!form.valid()) {
                return;
            }

            btn.addClass('kt-spinner kt-spinner--right kt-spinner--sm kt-spinner--light').attr('disabled', true);

            form.ajaxSubmit({

                url: 'password/email',
                success: function(response, status, xhr, $form) {
                    // similate 2s delay
                    setTimeout(function() {
                        btn.removeClass('kt-spinner kt-spinner--right kt-spinner--sm kt-spinner--light').attr('disabled', false);
                        form.clearForm(); // clear form
                        form.validate().resetForm(); // reset validation states

                        // display signup form
                        displaySignInForm();
                        var signInForm = login.find('.kt-login__signin form');
                        signInForm.clearForm();
                        signInForm.validate().resetForm();

                        showErrorMsg(signInForm, 'success', 'Cool! Password recovery instruction has been sent to your email.');
                    }, 2000);
                },
                error: function (xhr, ajaxOptions, thrownError, $form) {
                     btn.removeClass('kt-spinner kt-spinner--right kt-spinner--sm kt-spinner--light').attr('disabled', false);
                     showErrorMsg(form, 'danger', xhr.responseJSON.message);
                }
            });
        });
    }

    var handleVerifyOtpFormSubmit = function() {
        $('#kt_login_otp_submit').click(function(e) {
            e.preventDefault();
            
            var btn = $(this);
            var form = $(this).closest('form');

            form.validate({
                rules: {
                    otp: {
                        required: true
                    }
                }
            });

            if (!form.valid()) {
                return;
            }

            btn.addClass('kt-spinner kt-spinner--right kt-spinner--sm kt-spinner--light').attr('disabled', true);
            // console.log('hello');
            
            form.ajaxSubmit({
                url: 'verify_otp',
                success: function(response, status, xhr, $form) {
                    console.log(response);
                        btn.removeClass('kt-spinner kt-spinner--right kt-spinner--sm kt-spinner--light').attr('disabled', false);
                        if (xhr.status==201) {
                            console.log(response);
                            showErrorMsg(form, 'success', 'OTP verification complete.');
                            setTimeout(function() {
                                window.location="/home";
                                }, 2000);
                        } else {
                            showErrorMsg(form, 'danger', 'You Enter Wrong OTP.');
                        }

                },
                error: function (xhr, ajaxOptions, thrownError, $form) {
                     btn.removeClass('kt-spinner kt-spinner--right kt-spinner--sm kt-spinner--light').attr('disabled', false);
                     showErrorMsg(form, 'danger', xhr.responseJSON.message);
                }
            });
        });
    }


    var handleVerifyUserFormSubmit = function() {
        $('#m_login_verify_user_submit').click(function(e) {
            e.preventDefault();
            
            var btn = $(this);
            var form = $(this).closest('form');

            form.validate({
                rules: {
                    mobile: {
                        required: true
                    },
                    otp: {
                        required: true
                    }
                }
            });

            if (!form.valid()) {
                return;
            }

            btn.addClass('kt-spinner kt-spinner--right kt-spinner--sm kt-spinner--light').attr('disabled', true);

            
            form.ajaxSubmit({
                url: 'api/verify_mobile',
                success: function(response, status, xhr, $form) {
                    // similate 2s delay
                        btn.removeClass('kt-spinner kt-spinner--right kt-spinner--sm kt-spinner--light').attr('disabled', false);
                        form.clearForm(); // clear form
                        form.validate().resetForm(); // reset validation states

                        // // display signup form
                        //displaySignInForm();
                        $("#kt_login_forgot_cancel").trigger("click");
                        // var signInForm = login.find('.kt-login__signin form');
                        // signInForm.clearForm();
                        // signInForm.validate().resetForm();

                        showErrorMsg(form, 'success', 'OTP verification complete. Please login.');
                        setTimeout(function() {
                        window.location="/login";
                        }, 2000);

                },
                error: function (xhr, ajaxOptions, thrownError, $form) {
                     btn.removeClass('kt-spinner kt-spinner--right kt-spinner--sm kt-spinner--light').attr('disabled', false);
                     showErrorMsg(form, 'danger', xhr.responseJSON.message);
                }
            });
        });
    }



    var handleVerifyUserResendFormSubmit = function() {
        $('#m_login_verify_user_cancel').click(function(e) {
            e.preventDefault();
            var btn = $(this);
            var form = $(this).closest('form');

            form.validate({
                rules: {
                    email: {
                        required: true,
                        email: true
                    }
                }
            });

            if (!form.valid()) {
                return;
            }

            btn.addClass('kt-spinner kt-spinner--right kt-spinner--sm kt-spinner--light').attr('disabled', true);
            
            form.ajaxSubmit({
                url: 'email/resend',
                success: function(response, status, xhr, $form) {
                    btn.removeClass('kt-spinner kt-spinner--right kt-spinner--sm kt-spinner--light').attr('disabled', false);
                    form.clearForm(); // clear form
                    form.validate().resetForm(); // reset validation states
                    // display signup form
                    displaySignInForm();
                    var signInForm = login.find('.kt-login__signin form');
                    signInForm.clearForm();
                    signInForm.validate().resetForm();

                    showErrorMsg(signInForm, 'success', response.message);
                },
                error: function (xhr, ajaxOptions, thrownError, $form) {
                     btn.removeClass('kt-spinner kt-spinner--right kt-spinner--sm kt-spinner--light').attr('disabled', false);
                     showErrorMsg(form, 'danger', xhr.responseJSON.message);
                }
            });
        });
    }

    // Public Functions
    return {
        // public functions
        init: function() {
            handleFormSwitch();
            handleSignInFormSubmit();
            handleSignUpFormSubmit();
            handleForgotFormSubmit();
            handleVerifyUserFormSubmit();
            handleVerifyOtpFormSubmit();
            handleVerifyUserResendFormSubmit();
        }
    };
}();

// Class Initialization
jQuery(document).ready(function() {
    KTLoginGeneral.init();
});