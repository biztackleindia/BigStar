@extends('layouts.back-end.app')

@section('title', \App\CPU\translate('Points'))

@push('css_or_js')

@endpush

@section('content')
    
    <div class="content container-fluid">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a
                        href="{{route('admin.dashboard')}}">{{\App\CPU\translate('Dashboard')}}</a>
                </li>
                <li class="breadcrumb-item" aria-current="page">{{\App\CPU\translate('Points')}}</li>
            </ol>
        </nav>
        <!-- Page Heading -->

        <div class="row">
           
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header">
                        <h5 class="text-center">
                           
                            {{\App\CPU\translate('Points')}}
                        </h5>
                    </div>
                    <div class="card-body">
                        <form action="{{route('admin.points.store')}}" method="post">
                            @csrf
                            <div class="form-group">
                                <div class="row mb-2">
                                    <div class="col-md-6">
                                    
                                        <div>{{\App\CPU\translate('Referral Reward points')}}</div>
                                    </div>
                                    <div class="col-md-6">
                                    <input type="number" name="referral_reward_points" value="{{$referral_reward_points}}" class="form-control"
                                               id="referral_reward_points" placeholder="0">
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-md-6">
                                    <div>{{\App\CPU\translate('Product Reward points')}}</div>
                                    </div>
                                    <div class="col-md-6">
                                        <input type="number" name="product_reward_points" value="{{$product_reward_points}}" class="form-control"
                                               id="product_reward_points" placeholder="0">
                                    </div>
                                </div>

                                <div class="row mb-2">
                                    <div class="col-md-6">
                                    <div>{{\App\CPU\translate('Product Amount for Reward is')}}</div>
                                    </div>
                                    <div class="col-md-6">
                                        <input type="number" name="product_min_amount" value="{{$product_min_amount}}" class="form-control"
                                               id="product_min_amount" placeholder="0">
                                    </div>
                                </div>

                            </div>
                            

                            <div class="form-group text-right">
                                <button type="submit" id="add" class="btn btn-primary text-capitalize"
                                        style="color: white">
                                    {{\App\CPU\translate('update')}}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            

        </div>



    </div>
@endsection

@push('script')
    <!-- Page level custom scripts -->
    <script src="{{ asset('public/assets/select2/js/select2.min.js')}}"></script>
    <script>
        $(".js-example-theme-single").select2({
            theme: "classic"
        });

        $(".js-example-responsive").select2({
            width: 'resolve'
        });
    </script>

    <script>
        $('#add').on('click', function () {
            var name = $('#name').val();
            var symbol = $('#symbol').val();
            var code = $('#code').val();
            var exchange_rate = $('#exchange_rate').val();
            if (name == "" || symbol == "" || code == "" || exchange_rate == "") {
                alert('{{\App\CPU\translate('All input field is required')}}');
                return false;
            } else {
                return true;
            }
        });
        $(document).on('change', '.status', function () {
            var id = $(this).attr("id");
            if ($(this).prop("checked") == true) {
                var status = 1;
            } else if ($(this).prop("checked") == false) {
                var status = 0;
            }

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            });
            $.ajax({
                url: "{{route('admin.currency.status')}}",
                method: 'POST',
                data: {
                    id: id,
                    status: status
                },
                success: function (response) {
                    if (response.status === 1) {
                        toastr.success(response.message);
                    } else {
                        toastr.error(response.message);
                    }
                    location.reload();
                }
            });
        });
    </script>
    <script>
        $(document).on('click', '.delete', function () {
        var id = $(this).attr("id");
        Swal.fire({
            title: '{{\App\CPU\translate('Are you sure delete this')}} ?',
            text: "{{\App\CPU\translate('You will not be able to revert this')}}!",
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: '{{\App\CPU\translate('Yes, delete it')}}!',
            type: 'warning',
            reverseButtons: true
        }).then((result) => {
            if (result.value) {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                    }
                });
                $.ajax({
                    url: "{{route('admin.currency.delete')}}",
                    method: 'POST',
                    data: {id: id},
                    success: function (data) {
                        
                        if(data.status ==1){
                            toastr.success('{{\App\CPU\translate('Currency removed successfully!')}}');
                            location.reload();
                        }else{
                            toastr.warning('{{\App\CPU\translate('This Currency cannot be removed due to payment gateway dependency!')}}');
                            location.reload();
                        }
                    }
                });
            }
        })
    });
    </script>
    <script>
        function default_currency_delete_alert()
        {
            toastr.warning('{{\App\CPU\translate('default currency can not be deleted!to delete change the default currency first!')}}');
        }
    </script>
@endpush
