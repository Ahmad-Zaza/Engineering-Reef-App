<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<style>
    @font-face {
        font-family: 'DejaVu Sans';
        font-style: normal;
        font-weight: normal;
        src: url('/fonts/DejaVuSans/DejaVuSans.ttf') format('truetype');
    }

    * {
        font-family: DejaVu Sans, sans-serif;
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
                <td>#</td>
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
                        <td>{{$loop->iteration}}</td>
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

</body>
<script type="text/php">if ( isset($pdf) ) {
    $font = Font_Metrics::get_font("courier", "bold");
    $pdf->page_text(36, 18, "Page {PAGE_NUM} of {PAGE_COUNT}", $font, 6, array(0,0,0));
}</script>

</html>
