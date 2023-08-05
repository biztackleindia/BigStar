@extends('layouts.back-end.app')
@section('title', \App\CPU\translate('Create Pincode'))
@push('css_or_js')
    <!-- Custom styles for this page -->
    <link href="{{asset('public/assets/back-end')}}/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
@endpush

@section('content')
    <div class="content container-fluid">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">{{\App\CPU\translate('Dashboard')}}</a>
                </li>
                <li class="breadcrumb-item" aria-current="page">{{\App\CPU\translate('pincode')}}</li>
            </ol>
        </nav>

        <!-- Content Row -->
    <div class="row">
        <div class="col-md-12" style="margin-bottom: 10px;">
            <div class="card">
                <div class="card-header">
                    {{ \App\CPU\translate('Add')}} {{ \App\CPU\translate('new')}} {{ \App\CPU\translate('pincode')}}
                </div>
                <div class="card-body" style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">
                <form action="{{route('admin.pincode-role.store')}}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <div class="row">
                                
                                <div class="col-md-12">
                                    <label for="name">{{\App\CPU\translate('from')}} {{\App\CPU\translate('pincode')}}<span class="input-label-secondary">*</span></label>
                                    <input type="number" name="from_pincode" value="{{old('from_pincode')}}" class="form-control" 
                                           placeholder="{{\App\CPU\translate('Enter_pincode')}} " required>
                                </div>
                                <div class="col-md-12 mt-3">
                                    <label for="name">{{\App\CPU\translate('to')}} {{\App\CPU\translate('pincode')}}<span class="input-label-secondary">*</span></label>
                                    <input type="number" name="to_pincode" value="{{old('to_pincode')}}" class="form-control" 
                                           placeholder="{{\App\CPU\translate('Enter_pincode')}} " required>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary float-right">{{\App\CPU\translate('submit')}}</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="row flex-between justify-content-between align-items-center flex-grow-1">
                            <div class="col-12 col-md-7">
                                <h5>{{ \App\CPU\translate('pincode')}} {{ \App\CPU\translate('Table')}} </h5>
                            </div>
                            <div class="col-12 col-md-5" style="width: 30vw">
                                <!-- Search -->
                                <form action="{{ url()->current() }}" method="GET">
                                    <div class="input-group input-group-merge input-group-flush">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="tio-search"></i>
                                            </div>
                                        </div>
                                        <input id="datatableSearch_" type="search" name="search" class="form-control"
                                            placeholder="{{\App\CPU\translate('search')}} {{\App\CPU\translate('pincode')}}" aria-label="Search orders" value="{{ $search }}" required>
                                        <button type="submit" class="btn btn-primary">{{ \App\CPU\translate('Search')}}</button>
                                    </div>
                                </form>
                                <!-- End Search -->
                            </div>
                        </div>
                </div>
                <div class="card-body" style="padding: 0; text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">
                    <div class="table-responsive">
                        <table id="datatable"
                               class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table"
                               style="width: 100%">
                            <thead class="thead-light">
                            <tr>
                                <th style="">{{ \App\CPU\translate('SL#')}}</th>
                                <th style="width: 30%;text-align:center;">{{ \App\CPU\translate('from')}} {{ \App\CPU\translate('pincode')}} </th>
                                <th style="width: 30%;text-align:center;">{{ \App\CPU\translate('to')}} {{ \App\CPU\translate('pincode')}} </th>
                                <th style="width: 25%;text-align:center;"> {{ \App\CPU\translate('cash_on_delivery')}} </th>
                                <th style="width: 25%;text-align:center;"> {{ \App\CPU\translate('online_payment')}} </th>
                                <th style="width: 20%;text-align:center;">{{ \App\CPU\translate('Action')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            
                            @foreach($pincodes as $key=>$pincode)
                            
                                <tr>
                                    <td>{{$pincodes->firstItem()+$key}}</td>
                                    <td style="width: 40%;text-align:center;">{{$pincode['from_pincode']}}</td>
                                    <td style="width: 40%;text-align:center;">{{$pincode['to_pincode']}}</td>
                                    <td style="text-align: center;">
                                        <label class="switch">
                                            <input type="checkbox"
                                                   onclick="Cashondelivery_status('{{$pincode['id']}}')" {{$pincode->is_cash_on_delivery == 1?'checked':''}}>
                                            <span class="slider round"></span>
                                        </label>
                                    </td>
                                    
                                    <td style="text-align: center;">
                                        <label class="switch">
                                            <input type="checkbox"
                                                   onclick="Onlinedelivery_status('{{$pincode['id']}}')" {{$pincode->is_online_payment == 1?'checked':''}}>
                                            <span class="slider round"></span>
                                        </label>
                                    </td>
                                    <td style="width: 40%;text-align:center;">
                                        
                                        <!-- <a class="btn btn-primary btn-sm edit" style="cursor: pointer;"
                                            title="{{ \App\CPU\translate('Edit')}}"
                                            href="{{route('admin.pincode-role.edit',[$pincode['id']])}}"> 
                                            <i class="tio-edit"></i>
                                        </a> -->
                                        <a class="btn btn-danger btn-sm delete"style="cursor: pointer;"
                                            title="{{ \App\CPU\translate('Delete')}}"
                                            href="{{route('admin.pincode-role.delete',[$pincode['id']])}}"
                                            id="{{$pincode['id']}}">  
                                            <i class="tio-add-to-trash"></i>
                                        </a>
                                            
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer">
                    {!! $pincodes->links() !!}
                </div>
                @if(count($pincodes)==0)
                        <div class="text-center p-4">
                            <img class="mb-3" src="{{asset('public/assets/back-end')}}/svg/illustrations/sorry.svg" alt="Image Description" style="width: 7rem;">
                            <p class="mb-0">{{ \App\CPU\translate('No_data_to_show')}}</p>
                        </div>
                    @endif
            </div>
        </div>

    </div>
        
    </div>
@endsection

@push('script')
    <!-- Page level plugins -->
    <script src="{{asset('public/assets/back-end')}}/vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="{{asset('public/assets/back-end')}}/vendor/datatables/dataTables.bootstrap4.min.js"></script>
    <!-- Page level custom scripts -->
    <script>
        $(document).ready(function () {
            $('#dataTable').DataTable();
        });
    </script>
    <script>

        $('#submit-create-role').on('submit',function(e){
            
            var fields = $("input[name='modules[]']").serializeArray(); 
            if (fields.length === 0) 
            { 
                toastr.warning('{{ \App\CPU\translate('select_minimum_one_selection_box') }}', {
                            CloseButton: true,
                            ProgressBar: true
                        });
                return false;
            }else{
                $('#submit-create-role').submit();
            } 
        });
        function Cashondelivery_status(id) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            });
            $.ajax({
                url: "{{route('admin.pincode-role.cash-on-delivery')}}",
                method: 'POST',
                data: {
                    id: id
                },
                success: function () {
                    toastr.success('{{\App\CPU\translate('Updated successfully')}}');
                }
            });
        }
        function Onlinedelivery_status(id) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            });
            $.ajax({
                url: "{{route('admin.pincode-role.online-delivery')}}",
                method: 'POST',
                data: {
                    id: id
                },
                success: function () {
                    toastr.success('{{\App\CPU\translate('Updated successfully')}}');
                }
            });
        }
    </script>
@endpush
