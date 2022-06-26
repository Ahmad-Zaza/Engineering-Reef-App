<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<style>
    @font-face {
        font-family: 'DejaVu Sans';
        font-style: normal;
        font-weight: normal;
        /* src: storage_path('fonts/DejaVuSans.ttf') format('truetype'); */
        src: url('/fonts/DejaVuSans/DejaVuSans.ttf') format('truetype');
    }

    * {
        font-family:  DejaVu Sans, sans-serif;
        direction: rtl;
    }

    /* @font-face {
        font-family: 'Droid Arabic Kufi';
        src: url('/fonts/DroidKufi/DroidArabicKufi.woff2') format('woff2'),
            url('/fonts/DroidKufi/DroidArabicKufi.woff') format('woff');
        font-weight: normal;
        font-style: normal;
        font-display: swap;
    }

    * {
        font-family: "Droid Arabic Kufi";
        direction: rtl;
    } */

</style>

<body>
    @if (Request::input('fileformat') == 'pdf')
        <h3>{{ Request::input('filename') }}</h3>
    @endif
    <style>
    </style>

    <table border='1' width='100%' cellpadding='3' cellspacing="0" style='border-collapse: collapse;font-size:12px'>
        <thead>
            <tr>
                <?php
                foreach ($columns as $col) {
                    if (Request::get('columns')) {
                        if (!in_array($col['name'], Request::get('columns'))) {
                            continue;
                        }
                    }
                    $colname = $col['label'];
                    echo "<th style='background:#eeeeee'>$colname</th>";
                }
                ?>
            </tr>
        </thead>
        <tbody>
            @if (count($result) == 0)
                <tr class='warning'>
                    <td colspan='{{ count($columns) + 1 }}' align="center">No Data Avaliable</td>
                </tr>
            @else
                @foreach ($result as $row)
                    <tr>
                        <?php
                        foreach ($columns as $col) {
                            if (Request::get('columns')) {
                                if (!in_array($col['name'], Request::get('columns'))) {
                                    continue;
                                }
                            }
                        
                            $value = @$row->{$col['field']};
                            $title = @$row->{$title_field};
                        
                            if (@$col['image']) {
                                if ($value == '') {
                                    $value = 'http://placehold.it/50x50&text=NO+IMAGE';
                                }
                                $pic = strpos($value, 'http://') !== false ? $value : asset($value);
                                $pic_small = $pic;
                                if (Request::input('fileformat') == 'pdf') {
                                    echo "<td><a data-lightbox='roadtrip' rel='group_{{$table}}' title='$col[label]: $title' href='" . $pic . "'><img class='img-circle' width='40px' height='40px' src='" . $pic_small . "'/></a></td>";
                                } else {
                                    echo "<td>$pic</td>";
                                }
                            } elseif (@$col['download']) {
                                $url = strpos($value, 'http://') !== false ? $value : asset($value);
                                echo "<td><a class='btn btn-sm btn-primary' href='$url' target='_blank' title='Download File'>Download</a></td>";
                            } else {
                                //limit character
                                if ($col['str_limit']) {
                                    $value = trim(strip_tags($value));
                                    $value = str_limit($value, $col['str_limit']);
                                }
                        
                                if ($col['nl2br']) {
                                    $value = nl2br($value);
                                }
                        
                                if (Request::input('fileformat') == 'pdf') {
                                    if (!empty($col['callback_php'])) {
                                        foreach ($row as $k => $v) {
                                            $col['callback_php'] = str_replace('[' . $k . ']', $v, $col['callback_php']);
                                        }
                                        @eval("\$value = " . $col['callback_php'] . ';');
                                    }
                        
                                    //New method for callback
                                    if (isset($col['callback'])) {
                                        $value = call_user_func($col['callback'], $row);
                                    }
                                }
                        
                                echo '<td>' . $value . '</td>';
                            }
                        }
                        ?>
                    </tr>
                @endforeach
            @endif
        </tbody>
    </table>
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

                    <form method='post' target="_blank" action='{{ CRUDBooster::mainpath('export-data?t=' . time()) }}'>
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

                            <div class="form-group">
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
                            </div>

                            <div class="form-group">
                                <label>{{ trans('crudbooster.export_dialog_format_export') }}</label>
                                <select name='fileformat' class='form-control'>
                                    <option value='pdf'>PDF</option>
                                    <option value='csv'>CSV</option>
                                </select>
                            </div>

                            <p><a href='javascript:void(0)' class='toggle_advanced_report'><i
                                        class='fa fa-plus-square-o'></i>
                                    {{ trans('crudbooster.export_dialog_show_advanced') }}</a></p>

                            <div id='advanced_export' style='display: none'>


                                <div class="form-group">
                                    <label>{{ trans('crudbooster.export_dialog_page_size') }}</label>
                                    <select class='form-control' name='page_size'>
                                        <option <?= $setting->default_paper_size == 'Letter' ? 'selected' : '' ?>
                                            value='Letter'>Letter</option>
                                        <option <?= $setting->default_paper_size == 'Legal' ? 'selected' : '' ?>
                                            value='Legal'>Legal</option>
                                        <option <?= $setting->default_paper_size == 'Ledger' ? 'selected' : '' ?>
                                            value='Ledger'>Ledger</option>
                                        <?php for ($i = 0; $i <= 8; $i++):
    $select = ($setting->default_paper_size == 'A' . $i) ? "selected" : "";
    ?>
                                        <option <?= $select ?> value='A{{ $i }}'>A{{ $i }}
                                        </option>
                                        <?php endfor;?>

                                        <?php for ($i = 0; $i <= 10; $i++):
    $select = ($setting->default_paper_size == 'B' . $i) ? "selected" : "";
    ?>
                                        <option <?= $select ?> value='B{{ $i }}'>B{{ $i }}
                                        </option>
                                        <?php endfor;?>
                                    </select>
                                    <div class='help-block'><input type='checkbox' name='default_paper_size' value='1' />
                                        {{ trans('crudbooster.export_dialog_set_default') }}</div>
                                </div>

                                <div class="form-group">
                                    <label>{{ trans('crudbooster.export_dialog_page_orientation') }}</label>
                                    <select class='form-control' name='page_orientation'>
                                        <option value='potrait'>Potrait</option>
                                        <option value='landscape'>Landscape</option>
                                    </select>
                                </div>
                            </div>

                        </div>
                        <div class="modal-footer" align="right">
                            <button class="btn btn-default" type="button"
                                data-dismiss="modal">{{ trans('crudbooster.button_close') }}</button>
                            <button class="btn btn-primary btn-submit"
                                type="submit">{{ trans('crudbooster.button_submit') }}</button>
                        </div>
                    </form>
                </div>
                <!-- /.modal-content -->
            </div>
        </div>
</body>
<script type="text/php">if ( isset($pdf) ) {
    $font = Font_Metrics::get_font("courier", "bold");
    $pdf->page_text(36, 18, "Page {PAGE_NUM} of {PAGE_COUNT}", $font, 6, array(0,0,0));
}</script>

</html>
