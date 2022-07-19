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
        <table cellpadding="2" cellspacing="4" border="0" style="border-collapse: collapse;background-color: #9b9999;"
            width="100%">
            <tbody>
                <tr nobr="true" style="">
                    <td width="16%" style="font-family:arialbd;">
                        تفاصيل الدراسات للمهندس:</td>
                    <td width="8%" style="border: 0.7px solid #494444;font-family:arialbd;background-color:white;">
                        &nbsp;{{ $result[0]->deal_details[0]->num }}&nbsp;
                    </td>
                    <td width="25.5%" style="border: 0.7px solid #494444;font-family:arialbd;background-color:white;">
                        {{ $result[0]->deal_details[0]->name }}
                    </td>
                    <td width="24%"></td>
                    <td width="4%" style="font-family:arialbd;">الكوتا:</td>
                    <td width="22.5%" style="font-family:arialbd;border: 0.7px solid #494444;background-color:white;">
                        &nbsp;{{ $result[0]->deal_details[0]->cota }}&nbsp;
                    </td>
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
                    <td width="5%" align="center" style="background-color: #9b9999;font-family:arialbd;">
                        {{ $result[0]->close_month }}
                    </td>
                    <td width="7%" align="center" style="background-color: #9b9999;font-family:arialbd;">
                        {{ $result[0]->close_year }}
                    </td>
                    <td width="20%"></td>
                    <td width="17.5%"></td>
                    <td width="14.5%" align="center" class="all-border" style="background-color: #9b9999;font-family:arialbd;">المجموع الشهري</td>
                    <td width="12%" align="center" style="border: 0.7px solid #494444;background-color: #9b9999;font-family:arialbd;">
                        &nbsp;{{ $total['total_study_sum'] }}&nbsp;</td>
                    <td width="12%" align="center" style="border: 0.7px solid #494444;background-color: #9b9999;font-family:arialbd;">
                        &nbsp;{{ $total['total_file_sum'] }}&nbsp;</td>
                    <td width="12%" align="center" style="border: 0.7px solid #494444;background-color: #9b9999;font-family:arialbd;">
                        &nbsp;{{ $total['total_resident_sum'] }}&nbsp;</td>
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
                                <td style="border: 0.7px solid #494444;background-color: #9b9999;font-family:arialbd;">&nbsp;{{ $row->file_num }}&nbsp;
                                </td>
                                <td style="font-family:arialbd;">تاريخ المعاملة</td>
                                <td  style="border: 0.7px solid #494444;background-color: #9b9999;font-family:arialbd;">&nbsp;{{ $row->file_date }}&nbsp;
                                </td>
                                <td style="font-family:arialbd;">نوع المعاملة</td>
                                <td  style="border: 0.7px solid #494444;font-family:arialbd;">&nbsp;{{ $row->file_type }}&nbsp;</td>
                                <td style="font-family:arialbd;">مذكرة</td>
                                <td  style="border: 0.7px solid #494444;font-size:10px;">
                                    &nbsp;{{ $row->note_num && $row->note_date ? $row->note_num . '/' . ($row->note_date ?: '') : '' }}&nbsp;
                                </td>
                            </tr>
                            <tr nobr="true">
                                <td style="font-family:arialbd;">المنطقة العقارية</td>
                                <td  style="border: 0.7px solid #494444;">&nbsp;{{ $row->real_estate_area }}&nbsp;</td>
                                <td style="font-family:arialbd;">منطقة الحصر</td>
                                <td  style="border: 0.7px solid #494444;">&nbsp;{{ $row->confinement_area }}&nbsp;</td>
                                <td style="font-family:arialbd;">مالك العقار</td>
                                <td colspan="3" style="border: 0.7px solid #494444;">&nbsp;{{ $row->owner_name }}&nbsp;</td>
                            </tr>
                            <tr nobr="true">
                                <td style="font-family:arialbd;">مهندس المعاملة</td>
                                <td  style="border: 0.7px solid #494444;">&nbsp;{{ $row->file_user_num }}&nbsp;</td>
                                <td colspan="2" style="border: 0.7px solid #494444;">&nbsp;{{ $row->file_user_name }}&nbsp;</td>
                                <td style="font-family:arialbd;">حالة المعاملة</td>
                                <td  style="border: 0.7px solid #494444;">&nbsp;{{ $row->file_status }}&nbsp;</td>
                                <td style="font-family:arialbd;">أرقام العقارات</td>
                                <td  style="border: 0.7px solid #494444;">&nbsp;{{ $row->real_estate_num }}&nbsp;</td>
                            </tr>
                            <tr>
                                <td colspan="8" align="center" style="text-align:center;">
                                    <table cellpadding="3" style="border-collapse: collapse;margin:auto;" width="100%">
                                        <tbody>
                                            <tr>
                                                <td style="line-height: 0px;" colspan="4" border="0"></td>
                                            </tr>
                                            <tr>
                                                <td style="font-family:arialbd;border: 0.7px solid #494444;background-color: #9b9999;" align="center" colspan="4">الدراسات</td>
                                            </tr>
                                            <tr nobr="true">
                                                <td style="font-family:arialbd;border: 0.7px solid #494444;background-color: #9b9999;">اسم الدراسة</td>
                                                <td style="font-family:arialbd;border: 0.7px solid #494444;background-color: #9b9999;">دراسة</td>
                                                <td style="font-family:arialbd;border: 0.7px solid #494444;background-color: #9b9999;">إضبارة</td>
                                                <td style="font-family:arialbd;border: 0.7px solid #494444;background-color: #9b9999;">مقيم</td>
                                            </tr>
            
                                            @foreach ($row->deal_details as $row1)
                                                <tr nobr="true">
                                                    <td style="border: 0.7px solid #494444;">&nbsp;{{ $row1->study_name }}&nbsp;</td>
                                                    <td style="border: 0.7px solid #494444;">&nbsp;{{ $row1->study_value }}&nbsp;</td>
                                                    <td style="border: 0.7px solid #494444;">&nbsp;{{ $row1->study_file_value }}&nbsp;</td>
                                                    <td style="border: 0.7px solid #494444;">&nbsp;{{ $row1->study_resident_value }}&nbsp;</td>
                                                </tr>
                                            @endforeach
                                            <tr nobr="true">
                                                <td  style="font-family:arialbd;border: 0.7px solid #494444;background-color: #9b9999;">مجموع المعاملة</td>
                                                <td  style="font-family:arialbd;border: 0.7px solid #494444;background-color: #9b9999;">&nbsp;{{ $row->file_study_sum }}&nbsp;</td>
                                                <td  style="font-family:arialbd;border: 0.7px solid #494444;background-color: #9b9999;">&nbsp;{{ $row->file_file_sum }}&nbsp;</td>
                                                <td  style="font-family:arialbd;border: 0.7px solid #494444;background-color: #9b9999;">&nbsp;{{ $row->file_resident_sum }}&nbsp;
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
