<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <title>
        @yield('title')
    </title>
    <!-- SEO Meta Tags-->
    <meta name="description" content="">
    <meta name="keywords" content="">
    <meta name="author" content="">
    <!-- Viewport-->
    <meta name="_token" content="{{csrf_token()}}">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Favicon and Touch Icons-->
    <link rel="apple-touch-icon" sizes="180x180" href="">
    <link rel="icon" type="image/png" sizes="32x32" href="">
    <link rel="icon" type="image/png" sizes="16x16" href="">

    <link rel="stylesheet" href="{{asset('assets/back-end')}}/css/toastr.css"/>
    <!-- Main Theme Styles + Bootstrap-->
    <link rel="stylesheet" media="screen" href="{{asset('assets/front-end')}}/css/theme.min.css">
    <link rel="stylesheet" media="screen" href="{{asset('assets/front-end')}}/css/slick.css">
    <link rel="stylesheet" href="{{asset('assets/back-end')}}/css/toastr.css"/>
    @stack('css_or_js')

    {{--stripe--}}
    <script src="https://polyfill.io/v3/polyfill.min.js?version=3.52.1&features=fetch"></script>
    <script src="https://js.stripe.com/v3/"></script>
    {{--stripe--}}
</head>
<!-- Body-->
<body class="toolbar-enabled">

{{--loader--}}
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div id="loading" style="display: none;">
                <div style="position: fixed;z-index: 9999; left: 40%;top: 37% ;width: 100%">
                    <img width="200"
                         src="{{asset('storage/app/public/company')}}/{{\App\CPU\Helpers::get_business_settings('loader_gif')}}"
                         onerror="this.src='{{asset('assets/front-end/img/loader.gif')}}'">
                </div>
            </div>
        </div>
    </div>
</div>
{{--loader--}}

<!-- Page Content-->
<div class="checkout_details container pb-5 mb-2 mb-md-4">
    <div class="row mt-5">
        @php($user=\App\CPU\Helpers::get_customer())
        @php($config=\App\CPU\Helpers::get_business_settings('paypal'))
        @if($config['status'])
            <div class="col-md-6 mb-4" style="cursor: pointer">
                <div class="card">
                    <div class="card-body" style="height: 100px">
                        <form class="needs-validation" method="POST" id="payment-form"
                              action="{{route('pay-paypal')}}">
                            {{ csrf_field() }}
                            <button class="btn btn-block click-if-alone" type="submit">
                                <img width="150"
                                     src="{{asset('assets/front-end/img/paypal.png')}}"/>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @endif

        @php($coupon_discount = session()->has('coupon_discount') ? session('coupon_discount') : 0)
        @php($amount = \App\CPU\CartManager::cart_grand_total() - $coupon_discount)

        @php($config=\App\CPU\Helpers::get_business_settings('stripe'))
        @if($config['status'])
            <div class="col-md-6 mb-4" style="cursor: pointer">
                <div class="card">
                    <div class="card-body" style="height: 100px">
                        <button class="btn btn-block click-if-alone" type="button" id="checkout-button">
                            <i class="czi-card"></i> {{\App\CPU\translate('Credit / Debit card ( Stripe )')}}
                        </button>
                        <script type="text/javascript">
                            // Create an instance of the Stripe object with your publishable API key
                            var stripe = Stripe('{{$config['published_key']}}');
                            var checkoutButton = document.getElementById("checkout-button");
                            checkoutButton.addEventListener("click", function () {
                                fetch("{{route('pay-stripe')}}", {
                                    method: "GET",
                                }).then(function (response) {
                                    console.log(response)
                                    return response.text();
                                }).then(function (session) {
                                    /*console.log(JSON.parse(session).id)*/
                                    return stripe.redirectToCheckout({sessionId: JSON.parse(session).id});
                                }).then(function (result) {
                                    if (result.error) {
                                        alert(result.error.message);
                                    }
                                }).catch(function (error) {
                                    console.error("{{\App\CPU\translate('Error')}}:", error);
                                });
                            });
                        </script>
                    </div>
                </div>
            </div>
        @endif

        @php($config=\App\CPU\Helpers::get_business_settings('razor_pay'))
        @php($inr=\App\Model\Currency::where(['symbol'=>'₹'])->first())
        @php($usd=\App\Model\Currency::where(['code'=>'usd'])->first())
        @if(isset($inr) && isset($usd) && $config['status'])
            <div class="col-md-6 mb-4" style="cursor: pointer">
                <div class="card">
                    <div class="card-body" style="height: 100px">
                        <form action="{!!route('payment-razor')!!}" method="POST">
                        @csrf
                        <!-- Note that the amount is in paise = 50 INR -->
                            <!--amount need to be in paisa-->
                            <script src="https://checkout.razorpay.com/v1/checkout.js"
                                    data-key="{{ \Illuminate\Support\Facades\Config::get('razor.razor_key') }}"
                                    data-amount="{{(round(\App\CPU\Convert::usdToinr($amount)))*100}}"
                                    data-buttontext="Pay {{(\App\CPU\Convert::usdToinr($amount))*100}} INR"
                                    data-name="{{\App\Model\BusinessSetting::where(['type'=>'company_name'])->first()->value}}"
                                    data-description=""
                                    data-image="{{asset('storage/app/public/company/'.\App\Model\BusinessSetting::where(['type'=>'company_web_logo'])->first()->value)}}"
                                    data-prefill.name="{{$user->f_name}}"
                                    data-prefill.email="{{$user->email}}"
                                    data-theme.color="#ff7529">
                            </script>
                        </form>
                        <button class="btn btn-block click-if-alone" type="button"
                                onclick="$('.razorpay-payment-button').click()">
                            <img width="150"
                                 src="{{asset('assets/front-end/img/razor.png')}}"/>
                        </button>
                    </div>
                </div>
            </div>
        @endif

        @php($config=\App\CPU\Helpers::get_business_settings('paytm'))
        @if(isset($config) && $config['status'])
            <div class="col-md-6 mb-4" style="cursor: pointer">
                <div class="card">
                    <div class="card-body" style="height: 100px">
                        <a class="btn btn-block click-if-alone" href="{{route('paytm-payment')}}">
                            <img style="max-width: 150px; margin-top: -10px"
                                 src="{{asset('assets/front-end/img/paytm.png')}}"/>
                        </a>
                    </div>
                </div>
            </div>
        @endif

    </div>
</div>

<script src="{{asset('assets/front-end')}}/vendor/jquery/dist/jquery-2.2.4.min.js"></script>
<script src="{{asset('assets/front-end')}}/vendor/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
{{--Toastr--}}
<script src={{asset("assets/back-end/js/toastr.js")}}></script>
<script src="{{asset('assets/front-end')}}/js/sweet_alert.js"></script>

@php($mode = \App\CPU\Helpers::get_business_settings('bkash')['environment']??'sandbox')
@if($mode=='live')
    <script id="myScript"
            src="https://scripts.pay.bka.sh/versions/1.2.0-beta/checkout/bKash-checkout.js"></script>
@else
    <script id="myScript"
            src="https://scripts.sandbox.bka.sh/versions/1.2.0-beta/checkout/bKash-checkout-sandbox.js"></script>
@endif

<script>
    setInterval(function () {
        $('.stripe-button-el').hide()
    }, 10)

    setTimeout(function () {
        $('.stripe-button-el').hide();
        $('.razorpay-payment-button').hide();
    }, 10)
</script>

<script type="text/javascript">
    function BkashPayment() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            }
        });
        $('#loading').show();
        // get token
        $.ajax({
            url: "{{ route('bkash-get-token') }}",
            type: 'POST',
            contentType: 'application/json',
            success: function (data) {
                $('#loading').hide();
                $('pay-with-bkash-button').trigger('click');
                if (data.hasOwnProperty('msg')) {
                    showErrorMessage(data) // unknown error
                }
            },
            error: function (err) {
                $('#loading').hide();
                showErrorMessage(err);
            }
        });
    }

    let paymentID = '';
    bKash.init({
        paymentMode: 'checkout',
        paymentRequest: {},
        createRequest: function (request) {
            setTimeout(function () {
                createPayment(request);
            }, 2000)
        },
        executeRequestOnAuthorization: function (request) {
            $.ajax({
                url: '{{ route('bkash-execute-payment') }}',
                type: 'POST',
                contentType: 'application/json',
                data: JSON.stringify({
                    "paymentID": paymentID
                }),
                success: function (data) {
                    if (data) {
                        if (data.paymentID != null) {
                            BkashSuccess(data);
                        } else {
                            showErrorMessage(data);
                            bKash.execute().onError();
                        }
                    } else {
                        $.get('{{ route('bkash-query-payment') }}', {
                            payment_info: {
                                payment_id: paymentID
                            }
                        }, function (data) {
                            if (data.transactionStatus === 'Completed') {
                                BkashSuccess(data);
                            } else {
                                createPayment(request);
                            }
                        });
                    }
                },
                error: function (err) {
                    bKash.execute().onError();
                }
            });
        },
        onClose: function () {
            // for error handle after close bKash Popup
        }
    });

    function createPayment(request) {
        // because of createRequest function finds amount from this request
        request['amount'] = "{{round(\App\CPU\Convert::usdTobdt($amount),2)}}"; // max two decimal points allowed
        $.ajax({
            url: '{{ route('bkash-create-payment') }}',
            data: JSON.stringify(request),
            type: 'POST',
            contentType: 'application/json',
            success: function (data) {
                $('#loading').hide();
                if (data && data.paymentID != null) {
                    paymentID = data.paymentID;
                    bKash.create().onSuccess(data);
                } else {
                    bKash.create().onError();
                }
            },
            error: function (err) {
                $('#loading').hide();
                showErrorMessage(err.responseJSON);
                bKash.create().onError();
            }
        });
    }

    function BkashSuccess(data) {
        $.post('{{ route('bkash-success') }}', {
            payment_info: data
        }, function (res) {
            @if(session()->has('payment_mode') && session('payment_mode') == 'app')
                location.href = '{{ route('payment-success')}}';
            @else
                location.href = '{{route('order-placed')}}';
            @endif
        });
    }

    function showErrorMessage(response) {
        let message = 'Unknown Error';
        if (response.hasOwnProperty('errorMessage')) {
            let errorCode = parseInt(response.errorCode);
            let bkashErrorCode = [2001, 2002, 2003, 2004, 2005, 2006, 2007, 2008, 2009, 2010, 2011, 2012, 2013, 2014,
                2015, 2016, 2017, 2018, 2019, 2020, 2021, 2022, 2023, 2024, 2025, 2026, 2027, 2028, 2029, 2030,
                2031, 2032, 2033, 2034, 2035, 2036, 2037, 2038, 2039, 2040, 2041, 2042, 2043, 2044, 2045, 2046,
                2047, 2048, 2049, 2050, 2051, 2052, 2053, 2054, 2055, 2056, 2057, 2058, 2059, 2060, 2061, 2062,
                2063, 2064, 2065, 2066, 2067, 2068, 2069, 503,
            ];
            if (bkashErrorCode.includes(errorCode)) {
                message = response.errorMessage
            }
        }
        Swal.fire("Payment Failed!", message, "error");
    }
</script>

<script>
    function click_if_alone() {
        let total = $('.checkout_details .click-if-alone').length;
        if (Number.parseInt(total) < 2) {
            $('.click-if-alone').click()
            $('.checkout_details').html('<h1>{{\App\CPU\translate('Redirecting_to_the_payment_page')}}......</h1>');
        }
    }
    click_if_alone();
</script>

</body>
</html>
