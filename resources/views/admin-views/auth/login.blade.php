

<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Required Meta Tags Always Come First -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Title -->
    <title>{{\App\CPU\translate('Admin | Login')}}</title>

    <!-- Favicon -->
    <link rel="shortcut icon" href="favicon.ico">

    <!-- Font -->
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&amp;display=swap" rel="stylesheet">
    <!-- CSS Implementing Plugins -->
    <link rel="stylesheet" href="{{asset('assets/back-end')}}/css/vendor.min.css">
    <link rel="stylesheet" href="{{asset('assets/back-end')}}/vendor/icon-set/style.css">
    <!-- CSS Front Template -->
    <link rel="stylesheet" href="{{asset('assets/back-end')}}/css/theme.minc619.css?v=1.0">
    <link rel="stylesheet" href="{{asset('assets/back-end')}}/css/toastr.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">
    <style>
        body{
            background-image: url({{asset('banner4_bg.jpg')}});
                background-position: center;
    background-size: cover;
        }
        .input-icons i {
            position: absolute;
            cursor: pointer;
        }

        .input-icons {
            width: 100%;
            margin-bottom: 10px;
        }

        .icon {
            padding: 9% 0 0 0;
            min-width: 40px;
        }

        .input-field {
            width: 94%;
            padding: 10px 0 10px 10px;
            text-align: center;
            border-right-style: none;
        }
        .card {
    background: none;
    border: none;
    border-radius: 0px;
    width: 90%;
    margin: auto;
}
    .form-group input,.input-captcha {
    border-radius: 5px;
    height: 45px;
    /*color: #fff !important;*/
    border: 1px solid #a0c63a !important;
    text-align: center;
}
::placeholder { /* Chrome, Firefox, Opera, Safari 10.1+ */
  color: #d3d3d3;
  opacity: 1; /* Firefox */
}

:-ms-input-placeholder { /* Internet Explorer 10-11 */
  color: #d3d3d3;
}

::-ms-input-placeholder { /* Microsoft Edge */
  color: #d3d3d3;
}
.btn-primary{
    border-radius: 5px;
    height: 40px;
    background: #a0c63a !important;
    /*max-width: 200px;*/
    margin: auto;
    color: #fff;
        line-height: 0px;
    border: none;
}
.copy_right{
        text-align: center;
    margin-bottom: 13px;
    font-size: 13px;
}
@media(max-width:780px){
    .imge11{
        display:none;
    }
    .card{
         width: 100%;
    }
}
    </style>
</head>

<body>

<!-- ========== MAIN CONTENT ========== -->
<main id="content" role="main" class="main">
  

    <!-- Content -->
    <div class="container py-5 py-sm-4">
        
        @php($e_commerce_logo=\App\Model\BusinessSetting::where(['type'=>'company_web_logo'])->first()->value)
        <a class="d-flex justify-content-center mb-1" href="javascript:">
            <img class="z-index-2" src="{{asset("storage/app/public/company/".$e_commerce_logo)}}" alt="Logo"
                 onerror="this.src='{{asset('assets/back-end/img/400x400/img2.jpg')}}'"
                 style="max-width: 220px;">
        </a>

        <div class="row justify-content-center">
            <div class="col-md-12 col-lg-12">
                <!-- Card -->
                <div class="card card-lg mb-5">
                    <div class="card-body">
                        <div class="row">
                            
                        <!-- Form -->
                        <div class="col-md-7 col-lg-5" >
                        <form id="form-id" action="{{route('admin.auth.login')}}" method="post" style="box-shadow: 0 6px 12px rgba(140, 152, 164, .075);    background: #fff;
    padding: 30px;">
                            @csrf

                            <div class="text-center">
                                <div class="mb-2">
                                    <h1 class="display-4">{{\App\CPU\translate('sign_in')}}</h1><br>
                                    <!--<span>({{\App\CPU\translate('Admin or Employee Login')}})</span>-->
                                </div>
                            </div>

                            <!-- Form Group -->
                            <div class="js-form-message form-group">
                                <div class="input-group input-group-merge">
                                <!--<label class="input-label" for="signinSrEmail">{{\App\CPU\translate('your_email')}}</label>-->

                                <input type="email" class="form-control form-control-lg" name="email" id="signinSrEmail"
                                       tabindex="1" placeholder="Email Address" aria-label="email@address.com"
                                       required data-msg="Please enter a valid email address.">
                                <i class="fa fa-user" aria-hidden="true" style="position: absolute;
    right: 15px;
    top: 15px;"></i>
                            </div>
                             </div>
                            <!-- End Form Group -->

                            <!-- Form Group -->
                            <div class="js-form-message form-group">
                                <!--<label class="input-label" for="signupSrPassword" tabindex="0">-->
                                <!--    <span class="d-flex justify-content-between align-items-center">-->
                                <!--      {{\App\CPU\translate('password')}}-->
                                <!--    </span>-->
                                <!--</label>-->

                                <div class="input-group input-group-merge">
                                    <input type="password" class="js-toggle-password form-control form-control-lg"
                                           name="password" id="signupSrPassword" placeholder="Password"
                                           aria-label="8+ characters required" required
                                           data-msg="Your password is invalid. Please try again."
                                           data-hs-toggle-password-options='{
                                                     "target": "#changePassTarget",
                                            "defaultClass": "tio-hidden-outlined",
                                            "showClass": "tio-visible-outlined",
                                            "classChangeTarget": "#changePassIcon"
                                            }'>
                                    <div id="changePassTarget" class="input-group-append">
                                        <a class="input-group-text" href="javascript:">
                                            <i id="changePassIcon" class="tio-visible-outlined"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <!-- End Form Group -->

                            <!-- Checkbox -->
                            <div class="form-group">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="termsCheckbox"
                                           name="remember">
                                    <label class="custom-control-label text-muted" for="termsCheckbox">
                                        {{\App\CPU\translate('remember_me')}}
                                    </label>
                                </div>
                            </div>
                            <!-- End Checkbox -->
                            {{-- recaptcha --}}
                            @php($recaptcha = \App\CPU\Helpers::get_business_settings('recaptcha'))
                            @if(isset($recaptcha) && $recaptcha['status'] == 1)
                                <div id="recaptcha_element" style="width: 100%;" data-type="image"></div>
                                <br/>
                            @else
                                <div class="row p-2">
                                    <div class="col-6 pr-0">
                                        <input type="text" class="form-control form-control-lg input-captcha" name="default_captcha_value" value=""
                                            placeholder="{{\App\CPU\translate('Enter captcha value')}}"  autocomplete="off">
                                    </div>
                                    <div class="col-6 input-icons" style="background-color: #FFFFFF; border-radius: 5px;   ">
                                        <a onclick="javascript:re_captcha();">
                                            <img src="{{ URL('/admin/auth/code/captcha/1') }}" class="input-field" id="default_recaptcha_id" style="display: inline;width: 90%; height: 75%; padding-top: 0px;">
                                            <i class="tio-refresh icon"></i>
                                        </a>
                                    </div>
                                </div>
                            @endif

                            <button type="submit" class="btn btn-lg btn-block btn-primary">{{\App\CPU\translate('sign_in')}}</button>
                        </form>
                         </div>
                        <!-- End Form -->
                        
                        <!----Right image  -->
                        <div class="col-md-5 col-lg-7" style="text-align: center;">
                 
                        </div>
                         <!----Right image End -->
                        </div>
                    </div>
                    @if(env('APP_MODE')=='demo')
                        <div class="card-footer">
                            <div class="row">
                                <div class="col-10">
                                    <span>{{\App\CPU\translate('Email')}} : {{\App\CPU\translate('admin@admin.com')}}</span><br>
                                    <span>{{\App\CPU\translate('Password')}} : {{\App\CPU\translate('12345678')}}</span>
                                </div>
                                <div class="col-2">
                                    <button class="btn btn-primary" onclick="copy_cred()"><i class="tio-copy"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endif
                    <div class="copy_right">© <script>document.write(/\d{4}/.exec(Date())[0])</script> All Rights Reserved for BigStar| Powered by <a target="_blank" href="https://biztackle.in/">Biztackle Innovations</a></div>
                </div>
                <!-- End Card -->
            </div>
        </div>
        <label class="badge badge-soft-success float-right" style="z-index: 9;position: absolute;right: 0.5rem;bottom: 0.5rem;">{{\App\CPU\translate('Software version')}} : {{ env('SOFTWARE_VERSION') }}</label>
    </div>
    <!-- End Content -->
</main>
<!-- ========== END MAIN CONTENT ========== -->


<!-- JS Implementing Plugins -->
<script src="{{asset('assets/back-end')}}/js/vendor.min.js"></script>

<!-- JS Front -->
<script src="{{asset('assets/back-end')}}/js/theme.min.js"></script>
<script src="{{asset('assets/back-end')}}/js/toastr.js"></script>
{!! Toastr::message() !!}

@if ($errors->any())
    <script>
        @foreach($errors->all() as $error)
        toastr.error('{{$error}}', Error, {
            CloseButton: true,
            ProgressBar: true
        });
        @endforeach
    </script>
@endif

<!-- JS Plugins Init. -->
<script>
    $(document).on('ready', function () {
        // INITIALIZATION OF SHOW PASSWORD
        // =======================================================
        $('.js-toggle-password').each(function () {
            new HSTogglePassword(this).init()
        });

        // INITIALIZATION OF FORM VALIDATION
        // =======================================================
        $('.js-validate').each(function () {
            $.HSCore.components.HSValidation.init($(this));
        });
    });
</script>

{{-- recaptcha scripts start --}}
@if(isset($recaptcha) && $recaptcha['status'] == 1)
    <script type="text/javascript">
        var onloadCallback = function () {
            grecaptcha.render('recaptcha_element', {
                'sitekey': '{{ \App\CPU\Helpers::get_business_settings('recaptcha')['site_key'] }}'
            });
        };
    </script>
    <script src="https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit" async defer></script>
    <script>
        $("#form-id").on('submit',function(e) {
            var response = grecaptcha.getResponse();

            if (response.length === 0) {
                e.preventDefault();
                toastr.error("{{\App\CPU\translate('Please check the recaptcha')}}");
            }
        });
    </script>
@else
    <script type="text/javascript">
        function re_captcha() {
            $url = "{{ URL('/admin/auth/code/captcha') }}";
            $url = $url + "/" + Math.random();
            document.getElementById('default_recaptcha_id').src = $url;
            console.log('url: '+ $url);
        }
    </script>
@endif
{{-- recaptcha scripts end --}}

@if(env('APP_MODE')=='demo')
    <script>
        function copy_cred() {
            $('#signinSrEmail').val('primedeals@admin.com');
            $('#signupSrPassword').val('123123123');
            toastr.success('Copied successfully!', 'Success!', {
                CloseButton: true,
                ProgressBar: true
            });
        }
    </script>
@endif

<!-- IE Support -->
<script>
    if (/MSIE \d|Trident.*rv:/.test(navigator.userAgent)) document.write('<script src="{{asset('assets/admin')}}/vendor/babel-polyfill/polyfill.min.js"><\/script>');
</script>
</body>
</html>

