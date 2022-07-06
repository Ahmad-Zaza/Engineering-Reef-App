@extends('crudbooster::admin_template')
@section('content')

    <style>
        #set-seo {
            display: none;
        }
    </style>
    <div>

        @if (CRUDBooster::getCurrentMethod() != 'getProfile' && $button_cancel)
            @if (g('return_url'))
                <p><a title='Return' href='{{ g('return_url') }}'><i class='fa fa-chevron-circle-left '></i>
                        &nbsp;
                        {{ trans('crudbooster.form_back_to_list', ['module' => CRUDBooster::getCurrentModule()->name]) }}</a>
                </p>
            @else
                <p><a title='Main Module' href='{{ CRUDBooster::mainpath() }}'><i class='fa fa-chevron-circle-left '></i>
                        &nbsp;
                        {{ trans('crudbooster.form_back_to_list', ['module' => CRUDBooster::getCurrentModule()->name]) }}</a>
                </p>
            @endif
        @endif

        <div class="panel panel-default">
            <div class="panel-heading">
                <strong><i class='{{ CRUDBooster::getCurrentModule()->icon }}'></i> {!! $page_title or 'Page Title' !!}</strong>
            </div>

            <div class="panel-body" style="padding:20px 0px 0px 0px">
                <?php
                $action = @$row ? CRUDBooster::mainpath("edit-save/$row->id") : CRUDBooster::mainpath('add-save');
                $return_url = $return_url ?: g('return_url');
                ?>
                <form class='form-horizontal' method='post' id="form" enctype="multipart/form-data"
                    action='{{ $action }}'>
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type='hidden' name='return_url' value='{{ @$return_url }}' />
                    <input type='hidden' name='ref_mainpath' value='{{ CRUDBooster::mainpath() }}' />
                    <input type='hidden' name='ref_parameter' value='{{ urldecode(http_build_query(@$_GET)) }}' />
                    @if ($hide_form)
                        <input type="hidden" name="hide_form" value='{!! serialize($hide_form) !!}'>
                    @endif
                    <div class="box-body" id="parent-form-area">

                        @if ($command == 'detail')
                            @include('crudbooster::default.form_detail')
                        @else
                            @include('crudbooster::default.form_body')
                        @endif
                    </div><!-- /.box-body -->

                    <div class="box-footer" style="background: #F5F5F5">

                        <div class="form-group">
                            <label class="control-label col-sm-2"></label>
                            <div class="col-sm-10">


                                @if (CRUDBooster::isCreate() || CRUDBooster::isUpdate())

                                    @if (CRUDBooster::isCreate() && $button_addmore == true && $command == 'add')
                                        @if (CRUDBooster::getCurrentModule()->hasImage == 1)
                                            <input type="submit" name="submit"
                                                value='{{ trans('crudbooster.button_save_and_images') }}'
                                                class='btn btn-success'>
                                        @else
                                            <input type="submit" name="submit"
                                                value='{{ trans('crudbooster.button_save_more') }}'
                                                class='btn btn-success'>
                                        @endif
                                    @endif

                                    @if ($button_save && $command != 'detail')

                                        @if (CRUDBooster::getCurrentModule()->hasImage == 1 && $row != null)
                                            <?php
                                            $images = \DB::table('image_model')
                                                ->where([
                                                    'model' => CRUDBooster::getCurrentModule()->name,
                                                    'model_id' => $row->id,
                                                ])
                                                ->get();
                                            ?>

                                            <input type="hidden" id="list_images" name="list_images[]">
                                            <input type="hidden" id="moduleName"
                                                value="{{ CRUDBooster::getCurrentModule()->name }}">
                                            <input type="hidden" id="rowId" value="{{ $row->id }}">

                                            <a onclick="OpenInsertImage('{{ CRUDBooster::getCurrentModule()->name }}',{{ $row->id }})"
                                                class="btn btn-primary">Add Images </a>
                                            <div id="show-images" class="well">

                                                @foreach ($images as $element)
                                                    <a data-lightbox="roadtrip" id="image{{ $element->id }}"
                                                        href="{{ url('' . $element->path) }} ">
                                                        <img style="width:150px;height:150px;" title="Image For Image"
                                                            src="{{ url('' . $element->path) }}">
                                                    </a>
                                                    <span onclick="deleteImageList({{ $element->id }})"
                                                        id="span-{{ $element->id }}"
                                                        style='color:red;position: relative;cursor:pointer;bottom: 58px;'><i
                                                            class='fa fa-close'></i></span>
                                                @endforeach
                                            </div>
                                        @endif
                                        @if (CRUDBooster::getCurrentMethod() == 'getEdit')
                                            <input type="submit" name="submit" value='{{ trans('crudbooster.Edit') }}'
                                                id='btn-save-data' class='btn btn-success'>
                                        @else
                                            <input type="submit" name="submit"
                                                value='{{ trans('crudbooster.button_save') }}' id='btn-save-data'
                                                class='btn btn-success'>
                                        @endif



                                    @endif

                                @endif
                                @if ($button_cancel && CRUDBooster::getCurrentMethod() != 'getDetail')
                                    @if (g('return_url'))
                                        <a href='{{ g('return_url') }}' class='btn btn-default'><i
                                                class='fa fa-chevron-circle-left'></i>
                                            {{ trans('crudbooster.button_back') }}</a>
                                    @else
                                        <a href='{{ CRUDBooster::mainpath('?' . http_build_query(@$_GET)) }}'
                                            class='btn btn-default'><i class='fa fa-chevron-circle-left'></i>
                                            {{ trans('crudbooster.button_back') }}</a>
                                    @endif
                                @endif
                            </div>
                        </div>


                    </div><!-- /.box-footer-->

                </form>
            </div>
        </div>
    </div>
    <!--END AUTO MARGIN-->
    <div class="modal fade" id="modalInsertPhoto">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">Insert Image</h4>
                </div>
                <div class="modal-body" style="padding:0px; margin:0px; width: 100%;">

                </div>

            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

    <script language="javascript" type="text/javascript">
        $("#form").submit(function(e) {
            var data = $("#form").serialize();
            $("#form").submit();
            console.log(data);
            // debugger;

        });
    </script>

@endsection
