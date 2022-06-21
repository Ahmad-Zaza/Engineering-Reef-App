@extends('crudbooster::admin_template')
@section('content')


    @if ($button_show_data || $button_reload_data || $button_new_data || $button_delete_data || $index_button || $columns)
        <div id='box-actionmenu' class='box'>
            <div class='box-body'>
                @include('crudbooster::default.actionmenu')
            </div>
        </div>
    @endif


    @if (Request::get('file') && Request::get('import'))
        <ul class='nav nav-tabs'>
            <li style="background:#eeeeee"><a style="color:#111"
                    onclick="if(confirm('هل أنت متأكد من المغادرة ؟')) location.href='{{ CRUDBooster::mainpath('import-data') }}'"
                    href='javascript:;'><i class='fa fa-download'></i> رفع ملف &raquo;</a></li>
            <li style="background:#ffffff" class='active'><a style="color:#111" href='#'><i
                        class='fa fa-cloud-download'></i> جاري الاستيراد &raquo;</a></li>
        </ul>

        <!-- Box -->
        <div id='box_main' class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">جاري الاستيراد</h3>
                <div class="box-tools">
                </div>
            </div>

            <div class="box-body">

                <p style='font-weight: bold' id='status-import'><i class='fa fa-spin fa-spinner'></i> الرجاء الانتظار
                    جاري الاستيراد...</p>
                <div class="progress">
                    <div id='progress-import' class="progress-bar progress-bar-primary progress-bar-striped"
                        role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: 0%">
                        <span class="sr-only">40% Complete (success)</span>
                    </div>
                </div>

                @push('bottom')
                    <script type="text/javascript">
                        $(function() {
                            var total = {{ intval(Session::get('total_data_import')) }};

                            var int_prog = setInterval(function() {

                                $.post("{{ CRUDBooster::mainpath('do-import-chunk?file=' . Request::get('file')) }}", {
                                    resume: 1
                                }, function(resp) {
                                    console.log(resp.progress);
                                    value = $('#progress-import').attr('aria-valuenow');
                                    if (value < 100) {
                                        $('#progress-import').css('width', resp.progress + '%');
                                        $('#status-import').html(
                                            "<i class='fa fa-spin fa-spinner'></i> الرجاء الانتظار جاري الاستيراد... (" +
                                            resp.progress + "%)");
                                        $('#progress-import').attr('aria-valuenow', resp.progress);
                                        if (resp.progress >= 100) {
                                            $('#status-import').addClass('text-success').html(
                                                "<i class='fa fa-check-square-o'></i> تم استيراد البيانات !");
                                            clearInterval(int_prog);
                                        }
                                    }
                                })


                            }, 2500);

                            $.post("{{ CRUDBooster::mainpath('do-import-chunk') . '?file=' . Request::get('file') }}", function(
                                resp) {
                                if (resp.status == true) {
                                    $('#progress-import').css('width', '100%');
                                    $('#progress-import').attr('aria-valuenow', 100);
                                    $('#status-import').addClass('text-success').html(
                                        "<i class='fa fa-check-square-o'></i> تم استيراد البيانات !");
                                    clearInterval(int_prog);
                                    $('#upload-footer').show();
                                    console.log('Import Success');
                                }
                            })

                        })
                    </script>
                @endpush

            </div><!-- /.box-body -->

            <div class="box-footer" id='upload-footer' style="display:none">
                <div class='pull-right'>
                    <a href='{{ CRUDBooster::mainpath('import-data') }}' class='btn btn-default'><i
                            class='fa fa-upload'></i> رفع ملف جديد</a>
                    <a href='{{ CRUDBooster::mainpath() }}' class='btn btn-success'>العودة إلى قائمة المعاملات</a>
                </div>
            </div><!-- /.box-footer-->

        </div><!-- /.box -->
    @endif

    @if (!Request::get('file'))
        <ul class='nav nav-tabs'>
            <li style="background:#ffffff" class='active'><a style="color:#111"
                    onclick="if(confirm('هل أنت متأكد من المغادرة ؟')) location.href='{{ CRUDBooster::mainpath('import-data') }}'"
                    href='javascript:;'><i class='fa fa-download'></i> رفع ملف &raquo;</a></li>
            <li style="background:#eeeeee"><a style="color:#111" href='#'><i class='fa fa-cloud-download'></i> الاستيراد
                    &raquo;</a></li>
        </ul>

        <!-- Box -->
        <div id='box_main' class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">رفع ملف</h3>
                <div class="box-tools">

                </div>
            </div>

            <?php
            if ($data_sub_module) {
                $action_path = Route($data_sub_module->controller . 'GetIndex');
            } else {
                $action_path = CRUDBooster::mainpath();
            }
            
            $action = $action_path . '/do-upload-import-data';
            ?>

            <form method='post' id="form" enctype="multipart/form-data" action='{{ $action }}'>
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <div class="box-body">

                    <div class='callout callout-success'>
                        <h4>تعليمات استيراد المعاملات</h4>
                        قبل البدء برفع الملف الرجاء قراءة التعليمات التالية : <br />
                        * لاحقة الملف يجب أن تكون إحدى اللواحق التالية: xls or xlsx or csv<br />
                        * إذا كان الملف المراد استيراده يحتوي العديد من السجلات لايمكننا ضمان استيراد الملف بشكل كامل
                        لذلك الرجاء تقسيم الملف إلى 1000 سجل في كل ملف<br />
                        * بنية الجدول: السطر الأول هو الـ Heading والأسطر التالية هي التي ستحتوي على البيانات <br />
                        * إذا احتوى الملف على معاملة موجودة سابقا سيتم تخطيها أثناء الاستيراد<br />
                        * نموذج عن شكل ملف الاستيراد  يحتوي <a href='{{ url('/example.xlsx') }}' target='_blank' class='btn btn-primary'>تحميل</a>
                    </div>

                    <div class='form-group'>
                        <label>File XLS / CSV</label>
                        <input type='file' name='userfile' class='form-control' required />
                        <div class='help-block'>أنواع الملفات المسموحة : XLS, XLSX, CSV</div>
                    </div>
                </div><!-- /.box-body -->

                <div class="box-footer">
                    <div class='pull-right'>
                        <a href='{{ CRUDBooster::mainpath() }}' class='btn btn-default'>إلغاء الأمر</a>
                        <input type='submit' class='btn btn-primary' name='submit' value='رفع ملف' />
                    </div>
                </div><!-- /.box-footer-->
            </form>
        </div><!-- /.box -->
    @endif
    </div><!-- /.col -->


    </div><!-- /.row -->

@endsection
