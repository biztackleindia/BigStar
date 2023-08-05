@extends('layouts.back-end.app')

@section('title', \App\CPU\translate('Earning Report'))

@push('css_or_js')

@endpush
<style>
    .product-detail-table{
    background-color: #000000 !important;
    color: #fff !important;
}
.product-detail-table th{
    padding: 5px !important;
    font-size: 13px;
}
.order-detail-p_name {
    min-width: 240px;
    width: 100%;
    padding: 5px !important;
    white-space: break-spaces !important;
}
.pdf-btn{
    background: #0a7f4d;
    border: #0a7f4d;
    color: #fff !important;
    padding: 8px 25px;
    border-radius: 5px
}
.status_change_btn{
    padding: 8px 25px !important;
    border-radius: 5px !important;
    background-color: #041562 !important;
    margin-left: 10px;
    color: #fff !important;
    min-width: 125px;
}
</style>
@section('content')
<div class="content container-fluid" id="printableArea">
    <!-- Page Header -->
    <div class="page-header">
        <div class="media mb-3">
            <div class="media-body">
                <div class="row">
                    <div class="row col-lg mb-3 mb-lg-0 {{Session::get('direction') === "rtl" ? 'mr-2' : 'ml-2'}}"
                        style="display: block; text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">
                        <div>
                            <h1 class="page-header-title">{{\App\CPU\translate('order')}}
                                {{\App\CPU\translate('detail_report')}} </h1>
                            <h5 class="text-muted">( {{session('from_date')}} - {{session('to_date')}} )</h5>
                        </div>
                    </div>

                    <div class="col-lg-auto">
                        <div class="d-flex">
                            <a class="btn btn-icon btn-primary rounded-circle" href="{{route('admin.dashboard')}}">
                                <i class="tio-home-outlined"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Media -->
    </div>
    <!-- End Page Header -->

    <div class="row ">
        <div class="col-lg-12">
            <form action="{{route('admin.report.set-date')}}" method="post">
                @csrf
                <div class="row">
                    <div class="col-7">
                        <div class="mb-3">
                            <label for="exampleInputEmail1"
                                class="form-label">{{\App\CPU\translate('show_data_by_date_range')}}</label>
                        </div>
                    </div>
                    <div class="col-lg-5" style="text-align:right" >
                        <a class="" href="{{ route('admin.report.delivery_format_view')}}">Show Delivery Format</a>

                    </div>
                    <div class="col-3">
                        <div class="mb-3">
                            <input type="date" name="from" value="{{date('Y-m-d',strtotime($from))}}" id="from_date"
                                class="form-control" required>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="mb-3">
                            <input type="date" value="{{date('Y-m-d',strtotime($to))}}" name="to" id="to_date"
                                class="form-control" required>
                        </div>
                    </div>
                    <div class="col-3">
                    <div class="mb-3">
                    <select style="width: 100%" id="order_type_data" class="js-example-responsive form-control"
                            name="order_type" required >
                            
                                <option value="customer" {{Session::get('order_type')=='customer'?'selected':''}} >Customer</option>
                                <option value="member" {{Session::get('order_type')=='member'?'selected':''}} >Member</option>
                                               
                                            </select>
                    </div>
                    </div>
                    <div class="col-3">
                        <div class="mb-3">
                            <button type="submit"
                                class="btn btn-primary btn-block">{{\App\CPU\translate('Show')}}</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="col-lg-8" >
        <div style=" display: flex;">
            <input class="all_check_order" type="checkbox" name="all" value="all"><span style="margin: auto 0px;">&nbsp; Select All</span>
            <div class="dropdown" style="margin-left: 10px;">
                   <select name="order_status" onchange="order_status(this.value)" class="status form-control">
                   <option value="" style="    color: darkgrey;">------- Select -------</option>
                    <option value="pending"  > {{\App\CPU\translate('Pending')}}</option>
                    <option value="confirmed"  > {{\App\CPU\translate('Confirmed')}}</option>
                     <option value="processing"  >{{\App\CPU\translate('Processing')}} </option>
                    <option class="text-capitalize" value="out_for_delivery"  >{{\App\CPU\translate('out_for_delivery')}} </option>
                    <option  value="delivered"  >{{\App\CPU\translate('Delivered')}} </option>
                    <option  value="returned" > {{\App\CPU\translate('Returned')}}</option>
                    <option  value="failed"  >{{\App\CPU\translate('Failed')}} </option>
                    <option value="canceled"  >{{\App\CPU\translate('Canceled')}} </option>
                    </select>
                </div>

                <button type="button" class="btn status_change_btn">Update</button>
            </div>
           
        </div>
        
        <div class="col-lg-4" style="text-align:right" >
            <a class="pdf-btn" href="{{ route('admin.report.pdfview',['download'=>'pdf']) }}">Download PDF</a>

        </div>
            
       

   <!-- Table -->
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
                            <input class="check_order" type="checkbox" name="order_ids" value="{{$order['id']}}">
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
                            <td> <strong>{{\App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($order['order_amount']))}}</strong></td>
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
            <!-- End Table -->


    </div>
    <!-- End Stats -->


</div>
<!-- End Card -->
 <!-- Footer -->

 <div class="card-footer">
                <!-- Pagination -->
                <div class="row justify-content-center justify-content-sm-between align-items-sm-center">
                    <div class="col-sm-auto">
                        <div class="d-flex justify-content-center justify-content-sm-end">
                            <!-- Pagination -->
                            {!! $orders->links() !!}
                        </div>
                    </div>
                </div>
                <!-- End Pagination -->
            </div>
            <!-- End Footer -->
<!-- End Row -->

@endsection

@push('script')

@endpush

@push('script_2')

<script src="{{asset('public/assets/back-end')}}/vendor/chart.js/dist/Chart.min.js"></script>
<script src="{{asset('public/assets/back-end')}}/vendor/chartjs-chart-matrix/dist/chartjs-chart-matrix.min.js"></script>
<script src="{{asset('public/assets/back-end')}}/js/hs.chartjs-matrix.js"></script>

<script>
$(document).on('ready', function() {
  
    
    
    // INITIALIZATION OF FLATPICKR
    // =======================================================
    $('.js-flatpickr').each(function() {
        $.HSCore.components.HSFlatpickr.init($(this));
    });


    // INITIALIZATION OF NAV SCROLLER
    // =======================================================
    $('.js-nav-scroller').each(function() {
        new HsNavScroller($(this)).init()
    });


    // INITIALIZATION OF DATERANGEPICKER
    // =======================================================
    $('.js-daterangepicker').daterangepicker();

    $('.js-daterangepicker-times').daterangepicker({
        timePicker: true,
        startDate: moment().startOf('hour'),
        endDate: moment().startOf('hour').add(32, 'hour'),
        locale: {
            format: 'M/DD hh:mm A'
        }
    });

    var start = moment();
    var end = moment();

    function cb(start, end) {
        $('#js-daterangepicker-predefined .js-daterangepicker-predefined-preview').html(start.format('MMM D') +
            ' - ' + end.format('MMM D, YYYY'));
    }

    $('#js-daterangepicker-predefined').daterangepicker({
        startDate: start,
        endDate: end,
        ranges: {
            'Today': [moment(), moment()],
            'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Last 7 Days': [moment().subtract(6, 'days'), moment()],
            'Last 30 Days': [moment().subtract(29, 'days'), moment()],
            'This Month': [moment().startOf('month'), moment().endOf('month')],
            'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month')
                .endOf('month')
            ]
        }
    }, cb);

    cb(start, end);


    // INITIALIZATION OF CHARTJS
    // =======================================================
    $('.js-chart').each(function() {
        $.HSCore.components.HSChartJS.init($(this));
    });

    var updatingChart = $.HSCore.components.HSChartJS.init($('#updatingData'));

    // Call when tab is clicked
    $('[data-toggle="chart"]').click(function(e) {
        let keyDataset = $(e.currentTarget).attr('data-datasets')

        // Update datasets for chart
        updatingChart.data.datasets.forEach(function(dataset, key) {
            dataset.data = updatingChartDatasets[keyDataset][key];
        });
        updatingChart.update();
    })


    // INITIALIZATION OF MATRIX CHARTJS WITH CHARTJS MATRIX PLUGIN
    // =======================================================
    function generateHoursData() {
        var data = [];
        var dt = moment().subtract(365, 'days').startOf('day');
        var end = moment().startOf('day');
        while (dt <= end) {
            data.push({
                x: dt.format('YYYY-MM-DD'),
                y: dt.format('e'),
                d: dt.format('YYYY-MM-DD'),
                v: Math.random() * 24
            });
            dt = dt.add(1, 'day');
        }
        return data;
    }
 
    $.HSCore.components.HSChartMatrixJS.init($('.js-chart-matrix'), {
        data: {
            datasets: [{
                label: 'Commits',
                data: generateHoursData(),
                width: function(ctx) {
                    var a = ctx.chart.chartArea;
                    return (a.right - a.left) / 70;
                },
                height: function(ctx) {
                    var a = ctx.chart.chartArea;
                    return (a.bottom - a.top) / 10;
                }
            }]
        },
        options: {
            tooltips: {
                callbacks: {
                    title: function() {
                        return '';
                    },
                    label: function(item, data) {
                        var v = data.datasets[item.datasetIndex].data[item.index];

                        if (v.v.toFixed() > 0) {
                            return '<span class="font-weight-bold">' + v.v.toFixed() +
                                ' hours</span> on ' + v.d;
                        } else {
                            return '<span class="font-weight-bold">No time</span> on ' + v.d;
                        }
                    }
                }
            },
            scales: {
                xAxes: [{
                    position: 'bottom',
                    type: 'time',
                    offset: true,
                    time: {
                        unit: 'week',
                        round: 'week',
                        displayFormats: {
                            week: 'MMM'
                        }
                    },
                    ticks: {
                        "labelOffset": 20,
                        "maxRotation": 0,
                        "minRotation": 0,
                        "fontSize": 12,
                        "fontColor": "rgba(22, 52, 90, 0.5)",
                        "maxTicksLimit": 12,
                    },
                    gridLines: {
                        display: false
                    }
                }],
                yAxes: [{
                    type: 'time',
                    offset: true,
                    time: {
                        unit: 'day',
                        parser: 'e',
                        displayFormats: {
                            day: 'ddd'
                        }
                    },
                    ticks: {
                        "fontSize": 12,
                        "fontColor": "rgba(22, 52, 90, 0.5)",
                        "maxTicksLimit": 2,
                    },
                    gridLines: {
                        display: false
                    }
                }]
            }
        }
    });
    

    // INITIALIZATION OF CLIPBOARD
    // =======================================================
    $('.js-clipboard').each(function() {
        var clipboard = $.HSCore.components.HSClipboard.init(this);
    });


    // INITIALIZATION OF CIRCLES
    // =======================================================
    $('.js-circle').each(function() {
        var circle = $.HSCore.components.HSCircles.init($(this));
    });
});
</script>

<script>
 
$('#from_date,#to_date').change(function() {
    let fr = $('#from_date').val();
    let to = $('#to_date').val();
    if (fr != '' && to != '') {
        if (fr > to) {
            $('#from_date').val('');
            $('#to_date').val('');
            toastr.error('{{\App\CPU\translate('Invalid date range')}}!', Error, {
                    CloseButton: true,
                    ProgressBar: true
                });
        }
    }

})
function printPageArea(areaID){
    var printContent = document.getElementById(areaID).innerHTML;
    var originalContent = document.body.innerHTML;
    document.body.innerHTML = printContent;
    window.print();
    document.body.innerHTML = originalContent;
}
var order_ids = [];
var changed_status = '';
$("input[name=order_ids]:checkbox").click(function() {
    console.log($(this).val());
    var id = $(this).val();
    if($(this).prop("checked")==true){
        order_ids.push(id);
    }
    else{
       // Removing the specified element by value from the array
    for (let i = 0; i < order_ids.length; i++) {
        if (order_ids[i] == id) {
            order_ids.splice(i, 1);
        }
    }
    }
    
// console.log(order_ids);
})
$('.all_check_order').click(function(){
    order_ids = [];

    if($(this).prop("checked")==true){
        $("input[name=order_ids]:checkbox").prop('checked', true);
        
        var item_list= $("input[name=order_ids]:checkbox")
        for (let i = 0; i < item_list.length; i++) {
            order_ids.push(item_list[i].defaultValue);
    }
    }
    else{
        $("input[name=order_ids]:checkbox").prop('checked', false);
    }
    console.log(order_ids);
})
function order_status(status){
    changed_status =status;
}
        $('.status_change_btn').click(function(){
            console.log(order_ids);
            console.log(changed_status);
            if(changed_status){
            $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                        }
                    });
            $.ajax({
                        url: "{{route('admin.orders.bulkstatus')}}",
                        method: 'POST',
                        data: {
                            "order_ids": order_ids,
                            "order_status": changed_status
                        },
                        success: function (data) {
                            
                                toastr.success('{{\App\CPU\translate('Status Change successfully')}}!');
                                location.reload();
                          

                        }
                    });
                }
                else{
                    toastr.info('{{\App\CPU\translate('Select Order status')}}!');
                }
                })
$('#order_type_data').change(function() {
        console.log($('#order_type_data').val());
        $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                    }
                });

                $.ajax({
                    type: "post",
                    url: '{{route('admin.order_type')}}',
                   
                    data: {
                        order_type : $('#order_type_data').val(),
                       },
                    success: function (respons) {
                        
                    }
                });
    });
</script>
@endpush