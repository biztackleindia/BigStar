@extends('layouts.back-end.app')
@section('title', \App\CPU\translate('pincode'))
@push('css_or_js')
    <!-- Custom styles for this page -->
    <link href="{{asset('assets/back-end')}}/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
    <link href="{{asset('assets/back-end/css/croppie.css')}}" rel="stylesheet">
@endpush

@section('content')
    <div class="content container-fluid">

        <div class="row">
            <div class="col-md-12" style="margin-bottom: 10px;">
                <div class="card">
                    <div class="card-header">
                        <h3>{{ \App\CPU\translate('update')}} {{ \App\CPU\translate('pincode')}}</h3>
                    </div>
                    <div class="card-body"
                         style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">
                        <form action="{{route('admin.pincode-role.edit',[$pincodes['id']])}}" method="post">
                            @csrf
                            @php($language=\App\Model\BusinessSetting::where('type','pnc_language')->first())
                            @php($language = $language->value ?? null)
                            @php($default_lang = 'en')

                            @php($default_lang = json_decode($language)[0])
                            
                            
                                
                            <div class="form-group  lang_form">
                              <label for="name">{{ \App\CPU\translate('from')}} {{ \App\CPU\translate('pincode')}} <span class="input-label-secondary">*</span></label>
                                    <input type="text" name="from_pincode" value="{{$pincodes['from_pincode']}}"
                                           class="form-control" placeholder="{{\App\CPU\translate('Enter_pincode')}}" required>
                            </div>
                                
                            <div class="form-group  lang_form">
                              <label for="name">{{ \App\CPU\translate('to')}} {{ \App\CPU\translate('pincode')}} <span class="input-label-secondary">*</span></label>
                                    <input type="text" name="to_pincode" value="{{$pincodes['to_pincode']}}"
                                           class="form-control" placeholder="{{\App\CPU\translate('Enter_pincode')}}" required>
                            </div>
                            <button type="submit" class="btn btn-primary float-right">{{ \App\CPU\translate('update')}}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div>
            @endsection

            @push('script')
                <script>
                    $(".lang_link").click(function (e) {
                        e.preventDefault();
                        $(".lang_link").removeClass('active');
                        $(".lang_form").addClass('d-none');
                        $(this).addClass('active');

                        let form_id = this.id;
                        let lang = form_id.split("-")[0];
                        console.log(lang);
                        $("#" + lang + "-form").removeClass('d-none');
                        if (lang == '{{$default_lang}}') {
                            $(".from_part_2").removeClass('d-none');
                        } else {
                            $(".from_part_2").addClass('d-none');
                        }
                    });

                    $(document).ready(function () {
                        $('#dataTable').DataTable();
                    });
                </script>
    @endpush
