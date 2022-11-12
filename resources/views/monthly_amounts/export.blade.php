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
</style>
@php
@endphp

@if (!$cms_users_num)
    <h3>
        لايوجد بيانات متوفرة
    </h3>
@else

    <body>
        <table>
            <tbody>
                <tr>
                    <td></td>
                </tr>
            </tbody>
        </table>
        <table cellpadding="2" cellspacing="4" border="0" style="border-collapse: collapse;background-color: #9b9999;"
            width="100%">
            <tbody>
                <tr nobr="true" style="">
                    <td width="24%" style="font-family:arialbd;">
                        المبالغ المالية للمهندس:</td>
                    <td width="10%" style="border: 0.7px solid #494444;font-family:arialbd;background-color:white;">
                        &nbsp;{{ $cms_users_num }}&nbsp;
                    </td>
                    <td width="35.5%" style="border: 0.7px solid #494444;font-family:arialbd;background-color:white;">
                        {{ $cms_users_name }}
                    </td>
                    <td width="12%"></td>
                    <td width="6%" style="font-family:arialbd;">للشهر:</td>
                    <td width="12.5%" style="font-family:arialbd;border: 0.7px solid #494444;background-color:white;">
                        {{ $financial_month }} - {{ $financial_year }}</td>

                </tr>
                
            </tbody>
        </table>

        <table cellpadding="2" border="0" style="border-collapse: collapse;" width="100%">
            <tbody>
                <tr>
                    <td style="line-height: 1px;" colspan="6"></td>
                </tr>
                <table cellpadding="2" cellspacing="4" border="0" style="border-collapse: collapse;border: 0.7px solid #494444;"
                    width="100%">
                    <tbody>
                        <tr>
                            <td style="line-height: 1px;" colspan="6"></td>
                        </tr>
                        <tr nobr="true">
                            <td style="font-family:arialbd;">العامل</td>
                            <td style="border: 0.7px solid #494444;">{{ $factor }}</td>
                            <td style="font-family:arialbd;">النظام المالي</td>
                            <td style="border: 0.7px solid #494444;">{{ $financial_system }}</td>
                            <td style="font-family:arialbd;">النسبة</td>
                            <td style="border: 0.7px solid #494444;">{{ $percent }}</td>
                        </tr>
                        <tr>
                            <td style="line-height: 1px;" colspan="6"></td>
                        </tr>
                        <tr nobr="true">
                            <td style="font-family:arialbd;">الأتعاب</td>
                            <td style="border: 0.7px solid #494444;">{{ $effort }}</td>
                            <td style="font-family:arialbd;">الحصة</td>
                            <td style="border: 0.7px solid #494444;">{{ round($effort_percent, 2) }}</td>
                            <td style="font-family:arialbd;">مورد المشترك</td>
                            <td style="border: 0.7px solid #494444;">{{ round($share_in, 2) }}</td>
                        </tr>
                        <tr>
                            <td style="line-height: 1px;" colspan="6"></td>
                        </tr>
                        <tr nobr="true">
                            <td style="font-family:arialbd;">مقبوض المشترك</td>
                            <td style="border: 0.7px solid #494444;">{{ round($share_out, 2) }}</td>
                            <td style="font-family:arialbd;">مقبوض التدقيق</td>
                            <td style="border: 0.7px solid #494444;">{{ round($veri_out, 2) }}</td>
                            <td style="font-family:arialbd;">رديات مقيم</td>
                            <td style="border: 0.7px solid #494444;">{{ round($resident_out, 2) }}</td>
                        </tr>
                        <tr>
                            <td style="line-height: 1px;" colspan="6"></td>
                        </tr>
                        <tr nobr="true">
                            <td style="font-family:arialbd;">رديات إضبارة</td>
                            <td style="border: 0.7px solid #494444;">{{ round($folder_out, 2) }}</td>
                            <td style="font-family:arialbd;">إشراف</td>
                            <td style="border: 0.7px solid #494444;">{{ $supervision }}</td>
                            <td style="font-family:arialbd;">الحسميات</td>
                            <td style="border: 0.7px solid #494444;">{{ $discount }}</td>
                        </tr>
                        <tr>
                            <td style="line-height: 1px;" colspan="6"></td>
                        </tr>
                        <tr nobr="true">
                            <td style="font-family:arialbd;">التعويضات</td>
                            <td style="border: 0.7px solid #494444;">{{ $compensation }}</td>
                            <td style="font-family:arialbd;">ملاحظات</td>
                            <td style="border: 0.7px solid #494444;">{{ $notes }}</td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td style="line-height: 1px;" colspan="6"></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td></td>
                            <td style="font-family:arialbd;background-color: #9b9999">المقبوض الكلي</td>
                            <td style="font-family:arialbd;background-color: #9b9999">{{ $total_amount }}</td>
                            <td></td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>
            </tbody>
        </table>

    </body>
@endif

</html>