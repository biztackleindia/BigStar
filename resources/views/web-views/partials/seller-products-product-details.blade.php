@if(isset($product))
    @php($overallRating = \App\CPU\ProductManager::get_overall_rating($product->reviews))
    <div class="flash_deal_product rtl" style="cursor: pointer; height:155px; margin-bottom:10px;"
         onclick="location.href='{{route('product',$product->slug)}}'">
         @if(auth('customer')->check())  
         @if(auth('customer')->user()->member_id !='' && auth('customer')->user()->member_approval==1 )
         @if($product->member_discount > 0 && $product->member_discount != '')
         <div class="d-flex discout-box" style="left:5px;top:5px;position: absolute">
                    <span class="for-discoutn-value p-1 pl-2 pr-2">
                @if ($product->member_discount_type == 'percent')
                    {{round($product->member_discount)}}%
                @elseif($product->member_discount_type =='flat')
                    {{\App\CPU\Helpers::currency_converter($product->member_discount)}}
                @endif {{\App\CPU\translate('off')}}
            </span>
        </div>
        @elseif($product->discount > 0)
            <div class="d-flex discout-box" style="left:5px;top:5px;position: absolute">
                    <span class="for-discoutn-value p-1 pl-2 pr-2">
                    @if ($product->discount_type == 'percent')
                            {{round($product->discount,2)}}%
                        @elseif($product->discount_type =='flat')
                            {{\App\CPU\Helpers::currency_converter($product->discount)}}
                        @endif
                        {{\App\CPU\translate('off')}}
                    </span>
            </div>
        @else
            <div class="d-flex justify-content-end for-dicount-div-null">
                <span class="for-discoutn-value-null"></span>
            </div>
        @endif
        @endif
        @else
        @if($product->discount > 0)
            <div class="d-flex discout-box" style="left:5px;top:5px;position: absolute">
                    <span class="for-discoutn-value p-1 pl-2 pr-2">
                    @if ($product->discount_type == 'percent')
                            {{round($product->discount,2)}}%
                        @elseif($product->discount_type =='flat')
                            {{\App\CPU\Helpers::currency_converter($product->discount)}}
                        @endif
                        {{\App\CPU\translate('off')}}
                    </span>
            </div>
        @else
            <div class="d-flex justify-content-end for-dicount-div-null">
                <span class="for-discoutn-value-null"></span>
            </div>
        @endif
        @endif
        <div class=" d-flex" style="">
            <div class=" d-flex align-items-center justify-content-center"
                 style="padding-{{Session::get('direction') === "rtl" ?'right:14px':'left:14px'}};padding-top:14px;">
                <div class="flash-deals-background-image" style="background: {{$web_config['primary_color']}}10">
                    <img style="height: 125px!important;width:125px!important;border-radius:5px;"
                     src="{{\App\CPU\ProductManager::product_image_path('thumbnail')}}/{{$product['thumbnail']}}"
                     onerror="this.src='{{asset('assets/front-end/img/image-place-holder.png')}}'"/>
                </div>
            </div>
            <div class=" flash_deal_product_details pl-3 pr-3 pr-1 d-flex align-items-center">
                <div>
                    <div>
                        <span class="flash-product-title">
                            {{$product['name']}}
                        </span>
                    </div>
                    <div class="flash-product-review">
                        @for($inc=0;$inc<5;$inc++)
                            @if($inc<$overallRating[0])
                                <i class="sr-star czi-star-filled active"></i>
                            @else
                                <i class="sr-star czi-star" style="color:#fea569 !important"></i>
                            @endif
                        @endfor
                        <label class="badge-style2">
                            ( {{$product->reviews->count()}} )
                        </label>
                    </div>
                    <div>
                        @if($product->discount > 0)
                            <strike
                                style="font-size: 12px!important;color: #E96A6A!important;">
                                {{\App\CPU\Helpers::currency_converter($product->unit_price)}}
                            </strike>
                        @endif
                    </div>
                    <div class="flash-product-price">
                        {{\App\CPU\Helpers::currency_converter($product->unit_price-\App\CPU\Helpers::get_product_discount($product,$product->unit_price))}}
                        
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
@endif
