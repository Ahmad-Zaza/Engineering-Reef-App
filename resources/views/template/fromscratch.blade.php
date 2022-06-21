
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Landing Page Builder</title>
    <meta charset="utf-8">
    <!-- <meta name="viewport" content="width=device-width, initial-scale=1"> -->

    <link rel="stylesheet" type="text/css" href="/js/includes/css/style_ltr.css" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="/landing_page/js/inc/grideditor.css" rel="stylesheet" type="text/css" />
    <link href="/landing_page/js/dist/css/bootstrap.min_ltr.css" rel="stylesheet" type="text/css" />
    <link href="/landing_page/js/fancybox/jquery.fancybox-1.3.4.min.css" rel="stylesheet" type="text/css" />
    <link href="/landing_page/js/dist/colorpicker/bootstrap-colorpicker.css" rel="stylesheet" type="text/css" />
    <link href="/landing_page/js/dist/bootstrap-switch/css/bootstrap-switch.min.css" rel="stylesheet" type="text/css" />
    <link href='/landing_page/css/dragula.min.css' rel='stylesheet' type='text/css' />
    <link href="/landing_page/css/landing_page.css" rel="stylesheet">
    <link rel="stylesheet" href="{!! asset('landing_page/css/dropzone.css') !!}" />

    <style>
        .submitclass {
            margin-top: 0 !important;
        }

        .btn-primary {
            margin: 0 2%;
        }
    </style>


</head>

<body>
    <div class="p-loader">
        <div class="loader"></div>
    </div>
    <div class="well-bar well col-sm-12" style="display: flex;">

        <div class="col-sm-12">
            <div class="col-sm-4">
                <label>Background of Landing&nbsp;</label>
                <input  type="color" id="color-body-temp" onchange="changeBGColor(this)">
                <input type="hidden" id="image-body" value="">
                <input type="hidden" id="color-body" value="">
                <button type="button" class="btn btn-primary submitclass" onclick="showImagesBackground()">
                    select image
                </button>
                <input type="text" class="form-control hide" disabled id="path1">
                <div class="hidden" id="image-background-div"></div>
            </div>
            <div class="col-sm-4 text-center">
                <div class="header-label-page">
                    <input type="text" class="text-center landing-title" placeholder="Landing Title"
                        value="Landing Title">
                </div>
            </div>
            <div class="col-sm-4">
                <button type="button" class="btn btn-success submitclass pull-right" onclick="saveLanding()">
                    save Landing
                </button>
                <a class="btn btn-primary pull-right" href="{{url('modules/landing_pages')}}">my landingPages</a>
            </div>
        </div>
    </div>
    <div class="wrapper">
        <div id="content">
            <div id="landingTosave">
                <div id="scratchGrid" style='min-height:200px;'>
                </div>
                <!-- <div class='container'>
			<input type="button" value="Back" onclick=" history.back(0);">
			<input name="sub" type="submit" id="saveContent" value="Save">
		</div> -->
                <input type="hidden" name="id" value="">
                <input type="hidden" name="table" value="">
                <input type="hidden" name="field" value="">




            </div>
        </div>


        <div class="modal fade " id="BackgroundRowModal" tabindex="-1" role="dialog"
            aria-labelledby="Backgroud Row">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                        <h3 class="modal-title" id="gridSystemModalLabel"> Settings </h3>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-sm-12">
                                <div>
                                    <!-- Nav tabs -->
                                    <ul
                                        class="nav nav-tabs" role="tablist">
                                        <li role="presentation" class="active"><a href="#style_dom_ext" aria-controls="style_dom_ext" role="tab" data-toggle="tab"> style </a></li>
                                        <li role="presentation"><a href="#settings_dom_ext" aria-controls="settings_dom_ext" role="tab" data-toggle="tab"> Settings </a></li>
                                        <li role="presentation"><a href="#visible_cols_dom_ext" aria-controls="visible_cols_dom_ext" role="tab" data-toggle="tab">Visible cols</a></li>

                                    </ul>
                                    <!-- Tab panes -->
                                    <div class="tab-content" style='margin-top:20px;'>
                                        <div role="tabpanel" class="tab-pane active" id="style_dom_ext">
                                            <div class="form-horizontal">
                                                <div class="form-group">
                                                    <label for="cssClassSetting" class="col-sm-3 control-label">Css Class</label>
                                                    <div class="col-sm-7">
                                                        <input type="text" class="form-control" id="cssClassSetting">
                                                    </div>
                                                </div>
                                                <div class="form-group" id="BackGroundTypeDiv">
                                                    <label class="col-xs-3 control-label" for="BackgroundType"> background :</label>
                                                    <div class="col-xs-7"> <input id="BackgroundType" class="switch-btn" type="checkbox" value="1" checked="" data-on-text=" color " data-off-text=" image " data-on-color="danger"></div>
                                                </div>
                                                <div class="form-group" id="bg-color">
                                                    <label class="control-label col-xs-3" for="BackgroundColor">Background Color:</label>
                                                    <div class="col-xs-7">
                                                        <div id="BackgroundColor" class="input-group colorpicker-component">
                                                            <input type="text" value="" class="form-control" />
                                                            <span class="input-group-addon"><i></i></span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group" id="bg-image" style="display:none;">
                                                    <label class="control-label col-xs-3" for="BackgroundColor">Background Image:</label>
                                                    <div class="col-xs-9">
                                                        <div class="row">
                                                            <div class="col-xs-6" style='padding:0px'>
                                                                <a href= "javascript:void(0);" class="photoManagerBackground btn btn-default"> Open File Manager </a>
                                                            </div>
                                                            <div class="col-xs-6">
                                                                <input type="hidden" readonly class='form-control' id="bg_image_id">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>


                                                <div class="form-group">
                                                    <label class="control-label col-xs-3" for="CustomeMinHeight">Min Height:</label>
                                                    <div class="col-sm-7">
                                                        <div class="input-group">
                                                            <input type="number" class="form-control" min="0" id="CustomeMinHeight">
                                                            <span class="input-group-addon">px</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">

                                                    <label class="control-label col-xs-3" for="CustomeMarginTop" style="padding-top:30px"> margin :</label>

                                                    <div class="col-sm-7">
                                                        <div class="row">
                                                            <div class="col-xs-12">
                                                                <div class="input-group">
                                                                    <div style="width:25%" class="col-sm-4">Top</div>
                                                                    <div style="width:25%" class="col-sm-4">Bottom</div>
                                                                    <div style="width:25%" class="col-sm-4">Right</div>
                                                                    <div style="width:25%" class="col-sm-4">Left</div>
                                                                    <span class="input-group-addon" style="border:0px;background:none">&nbsp;</span>
                                                                </div>
                                                                <div class="input-group">
                                                                    <input type="number" class="form-control" style="width:25%"  id="CustomeMarginTop">
                                                                    <input type="number" class="form-control" style="width:25%"  id="CustomeMarginBottom">
                                                                    <input type="number" class="form-control" style="width:25%"  id="CustomeMarginRight">
                                                                    <input type="number" class="form-control" style="width:25%"  id="CustomeMarginLeft">
                                                                    <span class="input-group-addon">px</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label class="control-label col-xs-3" for="CustomePaddingLeft"  style="padding-top:30px"> padding :</label>
                                                    <div class="col-sm-7">
                                                        <div class="row">
                                                            <div class="col-xs-12">
                                                                <div class="input-group">
                                                                    <div style="width:25%" class="col-sm-4">Top</div>
                                                                    <div style="width:25%" class="col-sm-4">Bottom</div>
                                                                    <div style="width:25%" class="col-sm-4">Right</div>
                                                                    <div style="width:25%" class="col-sm-4">Left</div>
                                                                    <span class="input-group-addon" style="border:0px;background:none">&nbsp;</span>
                                                                </div>
                                                                <div class="input-group">
                                                                    <input type="number" class="form-control" style="width:25%" min="0" id="CustomePaddingTop">
                                                                    <input type="number" class="form-control" style="width:25%" min="0" id="CustomePaddingBottom">
                                                                    <input type="number" class="form-control" style="width:25%" min="0" id="CustomePaddingRight">
                                                                    <input type="number" class="form-control" style="width:25%" min="0" id="CustomePaddingLeft">
                                                                    <span class="input-group-addon">px</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                        <div role="tabpanel" class="tab-pane" id="settings_dom_ext">
                                            <div class="form-horizontal">
                                                <div class="form-group">
                                                    <label class="col-sm-3 control-label" for="IdSetting">ID</label>
                                                    <div class="col-sm-7">
                                                        <input type="text" class="form-control" id="IdSetting">
                                                    </div>
                                                </div>
                                                <div class="form-group" id="FullRowMode">
                                                    <label class="col-xs-3 control-label" for="BackgroundFull">Full Row:</label>
                                                    <div class="col-xs-7"> <input id="BackgroundFull" class="switch-btn" type="checkbox" value="1" data-on-text=" Yes " data-off-text=" No " data-on-color="danger"></div>
                                                </div>
                                                <div class="form-group" style='display: none; ' id="rowContentMode">
                                                    <label class="col-xs-3 control-label" for="ContentFullRow">Content Is Full:</label>
                                                    <div class="col-xs-7"> <input id="ContentFullRow" class="switch-btn" type="checkbox" value="1" data-on-text=" Yes " data-off-text=" No " data-on-color="danger"></div>
                                                </div>

                                            </div>
                                        </div>
                                        <div role="tabpanel" class="tab-pane" id="visible_cols_dom_ext">
                                            <div class="form-horizontal">
                                                <div class="form-group">
                                                    <label class="col-sm-4 control-label" for="desktop_hidden">Desktop Hide &nbsp;<i class='fa fa-desktop' ></i></label>
                                                    <div class="col-sm-8">
                                                        <input id="desktop_hidden" class="switch-btn" type="checkbox" value="1" data-on-text=" Hide " data-off-text=" Show " data-on-color="danger">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="tablet_hidden" class="col-sm-4 control-label">Tablet Hide &nbsp;<i class='fa fa-tablet'></i></label>
                                                    <div class="col-sm-8">
                                                        <input id="tablet_hidden" class="switch-btn" type="checkbox" value="1" data-on-text=" Hide " data-off-text=" Show " data-on-color="danger">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="mobile_hidden" class="col-sm-4 control-label">Mobile Hide &nbsp; <i class='fa fa-mobile-phone'></i></label>
                                                    <div class="col-sm-8">
                                                        <input id="mobile_hidden" class="switch-btn" type="checkbox" value="1" data-on-text=" Hide " data-off-text=" Show " data-on-color="danger">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" id="CancelBackgroundRow" class="btn btn-default"
                            data-dismiss="modal">cancel</button>
                        <button type="button" id="SaveBackgroundRow" class="btn btn-danger"> save </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Insert Text -->
        <div class="modal fade " id="InsetTextModal" tabindex="-1" role="dialog"
            aria-labelledby="Edit Dynamic Widget">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                        <h3 class="modal-title" id="gridSystemModalLabel">Edit Content</h3>
                    </div>
                    <div class="modal-body">
                        <textarea class="mceEditor" id="EditTextInserted"></textarea>
                    </div>
                    <div class="modal-footer">
                        <button type="button" id="CancelInsertText" class="btn btn-default"
                            data-dismiss="modal">cancel</button>
                        <button type="button" id="InsertText" class="btn btn-danger">save</button>
                    </div>
                </div>

            </div>
        </div>
        <!-- End Modal Insert Text -->

        <!-- Modal Insert Form -->
        <div class="modal fade " id="FormInserting" tabindex="-1" role="dialog" aria-labelledby="Insert Form">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h3 class="modal-title" id="gridSystemModalLabel">form</h3>

                    </div>

                    <div class="modal-body">
                    <p>Please Select a Form</p>
                        <div class='ck-content'>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" id="formID" value='0'>
                        <button type="button" id="CancelInsertForm" class="btn btn-default" data-dismiss="modal">Cancel</button>
                        <button type="button" id="InsertFormBtn" class="btn btn-danger">Save</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Modal Insert Form -->


    </div>
    <!-- modal to go to edit Page -->
    <div class="modal fade" id="myModalToGoEdit" role="dialog">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Save Landing</h4>
                </div>
                <div class="modal-body">
                    <p>Do You Want Save Landing to Complete?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default saveToSelectForm">save</button>
                </div>
            </div>
        </div>
    </div>
    <!--End modal to go to edit Page -->

    <!-- Modal Insert Photo For Builder -->
    <div class="modal fade" id="modalInsertPhoto" >
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                     <h4 class="modal-title">Insert Image</h4>
                </div>
                <div class="modal-body" style="padding:0px; margin:0px; width: 100%;">

                </div>
                <input type="hidden" id="TempImageSelected">
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    <!-- End Modal Insert Photo For Builder -->


     <!-- Modal Insert Photo For Builder -->
     <div class="modal fade" id="modalInsertPhotoForBackgroundRow" >
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                     <h4 class="modal-title">Insert Image</h4>
                </div>
                <div class="modal-body" style="padding:0px; margin:0px; width: 100%;">

                </div>
                <input type="hidden" id="TempImageSelectedForBackgroundRow">
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    <!-- End Modal Insert Photo For Builder -->

    <!-- Modal Insert Photo For Builder -->
    <div class="modal fade" id="modalInsertPhotoForBackgroundLanding" >
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                     <h4 class="modal-title">Insert Image</h4>
                </div>
                <div class="modal-body" style="padding:0px; margin:0px; width: 100%;">

                </div>
                <input type="hidden" id="TempImageSelectedForBackgroundLanding">
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    <!-- End Modal Insert Photo For Builder -->


    <script src="{{ asset('landing_page/vendor1/jquery/jquery.min.js') }}"></script>
    <link href="http://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.2/summernote.css" rel="stylesheet">
    <script src="http://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.2/summernote.js"></script>
    <!-- <script src="{{ asset('vendor1/bootstrap/js/bootstrap.bundle.min.js') }}" defer></script> conflict with open modal grideditor texteditor-->
    <!-- <script src="/js/inc/jquery-1.11.1.min.js" type="text/javascript"></script> -->
    <script src="/landing_page/js/jQueryUI/jquery-ui.min.js" type="text/javascript"></script>
    <script src="/landing_page/js/fancybox/jquery.fancybox-1.3.4_patch.js" type="text/javascript"></script>
    <script src="/landing_page/js/dist/js/bootstrap.min.js" type="text/javascript"></script>
    <script src="/landing_page/js/includes/tinymce/tinymce.min.js" type="text/javascript"></script>
    <script src="/landing_page/js/dist/colorpicker/bootstrap-colorpicker.js" type="text/javascript"></script>
    <script src="/landing_page/js/inc/jquery.grideditor.js" type="text/javascript"></script>
    <script src="/landing_page/js/dist/bootstrap-switch/js/bootstrap-switch.min.js" type="text/javascript"></script>
    <script src='/landing_page/js/dragula.min.js'></script>
    <script src="{{ asset('landing_page/vendor1/scriptLanding.js') }}" defer></script>
    <script src="{!! asset('js/dropzone.js') !!}" type="text/javascript"></script> @section('scripts') @show
    <script src="/landing_page/js/inc/landing-page-builder.js" type="text/javascript"></script>    
    
</body>

</html>
