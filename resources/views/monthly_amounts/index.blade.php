@extends('crudbooster::admin_template')

@section('content')
    <div class="box">
        <div class="box-header">
            <div class="box-tools pull-{{ trans('crudbooster.right') }}"
                style="position: relative;margin-top: -5px;margin-right: -10px;display:flex">

                <form method='get' id="table_filter_form" style="display:flex;" action='{{ Request::url() }}'>
                    @if (Crudbooster::me()->id_cms_privileges == 1)
                        <div class="input-group" style="width: 170px">
                            <input type="text" name="engineer" value="{{ Request::get('engineer') }}"
                                class="form-control input-sm pull-{{ trans('crudbooster.right') }}"
                                placeholder="رقم المهندس" />
                            <div class="input-group-btn">
                                @if (Request::get('engineer'))
                                    <?php
                                    $parameters = Request::all();
                                    unset($parameters['engineer']);
                                    $build_query = urldecode(http_build_query($parameters));
                                    $build_query = $build_query ? '?' . $build_query : '';
                                    $build_query = Request::all() ? $build_query : '';
                                    ?>
                                    <button type='button'
                                        onclick='location.href="{{ CRUDBooster::mainpath() . $build_query }}"'
                                        title="{{ trans('crudbooster.button_reset') }}" class='btn btn-sm btn-warning'><i
                                            class='fa fa-ban'></i></button>
                                @endif
                            </div>
                        </div>
                    @endif

                    <div class="input-group" style="width: 170px">
                        @php
                            $months = Db::table('financial_deals')
                                ->distinct('financial_month')
                                ->select('financial_month')
                                ->orderby('financial_month')
                                ->get()
                                ->pluck('financial_month');
                        @endphp
                        <select name="month" id="month"
                            class="form-control input-sm pull-{{ trans('crudbooster.right') }}">
                            <option value="">الرجاء اختيار شهر</option>
                            @foreach ($months as $month)
                                @if (Request::get('month') && $month == Request::get('month'))
                                    <option selected value="{{ $month }}">{{ $month }}</option>
                                @else
                                    <option value="{{ $month }}">{{ $month }}</option>
                                @endif
                            @endforeach
                        </select>

                        <div class="input-group-btn">
                            @if (Request::get('month'))
                                <?php
                                $parameters = Request::all();
                                unset($parameters['month']);
                                $build_query = urldecode(http_build_query($parameters));
                                $build_query = $build_query ? '?' . $build_query : '';
                                $build_query = Request::all() ? $build_query : '';
                                ?>
                                <button type='button'
                                    onclick='location.href="{{ CRUDBooster::mainpath() . $build_query }}"'
                                    title="{{ trans('crudbooster.button_reset') }}" class='btn btn-sm btn-warning'><i
                                        class='fa fa-ban'></i></button>
                            @endif
                        </div>
                    </div>
                    <div class="input-group" style="width: 170px">
                        @php
                            $years = Db::table('financial_deals')
                                ->distinct('financial_year')
                                ->select('financial_year')
                                ->get()
                                ->pluck('financial_year');
                        @endphp
                        <select name="year" id="year"
                            class="form-control input-sm pull-{{ trans('crudbooster.right') }}">
                            <option value="">الرجاء اختيار سنة</option>
                            @foreach ($years as $year)
                                @if (Request::get('year') && $year == Request::get('year'))
                                    <option selected value="{{ $year }}">{{ $year }}</option>
                                @else
                                    <option value="{{ $year }}">{{ $year }}</option>
                                @endif
                            @endforeach
                        </select>
                        <div class="input-group-btn">
                            @if (Request::get('year'))
                                <?php
                                $parameters = Request::all();
                                unset($parameters['year']);
                                $build_query = urldecode(http_build_query($parameters));
                                $build_query = $build_query ? '?' . $build_query : '';
                                $build_query = Request::all() ? $build_query : '';
                                ?>
                                <button type='button'
                                    onclick='location.href="{{ CRUDBooster::mainpath() . $build_query }}"'
                                    title="{{ trans('crudbooster.button_reset') }}" class='btn btn-sm btn-warning'><i
                                        class='fa fa-ban'></i></button>
                            @endif
                        </div>
                    </div>
                    <button type='submit' class="btn btn-sm btn-default" style="margin-left: 3px;"><i
                            class="fa fa-search"></i></button>
                </form>
            </div>

            <br style="clear:both" />

        </div>
        <div class="box-body table-responsive no-padding">
            @if ((!Request::get('year') || !Request::get('month') || !Request::get('engineer')) && CrudBooster::me()->id_cms_privileges == 1)
                <div class="bg-info text-center" style="padding: 10px;margin: 17px;">
                    الرجاء اختيار السنة و الشهر و المهندس
                </div>
            @elseif(!Request::get('year') && !Request::get('month') && CrudBooster::me()->id_cms_privileges == 2)
                <div class="bg-info text-center" style="padding: 10px;margin: 17px;">
                    الرجاء اختيار السنة أو الشهر
                </div>
            @elseif (count($result) == 0)
                <div class="bg-info text-center" style="padding: 10px;margin: 17px;">
                    {{ trans('crudbooster.table_data_not_found') }}
                </div>
            @else
                <div class="panel panel-default" style="margin:22px;">
                    @php
                        $row = $result[0];
                        Session::put('current_row_id', $result[0]->id);
                    @endphp
                    <div class="panel-heading">
                        <strong>تفاصيل القبض الشهري للمهندس: 
                            <span>{{ $row->cms_users_name . ' - ' . $row->cms_users_num }}</span></strong>
                    </div>

                    <div class="panel-body" style="padding:20px 0px 0px 0px">
                        @include('crudbooster::default.form_detail')
                    </div>
                </div>
            @endif
        </div>
        {{-- <div class="box-body table-responsive no-padding">
            @if (view()->exists(CrudBooster::getCurrentModule()->path . '.table'))
                @include(CrudBooster::getCurrentModule()->path . '.table')
            @else
                @include('crudbooster::default.table')
            @endif
        </div> --}}
    </div>

    <!-- MODAL FOR EXPORT DATA-->
    <div class="modal fade" tabindex="-1" role="dialog" id='export-data'>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button class="close" aria-label="Close" type="button" data-dismiss="modal">
                        <span aria-hidden="true">×</span></button>
                    <h4 class="modal-title"><i class='fa fa-download'></i>
                        {{ trans('crudbooster.export_dialog_title') }}</h4>
                </div>

                <form method='post' id='export-data-post' target="_blank"
                    action='{{ CRUDBooster::mainpath('export-data?t=' . time()) }}'>
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    {!! CRUDBooster::getUrlParameters() !!}
                    <div class="modal-body">
                        <div class="form-group">
                            <label>{{ trans('crudbooster.export_dialog_filename') }}</label>
                            <input type='text' name='filename' class='form-control' required
                                value='Report {{ $module_name }} - {{ date('d M Y') }}' />
                            <div class='help-block'>
                                {{ trans('crudbooster.export_dialog_help_filename') }}
                            </div>
                        </div>

                        <div class="form-group hide">
                            <label>{{ trans('crudbooster.export_dialog_maxdata') }}</label>
                            <input type='number' name='limit' class='form-control' required value='100' max="100000"
                                min="1" />
                            <div class='help-block'>{{ trans('crudbooster.export_dialog_help_maxdata') }}</div>
                        </div>

                        <div class='form-group'>
                            <label>{{ trans('crudbooster.export_dialog_columns') }}</label><br />
                            @foreach ($columns as $col)
                                <div class='checkbox inline'><label><input type='checkbox' checked name='columns[]'
                                            value='{{ $col['name'] }}'>{{ $col['label'] }}</label></div>
                            @endforeach
                            <div class='checkbox inline'><label><input type='checkbox' checked name='columns[]'
                                        value='studies'>تفاصيل الدراسات</label></div>
                        </div>

                        <div class="form-group">
                            <label>{{ trans('crudbooster.export_dialog_format_export') }}</label>
                            <select name='fileformat' class='form-control'>
                                <option value='pdf'>PDF</option>
                                <option value='csv'>CSV</option>
                            </select>
                        </div>

                        <p class="hide">
                            <a href='javascript:void(0)' class='toggle_advanced_report'><i
                                    class='fa fa-plus-square-o'></i>
                                {{ trans('crudbooster.export_dialog_show_advanced') }}</a>
                        </p>

                        <div id='advanced_export' style='display: none'>


                            <div class="form-group">
                                <label>{{ trans('crudbooster.export_dialog_page_size') }}</label>
                                <select class='form-control' name='page_size'>
                                    <option value="A4">A4</option>
                                </select>
                                <div class='help-block'><input type='checkbox' name='default_paper_size'
                                        value='1' />
                                    {{ trans('crudbooster.export_dialog_set_default') }}</div>
                            </div>

                            <div class="form-group">
                                <label>{{ trans('crudbooster.export_dialog_page_orientation') }}</label>
                                <select class='form-control' name='page_orientation'>
                                    <option value='potrait'>Potrait</option>
                                    <option selected value='landscape'>Landscape</option>
                                </select>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer" align="right">
                        <button class="btn btn-default" type="button"
                            data-dismiss="modal">{{ trans('crudbooster.button_close') }}</button>
                        <button class="btn btn-primary btn-submit" type="submit">تصدير</button>
                    </div>
                </form>
            </div>
            <!-- /.modal-content -->
        </div>
    </div>
@endsection

@push('bottom')
    <script>
        $(function() {
            $("#btn_export_data").click(function() {
                $("#export-data-post").submit();
            });

            $("#month,#year").change(function() {
                $("#table_filter_form").submit();
            });
        })
    </script>
@endpush