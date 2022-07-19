<html dir="rtl">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<style>

    * {
        /* font-family: DejaVu Sans, sans-serif; */
        direction: rtl;
        font-size: 10px;
        box-sizing: border-box;
    }
    .num{
        font-family: DejaVu Sans, sans-serif;
        font-size: 10px;
    }
</style>
@php

@endphp
@if(count($result) == 0)
<h3>
    لايوجد بيانات متوفرة
</h3>
@else
<body>
    <h3>
        تفاصيل الدراسات للمهندس
        <span>- {{ $result[0]->deal_details[0]->num }} :</span>
        <span>{{ $result[0]->deal_details[0]->name }}</span>
        <span>&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;</span>
        الكوتا
        <span>{{ $result[0]->deal_details[0]->cota }}:</span>
    </h3>
    <table cellpadding="2" border="0" style="border-collapse: collapse;font-size:8px" width="100%">
        <tbody>
            <tr nobr="true">
                @if (Request::has('close_year') && Request::has('close_month'))
                    <td border="0" style="font-size:10px;background-color: #9b9999;font-weight: bold">
                        {{ $result[0]->close_year . ' | ' . $result[0]->close_month }}
                    </td>
                @else
                    <td></td>
                @endif
                <td></td>
                <td></td>
                <td></td>
                <td border="1" style="background-color: #9b9999;">المجموع الكلي</td>
                <td border="1" style="background-color: #9b9999;">{{ $total['total_study_sum'] }}</td>
                <td border="1" style="background-color: #9b9999;">{{ $total['total_file_sum'] }}</td>
                <td border="1" style="background-color: #9b9999;">{{ $total['total_resident_sum'] }}</td>
            </tr>
        </tbody>
    </table>
    <table cellpadding="2" border="0" style="border-collapse: collapse;font-size:8px" width="100%">
        <tbody>
            @foreach ($result as $row)
                <tr>
                    <td border="0" style="border-bottom-width: 0.1px"></td>
                    <td border="0" style="border-bottom-width: 0.1px"></td>
                    <td border="0" style="border-bottom-width: 0.1px"></td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td width="47%">
                        <table cellpadding="3" border="0" style="border-collapse: collapse;font-size:8px" width="100%">
                            <tbody>
                                <tr nobr="true">
                                    <td>المعاملة</td>
                                    <td border="1" style="background-color: #9b9999;">{{ $row->file_num }}</td>
                                    <td border="1" style="background-color: #9b9999;">{{ $row->file_date }}</td>
                                    <td border="1">{{ $row->file_type }}</td>
                                    <td>مذكرة</td>
                                    <td border="1">
                                        {{ $row->note_num && $row->note_date ? $row->note_num . '-' . ($row->note_date ?: '') : '' }}
                                    </td>
                                </tr>
                                <tr border="0">
                                    <td></td>
                                </tr>
                                <tr nobr="true">
                                    <td>المنطقة العقارية</td>
                                    <td border="1">{{ $row->real_estate_area }}</td>
                                    <td>منطقة الحصر</td>
                                    <td border="1">{{ $row->confinement_area }}</td>
                                    <td>مالك العقار</td>
                                    <td border="1">{{ $row->owner_name }}</td>
                                </tr>
                                <tr border="0">
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>مهندس المعاملة</td>
                                    <td border="1">{{ $row->file_user_num }}</td>
                                    <td border="1">{{ $row->file_user_name }}</td>
                                    <td>حالة المعاملة</td>
                                    <td border="1">{{ $row->file_status }}</td>
                                </tr>
                                <tr border="0">
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>أرقام العقارات</td>
                                    <td border="1">{{ $row->real_estate_num }}</td>
                                </tr>

                            </tbody>
                        </table>
                    </td>
                    <td width="4%"></td>
                    <td width="47%">
                        <table cellpadding="3" style="border-collapse: collapse;font-size:8px" width="100%">
                            <tbody>
                                <tr nobr="true">
                                    <td border="1" style="background-color: #9b9999;">اسم الدراسة</td>
                                    <td border="1" style="background-color: #9b9999;">دراسة</td>
                                    <td border="1" style="background-color: #9b9999;">إضبارة</td>
                                    <td border="1" style="background-color: #9b9999;">مقيم</td>
                                </tr>

                                @foreach ($row->deal_details as $row1)
                                    <tr nobr="true">
                                        <td border="1">{{ $row1->study_name }}</td>
                                        <td border="1">{{ $row1->study_value }}</td>
                                        <td border="1">{{ $row1->study_file_value }}</td>
                                        <td border="1">{{ $row1->study_resident_value }}</td>
                                    </tr>
                                @endforeach
                                <tr>
                                    <td border="0"></td>
                                </tr>
                                <tr nobr="true">
                                    <td border="1" style="background-color: #9b9999;">مجموع المعاملة</td>
                                    <td border="1" style="background-color: #9b9999;">{{ $row->file_study_sum }}</td>
                                    <td border="1" style="background-color: #9b9999;">{{ $row->file_file_sum }}</td>
                                    <td border="1" style="background-color: #9b9999;">{{ $row->file_resident_sum }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>


</body>
@endif
</html>