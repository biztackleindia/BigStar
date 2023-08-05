@extends('layouts.back-end.app')

@section('title', \App\CPU\translate('Earning Report'))

@push('css_or_js')

@endpush
<style>
 
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
.class-all td{
    font-size: 13px; 
}
.pdf-btn{
    background: #0a7f4d;
    border: #0a7f4d;
    color: #fff !important;
    padding: 8px 25px;
    border-radius: 5px
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
                            <h1 class="page-header-title">{{\App\CPU\translate('Delivery Format')}}
                                </h1>
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
                    <div class="col-12">
                        <div class="mb-3">
                            <label for="exampleInputEmail1"
                                class="form-label">{{\App\CPU\translate('show_data_by_date_range')}}</label>
                        </div>
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
        <div class="col-lg-4">
        {{\App\CPU\translate('Pickup Location Name')}}
        @php($pickup=\App\CPU\Helpers::PickupLocations())
        @php($pickup_location_id=\App\CPU\Helpers::get_business_settings('pickup_location_id'))
        <select style="width: 100%;text-transform: capitalize;" id="pickup_location" class="js-example-responsive form-control"
         name="pickup_location" required  onchange="pickup_update(this.value)">
            @foreach($pickup as $key=>$location)
                <option value="{{$location->id}}" {{$pickup_location_id == $location->id?'selected':''}} >{{$location->pickup_name}}</option>
            @endforeach
         </select>
        
        </div>
        
        <div class="col-lg-8 mt-5" style="text-align:right" >
            <a class="pdf-btn" href="{{ route('admin.report.export_excel',['download'=>'excel']) }}">Download Excel</a>

        </div>
            
       

   <!-- Table -->
   <div class="table-responsive datatable-custom mt-3" >
                <table class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table "
                       style="width: 98%; text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}}">
                    <thead class="thead-light">
                    <tr>
                        <th class=""> {{\App\CPU\translate('SL')}}#</th>
                        <th class=" ">{{\App\CPU\translate('sale')}} {{\App\CPU\translate('Order')}} {{\App\CPU\translate('number')}}</th>
                        <th class=" ">{{\App\CPU\translate('transport_mode')}}</th>
                        <th>{{\App\CPU\translate('Payment Mode')}}  </th>
                        <th>COD {{\App\CPU\translate(' Amount')}}  </th>
                        <th>{{\App\CPU\translate('customer_name')}}  </th>
                        <th>{{\App\CPU\translate('phone_number')}}</th>
                        <th>{{\App\CPU\translate('Shipping Address Line1')}}</th>
                        <th>{{\App\CPU\translate('Shipping City')}}</th>
                        <th>{{\App\CPU\translate('Shipping State')}}</th>
                        <th>{{\App\CPU\translate('Shipping Pincode')}}</th>
                        
                        <th>{{\App\CPU\translate('Item Sku Code')}}</th>
                        <th>{{\App\CPU\translate('Item Sku Name')}} </th>
                        <th>{{\App\CPU\translate('Quantity Ordered')}} </th>
                        <th>{{\App\CPU\translate('Unit Item Price')}} </th>
                    </tr>
                    </thead>

                    <tbody>
                       
                    @foreach($orders as $key=>$order)
                   
                        <tr class=" class-all">
                            <td class="">
                           
                            {{$key+1}}
                            </td>
                            <td >
                                <a href="{{route('admin.orders.details',['id'=>$order['id']])}}">{{$order['id']}}</a>
                            </td>
                          
                            <td> {{$order['transport_mode']}}</td>
                            
                            <td class="text-capitalize">
                            @if($order['payment_method'] =='cash_on_delivery')  
                            <span>COD</span>
                            @else
                            <span>Prepaid</span>
                            @endif  
                             </td>
                             <td class="text-capitalize">
                            @if($order['payment_method'] =='cash_on_delivery')  
                            <span>{{\App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($order['order_amount']))}}</span>
                            @else
                            <span>0</span>
                            @endif  
                             </td>
                            <td >
                            @if(json_decode($order['shipping_address_data']))
                               @php($shipping_address=json_decode($order['shipping_address_data']))
                            
                              <span>{{$shipping_address? $shipping_address->contact_person_name : \App\CPU\translate('empty')}}</span><br>
                                 
                                @endif
                            </td>
                            <td>
                            @if(json_decode($order['shipping_address_data']))
                               @php($shipping_address=json_decode($order['shipping_address_data']))
                               <span>{{$shipping_address ? $shipping_address->phone  : \App\CPU\translate('empty')}}</span>
                               @endif
                            </td>
                            <td>
                            @if(json_decode($order['shipping_address_data']))
                               @php($shipping_address=json_decode($order['shipping_address_data']))
                               <span>{{$shipping_address ? $shipping_address->address  : \App\CPU\translate('empty')}}</span>
                               @endif
                            </td>
                            <td>
                            @if(json_decode($order['shipping_address_data']))
                               @php($shipping_address=json_decode($order['shipping_address_data']))
                               <span>{{$shipping_address ? $shipping_address->city  : \App\CPU\translate('empty')}}</span>
                               @endif
                            </td>
                            <td>
                            @if(json_decode($order['shipping_address_data']))
                               @php($shipping_address=json_decode($order['shipping_address_data']))
                               <span>{{$shipping_address ? $shipping_address->state  : \App\CPU\translate('empty')}}</span>
                               @endif
                            </td>
                            <td>
                            @if(json_decode($order['shipping_address_data']))
                               @php($shipping_address=json_decode($order['shipping_address_data']))
                               <span>{{$shipping_address ? $shipping_address->zip  : \App\CPU\translate('empty')}}</span>
                               @endif
                            </td>
                            @php($product=json_decode($order['product'],true))
                          
                            @php($product_sku=json_decode($order['product_sku'],true))
                           
                            <td> {{$product_sku['product_sku']}} </td> 
                            <td> <strong>{{$product['name']}}</strong></td>
                            <td> <strong>{{$order['qty']}}</strong></td>
                            <td> <strong>{{$order['price']}}</strong></td>
                        </tr>
                        
                    
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
function pickup_update(location_id) {
    $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                    }
                });
                $.ajax({
                type: "post",
                url: '{{route('admin.pick_up_update')}}',
                data: {
                    location_id: location_id
                },success: function (data) {
                   console.log(data);
                }
            });
        }
     
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