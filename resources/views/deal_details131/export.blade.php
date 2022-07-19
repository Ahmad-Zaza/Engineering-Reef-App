<html dir="rtl">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<style>
    * {
        /* font-family: DejaVu Sans, sans-serif; */
        direction: rtl;
        font-size: 12px;
        box-sizing: border-box;
    }
    .main td {
        text-align: center;
        border: 0.7px solid #494444;
        vertical-align: middle;
    }

    .main th {
        font-family: arialbd;
        text-align: center;
        border: 0.7px solid #494444;
        vertical-align: middle;
    }
</style>
@php

@endphp
@if (count($result) == 0)
    <h3>
        لايوجد بيانات متوفرة
    </h3>
@else

    <body>
        <table cellpadding="2" cellspacing="4" border="0" style="border-collapse: collapse;background-color: #9b9999;"
            width="100%">
            <tbody>
                <tr nobr="true" style="">
                    <td width="22%" style="font-family:arialbd;">
                        إقامات الدراسات غير المسددة للمهندس:</td>
                    <td width="10%" style="border: 0.7px solid #494444;font-family:arialbd;background-color:white;">
                        &nbsp;{{ $result[0]->cms_users_num }}&nbsp;
                    </td>
                    <td width="36.5%" style="border: 0.7px solid #494444;font-family:arialbd;background-color:white;">
                        {{ $result[0]->cms_users_name }}
                    </td>
                    <td width="10.5%"></td>
                    <td width="7%" style="font-family:arialbd;">حتى الشهر:</td>
                    <td width="4%" align="center" style="font-family:arialbd;border: 0.7px solid #494444;background-color:white;">
                        {{ $month }}
                    </td>
                    <td width="4%" style="font-family:arialbd;">السنة:</td>
                    <td width="6%" align="center" style="font-family:arialbd;border: 0.7px solid #494444;background-color:white;">
                        {{ $year }}
                    </td>
                </tr>
            </tbody>
        </table>
        <table>
            <thead>
                <tr>
                    <td></td>
                </tr>

            </thead>
        </table>
        <table class="main" cellpadding="2" border="1" style="border-collapse: collapse;"
            width="100%">
            <thead>
                <tr style="background-color: #9b9999;">
                    <th>رقم المعاملة</th>
                    <th>تاريخ المعاملة</th>
                    {{-- <th>رقم المهندس</th> --}}
                    {{-- <th>اسم المهندس</th> --}}
                    <th>المبلغ</th>
                    <th>مالك العقار</th>
                    <th>المنطقة العقارية</th>
                    <th>أرقام العقار</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($result as $row)
                    @php
                        $style = '';
                        if ($loop->iteration % 2 == 0) {
                            $style="background-color: #9b9999;";
                        }
                    @endphp
                    <tr style="{{ $style }}">
                        <td>{{ $row->deals_file_num }}</td>
                        <td>{{ $row->deals_file_date }}</td>
                        {{-- <td>{{ $row->cms_users_num }}</td> --}}
                        {{-- <td>{{ $row->cms_users_name }}</td> --}}
                        <td>{{ $row->study_resident_value }}</td>
                        <td>{{ $row->deals_owner_name }}</td>
                        <td>{{ $row->deals_real_estate_area }}</td>
                        <td>{{ $row->deals_real_estate_num }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

    </body>
@endif

</html>
