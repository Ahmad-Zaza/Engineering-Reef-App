@extends('crudbooster::admin_template')
@section('content')

    @if(!\Session::has('import_status'))
        <ul class='nav nav-tabs'>
            <li style="background:#ffffff" class='active'><a style="color:#111"
                                                             href='javascript:void(0);'><i class='fa fa-cloud-upload'></i>  استيراد البيانات &raquo;</a></li>
            <li style="background:#eeeeee"><a style="color:#111" href='#'><i class='fa fa-database'></i> نتيجة استيراد البيانات </a></li>                                                 
        </ul>
         
        <!-- Box -->
        <div id='box_main' class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"> استيراد البيانات </h3>
                <div class="box-tools">

                </div>
            </div>

            <?php
            $action_path = CRUDBooster::mainpath();
            $action = $action_path."/get-import-data";
            ?>

            <form method='post' id="form" enctype="multipart/form-data" action='{{url($action)}}'>
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <div class="box-body">

                    <div class='callout callout-success'>
                        <h4>مرحبًا بك في أداة استيراد البيانات  </h4>
                        قبل القيام بتحميل ملف ، من الأفضل قراءة هذه التعليمات التالية: <br/>
                        *  يجب أن يكون تنسيق الملف: xls أو xlsx  <br/>
                        <!--* إذا كان لديك ملف بيانات كبير ، فلا يمكننا ضمان ذلك. لذا ، يرجى تقسيم هذه الملفات إلى بعض أجزاء الملف (بحد أقصى 5 ميجا بايت على الأقل).<br/> -->
                        * تقوم هذه الأداة بإنشاء البيانات تلقائيًا ، لذا كن حذرًا بشأن بنية الجدول xls. يرجى التأكد بشكل صحيح من الجدول
                         <br/>
                        <?php
                            $module_path= CRUDBooster::getCurrentModule()->path;
                            if($module_path == 'persons' || $module_path == 'initial_voucher'){
                                
                                ?>
                               *  .هناك  حقول ضمن ملف البيانات المستورد تعتمد على معلومات ضمن النظام . قم بتنزيل شجرة الحسابات الحالية لاستفادة منها باستكمال معلومات ملف الإكسل المراد استيراده 

                                اضغط على تصدير لتنزيل الملف   . <a href='{{url(config('crudbooster.ADMIN_PATH')."/accounts/export")}}' target='_blank' class='btn btn-info'> تصدير</a>
                                <br/>
                        <?php
                            }
                        ?>

                        <?php
                            if($module_path == 'item_tracking100'){
                                
                                ?>
                               *  .هناك  حقول ضمن ملف البيانات المستورد تعتمد على معلومات ضمن النظام . قم بتنزيل بيانات المستودعات لاستفادة منها باستكمال معلومات ملف الإكسل المراد استيراده 

                                اضغط على تصدير لتنزيل الملف   . <a href='{{url(config('crudbooster.ADMIN_PATH')."/inventories/export")}}' target='_blank' class='btn btn-info'> تصدير</a>
                                <br/>
                        <?php
                            }
                        ?>

                        * يمكنك تنزيل مثال يوضح بنية ملف الاكسل الخاص  بهذا الموديول <a class="btn btn-info" href='{{asset("$example_file")}}' target="_blank">تنزيل <i class="fa fa-download"></i></a>
                    </div>

                    <div class='form-group'>
                        <label>الملف XLS / XLSX</label>
                        <input type='file' name='userfile' id="uploadFile" class='form-control' accept=".xls, .xlsx" required/>
                        <div class='help-block'>أنواع الملفات المقبولة : XLS, XLSX</div>
                    </div>
                </div><!-- /.box-body -->

                <div class="box-footer">
                    <div class='pull-right'>
                        <a href='{{ CRUDBooster::mainpath() }}' class='btn btn-default'>تراجع</a>
                        <button id="submit-btn"  class='btn btn-primary' name="submit"  onclick="onClickSubmitBtn()" > استيراد <i class="fa fa-cloud-upload"></i></button>
                         
                    </div>
                    <div class="">
                        <br/>
                        <br/>
                        <br/>
                        <div class="callout callout-info waiting-msg hidden">
                                        <h4>     رجاءاً ، انتظر</h4>
                                        <p>العملية تأخذ بعض الوقت . <i class="fa fa-refresh fa-spin"></i></p>
                                        
                        </div>
                    </div>
                </div><!-- /.box-footer-->
            </form>
        </div><!-- /.box -->


        @endif

        @if (\Session::has('import_status'))

        <ul class='nav nav-tabs'>
            <li style="background:#eeeeee"><a style="color:#111"
                                                             href='#'><i class='fa fa-cloud-upload'></i>  استيراد البيانات &raquo;</a></li>
            <li style="background:#ffffff" class='active'><a style="color:#111" href='#'><i class='fa fa-database'></i> نتيجة استيراد البيانات</a></li>

        </ul>
         
        <!-- Box -->
        <div id='box_main' class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">  نتيجة استيراد البيانات  </h3>
                <div class="box-tools">

                </div>
            </div>
                <div class="box-body" style="min-height:290px">

                    @if( \Session::get('import_status') == 'success')
                    <div class='callout callout-success'>
                        <h4> <i class="fa  fa-check-circle"></i> تمت العملية بنجاح  </h4>
                        @foreach(\Session::get('reports') as $report)
                          <li>{{$report}}</li> 
                        @endforeach
                    </div>
                    @endif

                    @if( \Session::get('import_status') != 'success')
                    <div class='callout callout-danger'>
                        <h4> <i class="fa   fa-close"></i> لم تتم العملية بنجاح  </h4>
                        @foreach(\Session::get('reports') as $report)
                          <li>{{$report}}</li> 
                        @endforeach
                    </div>
                    @endif
                   
                </div><!-- /.box-body -->

                <div class="box-footer">
                    <div class='pull-right'>
                        <a href='{{ CRUDBooster::mainpath() }}' class='btn btn-default'>انتهاء</a>
                        @if( \Session::get('import_status') != 'success')
                            <a href='{{ $action }}' class='btn btn-primary'>حاول مرة أخرى</a>
                        @endif
                    </div>

                </div><!-- /.box-footer-->
         
        </div><!-- /.box -->

        @endif

        </div><!-- /.col -->


        </div><!-- /.row -->

        
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>

<script type="text/javascript">
function onClickSubmitBtn(){
           
    if(confirm("هل أنت متأكد")){

        if(document.getElementById("uploadFile").value != "") {
            $("#submit-btn").addClass('disabled');
            $("#submit-btn i").removeClass('fa-cloud-upload');
            $("#submit-btn i").addClass('fa-spinner fa-spin');
            $('.waiting-msg').removeClass('hidden');
        }
       
    }
                    
           
        }
</script>
@endsection