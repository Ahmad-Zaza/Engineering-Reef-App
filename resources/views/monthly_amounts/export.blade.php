<html dir="rtl">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<style>

    * {
        /* font-family: DejaVu Sans, sans-serif; */
        direction: rtl;
        font-size: 18px;
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
    <h3>
        جدول المبالغ المالية للمهندس:
        <span>{{ $cms_users_name ." - ". $cms_users_num }}</span>
        <span>&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;</span>
        <span>&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;</span>
        <span>&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;</span>
        <span>&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;</span>
        للشهر
        <span>{{ $financial_month }}</span>
        السنة
        <span>{{ $financial_year }}</span>
    </h3>
    <table cellpadding="4" border="0" style="border-collapse: collapse;font-size:12px;margin-right:auto;margin-left:auto" width="100%">
        <tbody>
            <tr nobr="true">
                <td></td>
                <td border="1">العامل</td>
                <td border="1">{{ $factor }}</td>
                <td></td>
            </tr>
            <tr nobr="true">
                <td></td>
                <td border="1">النظام المالي</td>
                <td border="1">{{ $financial_system }}</td>
                <td></td>
            </tr>
            <tr nobr="true">
                <td></td>
                <td border="1">النسبة</td>
                <td border="1">{{ $percent }}</td>
                <td></td>
            </tr>
            <tr nobr="true">
                <td></td>
                <td border="1">الأتعاب</td>
                <td border="1">{{ $effort }}</td>
                <td></td>
            </tr>
            <tr nobr="true">
                <td></td>
                <td border="1">الحصة</td>
                <td border="1">{{ $effort_percent }}</td>
                <td></td>
            </tr>
            <tr nobr="true">
                <td></td>
                <td border="1">مورد المشترك</td>
                <td border="1">{{ $share_in }}</td>
                <td></td>
            </tr>
            <tr nobr="true">
                <td></td>
                <td border="1">مقبوض المشترك</td>
                <td border="1">{{ $share_out }}</td>
                <td></td>
            </tr>
            <tr nobr="true">
                <td></td>
                <td border="1">مقبوض التدقيق</td>
                <td border="1">{{ $veri_out }}</td>
                <td></td>
            </tr>
            <tr nobr="true">
                <td></td>
                <td border="1">رديات مقيم</td>
                <td border="1">{{ $resident_out }}</td>
                <td></td>
            </tr>
            <tr nobr="true">
                <td></td>
                <td border="1">رديات إضبارة</td>
                <td border="1">{{ $folder_out }}</td>
                <td></td>
            </tr>
            <tr nobr="true">
                <td></td>
                <td border="1">إشراف</td>
                <td border="1">{{ $supervision }}</td>
                <td></td>
            </tr>
            <tr nobr="true">
                <td></td>
                <td border="1">الحسميات</td>
                <td border="1">{{ $discount }}</td>
                <td></td>
            </tr>
            <tr nobr="true">
                <td></td>
                <td border="1">التعويضات</td>
                <td border="1">{{ $compensation }}</td>
                <td></td>
            </tr>
            <tr nobr="true">
                <td></td>
                <td border="1">المقبوض الكلي</td>
                <td border="1">{{ $total_amount }}</td>
                <td></td>
            </tr>
            <tr nobr="true">
                <td></td>
                <td border="1">ملاحظات</td>
                <td border="1">{{ $notes }}</td>
                <td></td>
            </tr>
        </tbody>
    </table>


</body>
@endif
</html>