@extends('crudbooster::admin_template')

@section('content')
    @if ($index_statistic)
        <div id='box-statistic' class='row'>
            @foreach ($index_statistic as $stat)
                <div class="{{ $stat['width'] ?: 'col-sm-3' }}">
                    <div class="small-box bg-{{ $stat['color'] ?: 'red' }}">
                        <div class="inner">
                            <h3>{{ $stat['count'] }}</h3>
                            <p>{{ $stat['label'] }}</p>
                        </div>
                        <div class="icon">
                            <i class="{{ $stat['icon'] }}"></i>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    @if (!is_null($pre_index_html) && !empty($pre_index_html))
        {!! $pre_index_html !!}
    @endif


    @if (g('return_url'))
        <p><a href='{{ g('return_url') }}'><i class='fa fa-chevron-circle-{{ trans('crudbooster.left') }}'></i>
                &nbsp; {{ trans('crudbooster.form_back_to_list', ['module' => urldecode(g('label'))]) }}</a></p>
    @endif

    @if ($parent_table)
        <div class="box box-default">
            <div class="box-body table-responsive no-padding">
                <table class='table table-bordered'>
                    <tbody>
                        <tr class='active'>
                            <td colspan="2"><strong><i class='fa fa-bars'></i>
                                    {{ ucwords(urldecode(g('label'))) }}</strong></td>
                        </tr>
                        @foreach (explode(',', urldecode(g('parent_columns'))) as $c)
                            <tr>
                                <td width="25%"><strong>
                                        @if (urldecode(g('parent_columns_alias')))
                                            {{ explode(',', urldecode(g('parent_columns_alias')))[$loop->index] }}
                                        @else
                                            {{ ucwords(str_replace('_', ' ', $c)) }}
                                        @endif
                                    </strong></td>
                                <td> {{ $parent_table->$c }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    <div class="box">
        <div class="box-header">
            @if ($button_bulk_action && (($button_delete && CRUDBooster::isDelete()) || $button_selected))
                <div class="pull-{{ trans('crudbooster.left') }}">
                    <div class="selected-action" style="display:inline-block;position:relative;">
                        <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown"
                            aria-expanded="false"><i class='fa fa-check-square-o'></i>
                            {{ trans('crudbooster.button_selected_action') }}
                            <span class="fa fa-caret-down"></span></button>
                        <ul class="dropdown-menu">
                            @if ($button_delete && CRUDBooster::isDelete())
                                <li><a href="javascript:void(0)" data-name='delete'
                                        title='{{ trans('crudbooster.action_delete_selected') }}'><i
                                            class="fa fa-trash"></i>
                                        {{ trans('crudbooster.action_delete_selected') }}</a></li>
                            @endif

                            {{-- fesal --}}
                            <li><a href="javascript:void(0)" data-name='is_active' title='تفعيل'><i
                                        class="fa fa-check"></i>تفعيل</a></li>

                            <li><a href="javascript:void(0)" data-name='is_in_active' title='إلغاء تفعيل'><i
                                        class="fa fa-close"></i>إلغاء تفعيل</a></li>
                            @if ($button_selected)
                                @foreach ($button_selected as $button)
                                    <li><a href="javascript:void(0)" data-name='{{ $button['name'] }}'
                                            title='{{ $button['label'] }}'><i class="fa fa-{{ $button['icon'] }}"></i>
                                            {{ $button['label'] }}</a></li>
                                @endforeach
                            @endif

                        </ul>
                        <!--end-dropdown-menu-->
                    </div>
                    <!--end-selected-action-->
                </div>
                <!--end-pull-left-->
            @endif
            <div class="box-tools pull-{{ trans('crudbooster.right') }}"
                style="position: relative;margin-top: -5px;margin-right: -10px;display:flex">

                <form method='get' id="table_filter_form" style="display:flex;" action='{{ Request::url() }}'>
                    @if (Crudbooster::me()->id_cms_privileges == 1)
                        <div class="input-group" style="width: 170px">
                            <input type="text" name="engineer" value="{{ Request::get('engineer') }}"
                                class="form-control input-sm pull-{{ trans('crudbooster.right') }}"
                                placeholder="رقم مهندس المعاملة" />
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
                                        title="{{ trans('crudbooster.button_reset') }}"
                                        class='btn btn-sm btn-warning'><i class='fa fa-ban'></i></button>
                                @endif
                            </div>
                        </div>
                    @endif

                    <div class="input-group" style="width: 170px">
                        @php
                            $months = Db::table('paid_deals')
                                ->distinct('month')
                                ->select('month')
                                ->orderby('month')
                                ->get()
                                ->pluck('month');
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
                            $years = Db::table('paid_deals')
                                ->distinct('year')
                                ->select('year')
                                ->orderby('year')
                                ->get()
                                ->pluck('year');
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

                <form method='get' id='form-limit-paging' style="display:inline-block" action='{{ Request::url() }}'>
                    {!! CRUDBooster::getUrlParameters(['limit']) !!}
                    <div class="input-group">
                        <select onchange="$('#form-limit-paging').submit()" name='limit' style="width: 56px;"
                            class='form-control input-sm'>
                            <option {{ $limit == 5 ? 'selected' : '' }} value='5'>5</option>
                            <option {{ $limit == 10 ? 'selected' : '' }} value='10'>10</option>
                            <option {{ $limit == 20 ? 'selected' : '' }} value='20'>20</option>
                            <option {{ $limit == 25 ? 'selected' : '' }} value='25'>25</option>
                            <option {{ $limit == 50 ? 'selected' : '' }} value='50'>50</option>
                            <option {{ $limit == 100 ? 'selected' : '' }} value='100'>100</option>
                            <option {{ $limit == 200 ? 'selected' : '' }} value='200'>200</option>
                        </select>
                    </div>
                </form>

            </div>

            <br style="clear:both" />

        </div>
        <div class="box-body table-responsive no-padding">
            @if (view()->exists(CrudBooster::getCurrentModule()->path . '.table'))
                @include(CrudBooster::getCurrentModule()->path . '.table')
            @else
                @include('crudbooster::default.table')
            @endif

        </div>
    </div>

    @if (!is_null($post_index_html) && !empty($post_index_html))
        {!! $post_index_html !!}
    @endif

@endsection
