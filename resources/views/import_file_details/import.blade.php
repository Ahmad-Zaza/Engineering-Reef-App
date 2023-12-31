@extends('crudbooster::admin_template')
@section('content')
    @if ($button_show_data ||
        $button_reload_data ||
        $button_new_data ||
        $button_delete_data ||
        $index_button ||
        $columns)
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
                                } else {
                                    $('#progress-import').css('width', '0%');
                                    $('#progress-import').attr('aria-valuenow', 0);
                                    $('#status-import').addClass('text-danger').html(
                                        "<i class='fa fa-close-square-o'></i>حدثت مشكلة لم يتم استيراد البيانات!");
                                    clearInterval(int_prog);
                                    $('#upload-footer').show();
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
                    <a href='{{ CRUDBooster::mainpath() }}' class='btn btn-success'>العودة إلى قائمة القبض الشهري</a>
                </div>
            </div><!-- /.box-footer-->

        </div><!-- /.box -->
    @endif

    @if (!Request::get('file'))
        <!-- Box -->
        <div id='box_main' class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">عملية استيراد ملف</h3>
                <div class="box-tools">

                </div>
            </div>


            <form method='post' id="form" enctype="multipart/form-data"
                action='{{ CRUDBooster::mainpath('add-save') }}'>
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <div class="box-body">

                    <div class='callout callout-success'>
                        <h4>تعليمات استيراد الملفات</h4>
                        قبل البدء برفع الملف الرجاء قراءة التعليمات التالية : <br />
                        * لاحقة الملف يجب أن تكون إحدى اللواحق التالية: xls or xlsx or csv<br />
                        * يجب أن يكون ترتيب عمليات الاستيراد بالشكل التالي: الذاتية ثم المعاملات ثم القبض الشهري ثم الإقامات المسددة <br/>
                        * بنية الجدول: السطر الأول هو الـ Heading والأسطر التالية هي التي ستحتوي على البيانات <br />
                        * إذا احتوى ملف المعاملات على معاملة موجودة سابقا سيتم تخطيها أثناء الاستيراد<br />
                        * إذا كان هناك أي خلية ليس لديها قيم الرجاء تركها فارغة وعدم اسناد أي رموز او نصوص لا تعبر عن القيمة الفعلية<br />
                        * إذا احتوى الملف على عمود رقم وتاريخ المذكرة فيجب الفصل بينهم بالرمز ":"<br />
                        * جميع التواريخ يجب أن تكون من الصيغة التالية:
                        <br>
                        d/m/Y
                        مثلا 10/01/2022<br />
                        <p class="file-example">
                            * نموذج عن شكل ملف الاستيراد للقبض الشهري <a
                                href='{{ url('/financial_deals_template.xlsx') }}' target="_blank"
                                class='btn btn-primary'>تحميل</a>
                        </p>
                        <p class="file-example">
                            * نموذج عن شكل ملف الاستيراد للذاتية <a
                                href='{{ url('/personal_.xlsx') }}' target="_blank"
                                class='btn btn-primary'>تحميل</a>
                        </p>
                        <p class="file-example">
                            * نموذج عن شكل ملف الاستيراد للاقامات <a
                                href='{{ url('/paid_deals_template.xlsx') }}' target="_blank"
                                class='btn btn-primary'>تحميل</a>
                        </p>

                        <p class="file-example">
                            * نموذج عن شكل ملف الاستيراد للمعاملات <a
                                href='{{ url('/example.xlsx') }}' target="_blank"
                                class='btn btn-primary'>تحميل</a>
                        </p>

                    </div>

                    <div class='form-group'>
                        <label>الملف</label>
                        <input type='file' name='name' class='form-control' required />
                        <div class='help-block'>أنواع الملفات المسموحة : XLS, XLSX, CSV</div>
                    </div>
                    <div class='form-group'>
                        <label>النوع</label>
                        <select name='type' class='form-control' required>
                            <option value="">اختر النوع المطلوب</option>
                            <option value="deals"> الأعمال الشهرية </option>
                            <option value="paid_stays">إقامات مسددة</option>
                            <option value="monthly_ammounts">القبض الشهري</option>
                            <option value="personal">ملفات الذاتية</option>
                        </select>
                        <div class='help-block'></div>
                    </div>
                </div><!-- /.box-body -->

                <div class="box-footer">
                    <div class='pull-right'>
                        <a href='{{ CRUDBooster::mainpath() }}' class='btn btn-default'>إلغاء الأمر</a>
                        <input type='submit' class='btn btn-primary' name='submit' value='حفظ' />
                    </div>
                </div><!-- /.box-footer-->
            </form>
        </div><!-- /.box -->
    @endif
    </div><!-- /.col -->


    </div><!-- /.row -->
@endsection