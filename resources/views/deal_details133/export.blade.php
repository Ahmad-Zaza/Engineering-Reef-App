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
        الإقامات غير المسددة لمعاملات المهندس:
        <span>- {{ $result[0]->deal_engineer_num }} :</span>
        <span>{{ $result[0]->deal_engineer_name }}</span>
        <span>&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;</span>
    </h3>
    <table cellpadding="2" border="1" style="border-collapse: collapse;font-size:12px" width="100%">
        <thead>
            <tr>
                <td>رقم المعاملة</td>
                <td>تاريخ المعاملة</td>
                <td>رقم المهندس</td>
                <td>اسم المهندس</td>
                <td>المبلغ</td>
                <td>مالك العقار</td>
                <td>المنطقة العقارية</td>
                <td>أرقام العقار</td>
            </tr>
        </thead>
        <tbody>
            @foreach ($result as $row)
                <tr>
                    <td>{{ $row->deals_file_num }}</td>
                    <td>{{ $row->deals_file_date }}</td>
                    <td>{{ $row->cms_users_num }}</td>
                    <td>{{ $row->cms_users_name }}</td>
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