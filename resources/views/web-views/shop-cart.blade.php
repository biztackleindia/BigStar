@extends('layouts.front-end.app')

@section('title',\App\CPU\translate('My Shopping Cart'))

@push('css_or_js')
<meta property="og:image" content="{{asset('storage/app/public/company')}}/{{$web_config['web_logo']->value}}" />
<meta property="og:title" content="{{$web_config['name']->value}} " />
<meta property="og:url" content="{{env('APP_URL')}}">
<meta property="og:description" content="{!! substr($web_config['about']->value,0,100) !!}">

<meta property="twitter:card" content="{{asset('storage/app/public/company')}}/{{$web_config['web_logo']->value}}" />
<meta property="twitter:title" content="{{$web_config['name']->value}}" />
<meta property="twitter:url" content="{{env('APP_URL')}}">
<meta property="twitter:description" content="{!! substr($web_config['about']->value,0,100) !!}">
<link rel="stylesheet" href="{{asset('public/assets/front-end')}}/css/shop-cart.css" />
<style>
    .cart-img{
        height: 62px;
    }
   
    .unit_weight{
        font-size: 11px;
    }
    @media(max-width:600px){
        .cart-img{
            min-width: 30px;
    height: auto;
    }
}
</style>
@endpush

@section('content')
<div class="container pb-5 mb-2 mt-3 rtl"
    style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};" id="cart-summary">
    @include('layouts.front-end.partials.cart_details')
</div>
@endsection

@push('script')
<script>
cartPageQuantityInitialize();
function updateCartPageQuantity(quantity,cart_id) {
       
       $.post('{{route('cart.updateQuantity')}}', {
           _token: '{{csrf_token()}}',
           key: cart_id,
           quantity: quantity
       }, function (response) {
           if (response.status == 0) {
               toastr.error(response.message, {
                   CloseButton: true,
                   ProgressBar: true
               });
               $("#input" + cart_id).val(response['qty']);
           } else {
               updateNavCart();
               location.reload();
           }
       });
   }
function cartPageQuantityInitialize() {
        $('.btn-plus').click(function (e) {
            e.preventDefault();
            console.log("clicked");
            fieldName = $(this).attr('data-field');
            type = $(this).attr('data-type');
            var input = $("input[name='" + fieldName + "']");
           
            var cart_id=$(this).attr('id').slice(5);
            var currentVal = parseInt($('#input'+cart_id).val());
            console.log(currentVal);
            if (!isNaN(currentVal)) {
                if (currentVal < $('#input'+cart_id).attr('max')) {
                        $('#input'+cart_id).val(currentVal + 1).change();
                        updateCartPageQuantity($('#input'+cart_id).val(),cart_id);
                    }
                    if (parseInt($('#input'+cart_id).val()) == $('#input'+cart_id).attr('max')) {
                        $(this).attr('disabled', true);
                    }
            } else {
                $('#input'+cart_id).val(0);
            }
        });
        $('.btn-minus').click(function (e) {
            e.preventDefault();
           
            fieldName = $(this).attr('data-field');
            type = $(this).attr('data-type');
            var input = $("input[name='" + fieldName + "']");
           
            var cart_id=$(this).attr('id').slice(5);
            var currentVal = parseInt($('#input'+cart_id).val());
            
            if (!isNaN(currentVal)) {
                if (currentVal > $('#input'+cart_id).attr('min')) {
                        $('#input'+cart_id).val(currentVal - 1).change();
                        updateCartPageQuantity($('#input'+cart_id).val(),cart_id);

                    }
                    if (parseInt($('#input'+cart_id).val()) == $('#input'+cart_id).attr('min')) {
                        $(this).attr('disabled', true);
                    }
            } else {
                $('#input'+cart_id).val(0);
            }
        });
        

        $('.input-number').change(function () {
           
            minValue = parseInt($(this).attr('min'));
            maxValue = parseInt($(this).attr('max'));
            var cart_id=$(this).attr('id').slice(5);
            
            valueCurrent = parseInt($(this).val());
            
            var name = $(this).attr('name');
            if (valueCurrent >= minValue) {
                updateCartPageQuantity(valueCurrent,cart_id);
            }
            else if(valueCurrent >= maxValue){
                updateCartPageQuantity(valueCurrent,cart_id);
            }
             else {
                if(minValue<1){
                    
                Swal.fire({
                    icon: 'error',
                    title: 'Cart',
                    text: '{{\App\CPU\translate('Sorry, the minimum value was reached')}}'
                });
                location.reload();
               }
               
              
            }
            


        });
       
       
    }
</script>
@endpush