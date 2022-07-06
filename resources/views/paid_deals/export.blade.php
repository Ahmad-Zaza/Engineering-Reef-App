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
        إقامات الدراسات المسددة للمهندس:
        <span>- {{ $result[0]->cms_users_num }} :</span>
        <span>{{ $result[0]->cms_users_name }}</span>
        <span>&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;</span>
    </h3>
    <table cellpadding="2" border="1" style="border-collapse: collapse;font-size:12px" width="100%">
        <thead>
            <tr>
                <td>رقم المذكرة</td>
                <td>تاريخ المذكرة</td>
                <td>رقم المعاملة</td>
                <td>تاريخ المعاملة</td>
                <td>المبلغ</td>
                <td>تاريخ التطبيق</td>
                <td>مالك العقار</td>
                <td>أرقام العقار</td>
            </tr>
        </thead>
        <tbody>
            @foreach ($result as $row)
                <tr>
                    <td>{{ $row->note_num }}</td>
                    <td>{{ $row->note_date }}</td>
                    <td>{{ $row->deals_file_num }}</td>
                    <td>{{ $row->deals_file_date }}</td>
                    <td>{{ $row->total_amount }}</td>
                    <td>{{ $row->application_date }}</td>
                    <td>{{ $row->deals_owner_name }}</td>
                    <td>{{ $row->deals_real_estate_num }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>
@endif
</html>