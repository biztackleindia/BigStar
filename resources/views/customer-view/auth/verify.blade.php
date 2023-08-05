@extends('layouts.front-end.app')

@section('title', \App\CPU\translate('Verify'))

@push('css_or_js')
    <style>
        #sendBtn{
            
    right: 25px;
    position: absolute;
    bottom: 20px;

        }
        @media(max-width:500px){
            #sign_in{
                margin-top: -23% !important;
            }

        }
    </style>
@endpush

@section('content')
    <div class="container py-4 py-lg-5 my-4">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card border-0 box-shadow">
                    <div class="card-body">
                        <div class="text-center">
                            <h2 class="h4 mb-1">{{\App\CPU\translate('one_step_ahead')}}</h2>
                            <p class="font-size-sm text-muted mb-4">{{\App\CPU\translate('verify_information_to_continue')}}.</p>
                        </div>
                        <form class="needs-validation_" id="sign-up-form" action="{{ route('customer.auth.verify') }}"
                              method="post">
                            @csrf
                            <div class="col-sm-12">
                                @php($email_verify_status = \App\CPU\Helpers::get_business_settings('email_verification'))
                                @php($phone_verify_status = \App\CPU\Helpers::get_business_settings('phone_verification'))
                                <div class="form-group">
                                    @if(\App\CPU\Helpers::get_business_settings('email_verification'))
                                        <label for="reg-phone" class="text-primary">
                                            *
                                            {{\App\CPU\translate('please') }}
                                            {{\App\CPU\translate('provide') }}
                                            {{\App\CPU\translate('verification') }}
                                            {{\App\CPU\translate('token') }}
                                            {{\App\CPU\translate('sent_in_your_email') }}
                                        </label>
                                    @elseif(\App\CPU\Helpers::get_business_settings('phone_verification'))
                                        <label for="reg-phone" class="text-primary">
                                            *
                                            {{\App\CPU\translate('please') }}
                                            {{\App\CPU\translate('provide') }}
                                            {{\App\CPU\translate('OTP') }}
                                            {{\App\CPU\translate('sent_in_your_phone') }}
                                        </label>
                                    @else
                                        <label for="reg-phone" class="text-primary">* {{\App\CPU\translate('verification_code') }} / {{ \App\CPU\translate('OTP')}}</label>
                                    @endif
                                    <input class="form-control" type="text" name="token" required>
                                </div>
                            </div>
                            <input type="hidden" id="user_id" value="{{$user->id}}" name="id">
                            <button type="submit" class="btn btn-outline-primary">{{\App\CPU\translate('verify')}}</button>
                        </form>
                        <button type="button" id="sendBtn" class="btn btn-outline-primary">{{\App\CPU\translate('resend')}}</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

{{--     {{route('customer.auth.verify', $user->id)}}       --}}

@push('script')
<script>
     $('#sendBtn').on('click', function (e) {
        console.log("click");
            e.preventDefault();
            let user_id = $('#user_id').val();
            console.log(user_id);
            let data = {
                user_id: user_id,
               
            }
            
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                    }
                });

                $.ajax({
                    type: "post",
                    url: '{{route('customer.auth.resend_otp')}}',
                    data: data,
                    success: function (respons) {
                        toastr.success('{{\App\CPU\translate('Please check your SMS for OTP')}}', {
                                CloseButton: true,
                                ProgressBar: true
                            });
                    }
                });
               
        });
</script>
@endpush
