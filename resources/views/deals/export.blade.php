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

    tr {
        height: 1px;
    }

    td {
        height: inherit;
    }

    td span {
        height: 100%;
        width: 100%;
        display: block;
    }
    .all-border{
        border: 0.7px solid #494444;
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
        <table cellpadding="2" cellspacing="4" border="0" style="border-collapse: collapse;background-color: #f4f4f4;"
            width="100%">
            <tbody>
                <tr nobr="true" style="">
                    <td width="16%" style="font-family:arialbd;">
                        تفاصيل الدراسات للمهندس:</td>
                    <td width="8%" style="border: 0.7px solid #494444;font-family:arialbd;background-color:white;">
                        {{ $result[0]->deal_details[0]->num }}
                    </td>
                    <td width="28%" style="border: 0.7px solid #494444;font-family:arialbd;background-color:white;">
                        {{ $result[0]->deal_details[0]->name }}
                    </td>
                    <td width="5%" style="font-family:arialbd;">الكوتا:</td>
                    <td width="20%" style="font-family:arialbd;border: 0.7px solid #494444;background-color:white;">
                        {{ $result[0]->deal_details[0]->cota }}
                    </td>
                    <td width="23%"></td>
                </tr>
                
            </tbody>
        </table>
        <table>
            <tbody>
                <tr>
                    <td style="line-height: 5px;" colspan="6"></td>
                </tr>
            </tbody>
        </table>

        <table cellpadding="2" border="0" style="border-collapse: collapse;" width="100%">
            <tbody>
                <tr nobr="true">
                    <td width="5%" align="center" style="background-color: #f4f4f4;font-family:arialbd;">
                        {{ $result[0]->close_month }}
                    </td>
                    <td width="7%" align="center" style="background-color: #f4f4f4;font-family:arialbd;">
                        {{ $result[0]->close_year }}
                    </td>
                    <td width="20%"></td>
                    <td width="20%"></td>
                    <td width="12%" align="center" class="all-border" style="background-color: #f4f4f4;font-family:arialbd;">المجموع الشهري</td>
                    <td width="12%" align="center" style="border: 0.7px solid #494444;background-color: #f4f4f4;font-family:arialbd;">
                        {{ $total['total_study_sum'] }}</td>
                    <td width="12%" align="center" style="border: 0.7px solid #494444;background-color: #f4f4f4;font-family:arialbd;">
                        {{ $total['total_file_sum'] }}</td>
                    <td width="12%" align="center" style="border: 0.7px solid #494444;background-color: #f4f4f4;font-family:arialbd;">
                        {{ $total['total_resident_sum'] }}</td>
                </tr>
                <tr>
                    <td style="line-height: 1px;" colspan="8"></td>
                </tr>
            </tbody>
        </table>
        <table cellpadding="2" border="0" style="border-collapse: collapse;" width="100%">
            <tbody>
                @foreach ($result as $row)
                    <table cellpadding="2" cellspacing="7" border="0" style="border-collapse: collapse;border: 0.7px solid #494444;" width="100%">
                        <tbody>
                            <tr>
                                <td style="line-height: 0px;" colspan="8" border="0"></td>
                            </tr>
                            <tr nobr="true">
                                <td style="font-family:arialbd;">رقم المعاملة</td>
                                <td style="border: 0.7px solid #494444;background-color: #f4f4f4;font-family:arialbd;">{{ $row->file_num }}
                                </td>
                                <td style="font-family:arialbd;">تاريخ المعاملة</td>
                                <td  style="border: 0.7px solid #494444;background-color: #f4f4f4;font-family:arialbd;">{{ $row->file_date }}
                                </td>
                                <td style="font-family:arialbd;">نوع المعاملة</td>
                                <td  style="border: 0.7px solid #494444;font-family:arialbd;">{{ $row->file_type }}</td>
                                <td style="font-family:arialbd;">مذكرة</td>
                                <td  style="border: 0.7px solid #494444;">
                                    {{ $row->note_num && $row->note_date ? $row->note_num . '-' . ($row->note_date ?: '') : '' }}
                                </td>
                            </tr>
                            <tr nobr="true">
                                <td style="font-family:arialbd;">المنطقة العقارية</td>
                                <td  style="border: 0.7px solid #494444;">{{ $row->real_estate_area }}</td>
                                <td style="font-family:arialbd;">منطقة الحصر</td>
                                <td  style="border: 0.7px solid #494444;">{{ $row->confinement_area }}</td>
                                <td style="font-family:arialbd;">مالك العقار</td>
                                <td colspan="3" style="border: 0.7px solid #494444;">{{ $row->owner_name }}</td>
                            </tr>
                            <tr nobr="true">
                                <td style="font-family:arialbd;">مهندس المعاملة</td>
                                <td  style="border: 0.7px solid #494444;">{{ $row->file_user_num }}</td>
                                <td colspan="2" style="border: 0.7px solid #494444;">{{ $row->file_user_name }}</td>
                                <td style="font-family:arialbd;">حالة المعاملة</td>
                                <td  style="border: 0.7px solid #494444;">{{ $row->file_status }}</td>
                                <td style="font-family:arialbd;">أرقام العقارات</td>
                                <td  style="border: 0.7px solid #494444;">{{ $row->real_estate_num }}</td>
                            </tr>
                            <tr>
                                <td colspan="8" align="center" style="text-align:center;">
                                    <table cellpadding="3" style="border-collapse: collapse;margin:auto;" width="80%">
                                        <tbody>
                                            <tr>
                                                <td style="line-height: 0px;" colspan="4" border="0"></td>
                                            </tr>
                                            <tr>
                                                <td style="font-family:arialbd;border: 0.7px solid #494444;background-color: #f4f4f4;" align="center" colspan="4">الدراسات</td>
                                            </tr>
                                            <tr nobr="true">
                                                <td style="font-family:arialbd;border: 0.7px solid #494444;background-color: #f4f4f4;">اسم الدراسة</td>
                                                <td style="font-family:arialbd;border: 0.7px solid #494444;background-color: #f4f4f4;">دراسة</td>
                                                <td style="font-family:arialbd;border: 0.7px solid #494444;background-color: #f4f4f4;">إضبارة</td>
                                                <td style="font-family:arialbd;border: 0.7px solid #494444;background-color: #f4f4f4;">مقيم</td>
                                            </tr>
            
                                            @foreach ($row->deal_details as $row1)
                                                <tr nobr="true">
                                                    <td style="border: 0.7px solid #494444;">{{ $row1->study_name }}</td>
                                                    <td style="border: 0.7px solid #494444;">{{ $row1->study_value }}</td>
                                                    <td style="border: 0.7px solid #494444;">{{ $row1->study_file_value }}</td>
                                                    <td style="border: 0.7px solid #494444;">{{ $row1->study_resident_value }}</td>
                                                </tr>
                                            @endforeach
                                            <tr nobr="true">
                                                <td  style="font-family:arialbd;border: 0.7px solid #494444;background-color: #f4f4f4;">مجموع المعاملة</td>
                                                <td  style="font-family:arialbd;border: 0.7px solid #494444;background-color: #f4f4f4;">{{ $row->file_study_sum }}</td>
                                                <td  style="font-family:arialbd;border: 0.7px solid #494444;background-color: #f4f4f4;">{{ $row->file_file_sum }}</td>
                                                <td  style="font-family:arialbd;border: 0.7px solid #494444;background-color: #f4f4f4;">{{ $row->file_resident_sum }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="line-height: 0px;" colspan="4" border="0"></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    
                @endforeach
            </tbody>
        </table>


    </body>
@endif

</html>
