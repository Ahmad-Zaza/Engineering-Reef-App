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

@if(!$cms_users_num)
<h3>
    لايوجد بيانات متوفرة
</h3>
@else
<body>
    
    <table cellpadding="2" cellspacing="4" border="0" style="border-collapse: collapse;" width="100%">
        <tbody>
            <tr>
                <td></td>
            </tr>
            <tr nobr="true" style="">
                <td align="center" style="font-family:arialbd;">
                    جدول المبالغ المالية للمهندس: {{$cms_users_name ." - ". $cms_users_num }}
                </td>
            </tr>
            <tr>
                <td align="center" style="font-family:arialbd;">للشهر {{ $financial_month }} السنة {{ $financial_year }}</td>
            </tr>
        </tbody>
    </table>
    <table cellpadding="5" border="0" style="border-collapse: collapse;margin-right:auto;margin-left:auto" width="100%">
        <tbody>
            <tr nobr="true">
                <td></td>
                <td style="border: 0.7px solid #494444;font-family:arialbd;background-color: #9b9999;">العامل</td>
                <td style="border: 0.7px solid #494444;background-color: #9b9999;">{{ $factor }}</td>
                <td></td>
            </tr>
            <tr nobr="true">
                <td></td>
                <td style="border: 0.7px solid #494444;font-family:arialbd;">النظام المالي</td>
                <td style="border: 0.7px solid #494444;">{{ $financial_system }}</td>
                <td></td>
            </tr>
            <tr nobr="true">
                <td></td>
                <td style="border: 0.7px solid #494444;font-family:arialbd;background-color: #9b9999;">النسبة</td>
                <td style="border: 0.7px solid #494444;background-color: #9b9999;">{{ $percent }}</td>
                <td></td>
            </tr>
            <tr nobr="true">
                <td></td>
                <td style="border: 0.7px solid #494444;font-family:arialbd;">الأتعاب</td>
                <td style="border: 0.7px solid #494444;">{{ $effort }}</td>
                <td></td>
            </tr>
            <tr nobr="true">
                <td></td>
                <td style="border: 0.7px solid #494444;font-family:arialbd;background-color: #9b9999;">الحصة</td>
                <td style="border: 0.7px solid #494444;background-color: #9b9999;">{{ $effort_percent }}</td>
                <td></td>
            </tr>
            <tr nobr="true">
                <td></td>
                <td style="border: 0.7px solid #494444;font-family:arialbd;">مورد المشترك</td>
                <td style="border: 0.7px solid #494444;">{{ $share_in }}</td>
                <td></td>
            </tr>
            <tr nobr="true">
                <td></td>
                <td style="border: 0.7px solid #494444;font-family:arialbd;background-color: #9b9999;">مقبوض المشترك</td>
                <td style="border: 0.7px solid #494444;background-color: #9b9999;">{{ $share_out }}</td>
                <td></td>
            </tr>
            <tr nobr="true">
                <td></td>
                <td style="border: 0.7px solid #494444;font-family:arialbd;">مقبوض التدقيق</td>
                <td style="border: 0.7px solid #494444;">{{ $veri_out }}</td>
                <td></td>
            </tr>
            <tr nobr="true">
                <td></td>
                <td style="border: 0.7px solid #494444;font-family:arialbd;background-color: #9b9999;">رديات مقيم</td>
                <td style="border: 0.7px solid #494444;background-color: #9b9999;">{{ $resident_out }}</td>
                <td></td>
            </tr>
            <tr nobr="true">
                <td></td>
                <td style="border: 0.7px solid #494444;font-family:arialbd;">رديات إضبارة</td>
                <td style="border: 0.7px solid #494444;">{{ $folder_out }}</td>
                <td></td>
            </tr>
            <tr nobr="true">
                <td></td>
                <td style="border: 0.7px solid #494444;font-family:arialbd;background-color: #9b9999;">إشراف</td>
                <td style="border: 0.7px solid #494444;background-color: #9b9999;">{{ $supervision }}</td>
                <td></td>
            </tr>
            <tr nobr="true">
                <td></td>
                <td style="border: 0.7px solid #494444;font-family:arialbd;">الحسميات</td>
                <td style="border: 0.7px solid #494444;">{{ $discount }}</td>
                <td></td>
            </tr>
            <tr nobr="true">
                <td></td>
                <td style="border: 0.7px solid #494444;font-family:arialbd;background-color: #9b9999;">التعويضات</td>
                <td style="border: 0.7px solid #494444;background-color: #9b9999;">{{ $compensation }}</td>
                <td></td>
            </tr>
            <tr nobr="true">
                <td></td>
                <td style="border: 0.7px solid #494444;font-family:arialbd;">المقبوض الكلي</td>
                <td style="border: 0.7px solid #494444;">{{ $total_amount }}</td>
                <td></td>
            </tr>
            <tr nobr="true">
                <td></td>
                <td style="border: 0.7px solid #494444;font-family:arialbd;background-color: #9b9999;">ملاحظات</td>
                <td style="border: 0.7px solid #494444;background-color: #9b9999;">{{ $notes }}</td>
                <td></td>
            </tr>
        </tbody>
    </table>


</body>
@endif
</html>