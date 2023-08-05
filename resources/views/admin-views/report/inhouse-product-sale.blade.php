@extends('layouts.back-end.app')

@section('title', \App\CPU\translate('product sale report'))

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush
<style>
.report_btn a {
    color: #fff !important;
}

.report_btn {
    margin-bottom: 15px;
    color: #fff;
    min-width: 230px;
    background-color: #a1c63a;
    border-color: #045cff;
    border: 0.0625rem solid transparent;
    padding: 0.54688rem 0.875rem;
    font-size: .875rem;
    line-height: 1.6;
    border-radius: 0.3125rem;
    transition: all .2s ease-in-out;
}
</style>
@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <!-- Nav -->
            <div class="js-nav-scroller hs-nav-scroller-horizontal">
                <ul class="nav nav-tabs page-header-tabs" id="projectsTab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" href="javascript:">{{\App\CPU\translate('product sale report')}}</a>
                    </li>
                </ul>
            </div>
            <!-- End Nav -->
        </div>
        <!-- End Page Header -->
        <div class="col-lg-12">
                <form action="{{route('admin.export_sale_report')}}" method="post">
                    @csrf
                    <div class="row">
                        <div class="col-12">
                            <div class="mb-3">
                                <label for="exampleInputEmail1"
                                       class="form-label">{{\App\CPU\translate('Show data by date range')}}</label>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="mb-3">
                                <input type="date" value="{{date('Y-m-d',strtotime(session('from_date')))}}" name="from" id="from_date"
                                       class="form-control" required>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="mb-3">
                                <input type="date" name="to" value="{{date('Y-m-d',strtotime(session('to_date')))}}" id="to_date"
                                       class="form-control" required>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="mb-3">
                                <button type="submit"
                                        class="btn btn-primary btn-block">{{\App\CPU\translate('Export Report')}}</button>
                            </div>
                        </div>
                    </div>
                </form>
                <!-- Export report -->

                <!-- <div>
                    <button class="report_btn" id="export_sale_report"><a href="{{route('admin.export_sale_report')}}"
                    >Export Report</a></button>
                </div> -->
            </div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <form style="width: 100%;" action="{{route('admin.report.inhoue-product-sale')}}">
                            @csrf
                            <div class="flex-between row">
                                <div class="col-2 text-center">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">{{\App\CPU\translate('Category')}}</label>
                                    </div>
                                </div>
                                <div class="col-7">
                                    <div class="form-group">
                                        <select
                                            class="js-select2-custom form-control"
                                            name="category_id">
                                            <option value="all">{{\App\CPU\translate('All')}}</option>
                                            @foreach($categories as $c)
                                                <option value="{{$c['id']}}" {{$category_id==$c['id']? 'selected': ''}}>
                                                    {{$c['name']}}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-12 col-md-3">
                                    <button type="submit" class="btn btn-primary btn-block">
                                        {{\App\CPU\translate('Filter')}}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="card-body"
                         style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">
                        <table class="table">
                            <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">
                                    {{\App\CPU\translate('Product Name')}} <label class="badge badge-success ml-3"
                                                        style="cursor: pointer">{{\App\CPU\translate('ASE/DESC')}}</label>
                                </th>
                                <th scope="col">
                                    {{\App\CPU\translate('Total Sale')}} <label class="badge badge-success ml-3"
                                                      style="cursor: pointer">{{\App\CPU\translate('ASE/DESC')}}</label>
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($products as $key=>$data)
                                <tr>
                                    <th scope="row">{{$key+1}}</th>
                                    <td>{{$data['name']}}</td>
                                    <td>{{$data->order_delivered->sum('qty')}}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <table>
                            <tfoot>
                            {!! $products->links() !!}
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Stats -->
    </div>
@endsection

@push('script')

@endpush

@push('script_2')

@endpush
