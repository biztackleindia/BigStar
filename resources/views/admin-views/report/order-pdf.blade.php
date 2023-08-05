<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{\App\CPU\translate('invoice')}}</title>
    <meta http-equiv="Content-Type" content="text/html;"/>
    <meta charset="UTF-8">
    <style media="all">
        * {
            margin: 0;
            padding: 0;
            line-height: 1.3;
            font-family: sans-serif;
            color: #333542;
        }
.product-detail-table th{
    color:#fff;
}
.table .thead-light th {
    color: #677788;
    background-color: #f8fafd;
    border-color: rgba(231, 234, 243, .7);
}


        /* IE 6 */
        * html .footer {
            position: absolute;
            top: expression((0-(footer.offsetHeight)+(document.documentElement.clientHeight ? document.documentElement.clientHeight : document.body.clientHeight)+(ignoreMe = document.documentElement.scrollTop ? document.documentElement.scrollTop : document.body.scrollTop))+'px');
        }

        body {
            font-size: .875rem;
        }

        .gry-color *,
        .gry-color {
            color: #333542;
        }

        table {
            width: 100%;
        }

        table th {
            font-weight: normal;
        }

        table.padding th {
            padding: .5rem .7rem;
        }

        table.padding td {
            padding: .7rem;
        }

        table.sm-padding td {
            padding: .2rem .7rem;
        }

        .border-bottom td,
        .border-bottom th {
            border-bottom: 0px solid{{$web_config['primary_color']}};;
        }

        .col-12 {
            width: 100%;
        }

        [class*='col-'] {
            float: left;
            /*border: 1px solid #F3F3F3;*/
        }

        .row:after {
            content: ' ';
            clear: both;
            display: block;
        }

        .wrapper {
            width: 100%;
            height: auto;
            margin: 0 auto;
        }

        .header-height {
            height: 15px;
            border: 1px{{$web_config['primary_color']}};
            background: {{$web_config['primary_color']}};
        }

        .content-height {
            display: flex;
        }

        .customers {
            font-family: Arial, Helvetica, sans-serif;
            border-collapse: collapse;
            width: 100%;
        }

        table.customers {
            background-color: #FFFFFF;
        }

        table.customers > tr {
            background-color: #FFFFFF;
        }

        table.customers tr > td {
            border-top: 5px solid #FFF;
            border-bottom: 5px solid #FFF;
        }

        .header {
            border: 1px solid #ecebeb;
        }

        .customers th {
            /*border: 1px solid #A1CEFF;*/
            padding: 8px;
        }

        .customers td {
            /*border: 1px solid #F3F3F3;*/
            padding: 14px;
        }

        .customers th {
            color: white;
            padding-top: 12px;
            padding-bottom: 12px;
            text-align: left;
        }

        .bg-primary {
            /*font-weight: bold !important;*/
            font-size: 0.95rem !important;
            text-align: left;
            color: white;
            {{--background-color:  {{$web_config['primary_color']}};--}}
              background-color: {{$web_config['primary_color']}};
        }

        .bg-secondary {
            /*font-weight: bold !important;*/
            font-size: 0.95rem !important;
            text-align: left;
            color: #333542 !important;
            background-color: #E6E6E6;
        }

        .big-footer-height {
            height: 250px;
            display: block;
        }

        .table-total {
            font-family: Arial, Helvetica, sans-serif;
        }

        .table-total th, td {
            text-align: left;
            padding: 10px;
        }

        .footer-height {
            height: 75px;
        }

        .for-th {
            color: white;
        {{--border: 1px solid  {{$web_config['primary_color']}};--}}


        }

        .for-th-font-bold {
            /*font-weight: bold !important;*/
            font-size: 0.95rem !important;
            text-align: left !important;
            color: #333542 !important;
            background-color: #E6E6E6;
        }

        .for-tb {
            margin: 10px;
        }

        .for-tb td {
            /*margin: 10px;*/
            border-style: hidden;
        }


        .text-left {
            text-align: left;
        }

        .text-right {
            text-align: right;
        }

        .small {
            font-size: .85rem;
        }

        .currency {

        }

        .strong {
            font-size: 0.95rem;
        }

        .bold {
            font-weight: bold;
        }

        .for-footer {
            position: relative;
            left: 0;
            bottom: 0;
            width: 100%;
            background-color: rgb(214, 214, 214);
            height: auto;
            margin: auto;
            text-align: center;
        }

        .flex-start {
            display: flex;
            justify-content: flex-start;
        }

        .flex-end {
            display: flex;
            justify-content: flex-end;
        }

        .flex-between {
            display: flex;
            justify-content: space-between;
        }

        .inline {
            display: inline;
        }

        .content-position {
            padding: 15px 40px;
        }

        .content-position-y {
            padding: 0px 40px;
        }

        .triangle {
            width: 0;
            height: 0;

            border: 22px solid{{$web_config['primary_color']}};;

            border-top-color: transparent;
            border-bottom-color: transparent;
            border-right-color: transparent;
        }

        .triangle2 {
            width: 0;
            height: 0;
            border: 22px solid white;
            border-top-color: white;
            border-bottom-color: white;
            border-right-color: white;
            border-left-color: transparent;
        }

        .h1 {
            font-size: 2em;
            margin-block-start: 0.67em;
            margin-block-end: 0.67em;
            margin-inline-start: 0px;
            margin-inline-end: 0px;
            font-weight: bold;
        }

        .h2 {
            font-size: 1.5em;
            margin-block-start: 0.83em;
            margin-block-end: 0.83em;
            margin-inline-start: 0px;
            margin-inline-end: 0px;
            font-weight: bold;
        }

        .h4 {
            margin-block-start: 1.33em;
            margin-block-end: 1.33em;
            margin-inline-start: 0px;
            margin-inline-end: 0px;
            font-weight: bold;
        }

        .montserrat-normal-600 {
            font-family: Montserrat;
            font-style: normal;
            font-weight: 600;
            font-size: 18px;
            line-height: 6px;
            /* or 150% */


            color: #363B45;
        }

        .montserrat-bold-700 {
            font-family: Montserrat;
            font-style: normal;
            font-weight: 700;
            font-size: 18px;
            line-height: 6px;
            /* or 150% */


            color: #363B45;
        }

        .text-white {
            color: white !important;
        }

        .bs-0 {
            border-spacing: 0;
        }
    </style>
</head>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

<body>

<h1 style="color: #030303; margin-bottom: 0px; font-size: 22px;text-transform: capitalize;margin-bottom:15px;">{{\App\CPU\translate('order')}}
                                {{\App\CPU\translate('detail_report')}}</h1>
                               

<div class="table-responsive datatable-custom mt-3" >
                <table class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table"
                       style="width: 98%; text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}}">
                    <thead class="thead-light">
                    <tr>
                        <th class=""> {{\App\CPU\translate('SL')}}#</th>
                        <th class=" ">{{\App\CPU\translate('Order')}} {{\App\CPU\translate('id')}}</th>
                        <th> {{\App\CPU\translate('Order')}} {{\App\CPU\translate('Date')}} </th>
                        <th>{{\App\CPU\translate('customer_name')}}  </th>
                        <th>{{\App\CPU\translate('phone_number')}}</th>
                       
                        <th>{{\App\CPU\translate('paid')}} / {{\App\CPU\translate('Unpaid')}} </th>
                        <th>{{\App\CPU\translate('Order')}} {{\App\CPU\translate('status')}}</th>
                        <th>{{\App\CPU\translate('Total')}} {{\App\CPU\translate('amount')}}</th>
                    </tr>
                    </thead>

                    <tbody>
                       
                    @foreach($orders as $key=>$order)
                   
                        <tr class="status-{{$order['order_status']}} class-all">
                            <td class="">
                            {{$key+1}}
                            </td>
                            <td class="table-column-pl-0">
                                <a href="{{route('admin.orders.details',['id'=>$order['id']])}}">{{$order['id']}}</a>
                            </td>
                            <td>{{date('d M Y',strtotime($order['created_at']))}}</td>
                            <td >
                            @if(json_decode($order['shipping_address_data']))
                               @php($shipping_address=json_decode($order['shipping_address_data']))
                            
                              <strong>{{$shipping_address? $shipping_address->contact_person_name : \App\CPU\translate('empty')}}</strong><br>
                                 
                               
                                <strong>{{$shipping_address ? $shipping_address->city : \App\CPU\translate('empty')}}</strong><br>
                                
                                <strong>{{$shipping_address ? $shipping_address->zip  : \App\CPU\translate('empty')}}</strong><br>
                                
                                <strong>{{$shipping_address ? $shipping_address->address  : \App\CPU\translate('empty')}}</strong><br>
                               
                               
                                @endif
                            </td>
                            <td>
                            @if(json_decode($order['shipping_address_data']))
                               @php($shipping_address=json_decode($order['shipping_address_data']))
                               <strong>{{$shipping_address ? $shipping_address->phone  : \App\CPU\translate('empty')}}</strong>
                               @endif
                            </td>
                           
                            <td class="text-capitalize">
                           
                            {{\App\CPU\translate($order['payment_status'])}}
                            </td>
                            <td>
                                
                            {{\App\CPU\translate($order['order_status'])}}
                            
                            </td> 
                            <td><strong> {{\App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($order['order_amount']))}}</strong></td>
                        </tr>
                        <!-- Product Detail table -->
                        <tr class="product-detail-table" style="background-color: #000000 !important;
                        color: #fff !important;">
                            <th class=""> </th>
                            <th class=" ">{{\App\CPU\translate('item')}} {{\App\CPU\translate('no')}}</th>
                            <th> {{\App\CPU\translate('item')}} {{\App\CPU\translate('name')}} </th>
                            <th>{{\App\CPU\translate('item')}} {{\App\CPU\translate('rate')}} </th>
                            <th>{{\App\CPU\translate('quantity')}}</th>
                            <th>{{\App\CPU\translate('tax')}} </th>
                            <th>{{\App\CPU\translate('discount')}} </th>
                            <th>{{\App\CPU\translate('sub_total')}} </th>
                        </tr>
                        @php($subtotal=0)
                            @php($total=0)
                            @php($shipping=0)
                            @php($discount=0)
                            @php($tax=0)
                        @foreach($order['order_details'] as $keys=>$order_item)
                        @php($product=json_decode($order_item->product_details,true))
                    <tr style="    background: #d6d6d6;    font-size: 12px;"> 
                       <td class=""> </td>
                        <td class="table-column-pl-0">
                                    {{$order_item['id']}}
                        </td>
                       <td class="order-detail-p_name">{{$product['name']}}</td>
                       <td>{{$order_item['price']}}</td>
                       <td> {{$order_item['qty']}}</td>
                       <td>{{$order_item['tax']}} </td>
                       <td class="text-capitalize">{{$order_item['discount']}}</td>
                       <td>
                       @php($subtotal=$order_item['price']*$order_item->qty+$order_item['tax']-$order_item['discount']) 
                       {{\App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($subtotal))}}
                      </td> 
                    <tr>

                    @endforeach
                     <!-- Product Detail table end -->
                    
                    @endforeach
                    </tbody>
                </table>
            </div>

</body>
</html>
