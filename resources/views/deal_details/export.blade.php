<html dir="rtl">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<style>
    * {
        direction: rtl;
        font-size: 10px;
        box-sizing: border-box;
    }
</style>
@php

@endphp
@if (count($details) == 0)
    <h3>
        لايوجد بيانات متوفرة
    </h3>
@else

    <body>
        <h3>
            تاريخ المعاملة:
            <span> {{ $deal->file_date }}</span>
            <span>&emsp;&emsp;&emsp;&emsp;&emsp;</span>
            رقم المعاملة:
            <b>{{ $deal->file_num }}</b>
            <span>&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;</span>
            مهندس المعاملة:
            <span>{{ $deal->file_engineer->name . ' - ' . $deal->file_engineer->num }}</span>
        </h3>
        <table cellpadding="2" border="0" style="border-collapse: collapse;margin:auto;" width="100%">
            <tbody>
                <tr nobr="true">
                    <td width="16%">صاحب العلاقة</td>
                    <td border="1" width="16%">{{ $deal->owner_name }}</td>
                    <td width="2%"></td>
                    <td width="16%">منطقة حصر العقار</td>
                    <td border="1" width="16%">{{ $deal->confinement_area }}</td>
                    <td width="2%"></td>
                    <td width="16%">نوع الرخصة</td>
                    <td border="1" width="16%">{{ $deal->file_type }}</td>
                </tr>
                <tr>
                    <td colspan="6"></td>
                </tr>
                <tr nobr="true">
                    <td width="16%">المنطقة العقارية</td>
                    <td border="1" width="16%">{{ $deal->real_estate_area }}</td>
                    <td width="2%"></td>
                    <td width="16%">عدد الطوابق</td>
                    <td border="1" width="16%">{{ $deal->floors_count }}</td>
                    <td width="2%"></td>
                    <td width="16%">المساحة الإجمالية</td>
                    <td border="1" width="16%">{{ $deal->total_space }}</td>
                </tr>
                <tr>
                    <td colspan="6"></td>
                </tr>
                <tr nobr="true">
                    <td width="16%">أرقام العقارات</td>
                    <td border="1" width="16%">{{ $deal->real_estate_num }}</td>
                    <td width="2%"></td>
                    <td width="16%">تاريخ المذكرة ورقمها</td>
                    <td border="1" width="16%">
                        {{ $deal->note_num ? $deal->note_num . ' - ' . $deal->note->date : '' }}</td>
                    <td width="2%"></td>
                    <td width="16%">مجموع الرخصة</td>
                    <td border="1" width="16%">{{ $deal->license_sum }}</td>
                </tr>

            </tbody>
        </table>
        <br>
        <br>
        <table cellpadding="2" border="1" style="border-collapse: collapse;margin:auto;" width="100%">
            <thead>
                <tr>
                    <th align="center">نوع الدراسة</th>
                    <th align="center">رقم المهندس</th>
                    <th align="center">اسم المهندس</th>
                    <th align="center">الأتعاب</th>
                    <th align="center">اضبارة</th>
                    <th align="center">مقيم</th>
                </tr>

            </thead>
            <tbody>
                @foreach ($details as $study_name => $item)
                    @foreach ($item['items'] as $subitem)
                        <tr nobr="true">
                            @if ($loop->iteration == 1)
                                <td rowspan="{{ count($item['items']) + 1 }}" align="center">{{ $study_name }}
                                </td>
                            @endif
                            <td>{{ $subitem->study_engineer->num }}</td>
                            <td>{{ $subitem->study_engineer->name }}</td>
                            <td>{{ $subitem->study_value }}</td>
                            <td>{{ $subitem->study_file_value }}</td>
                            <td>{{ $subitem->study_resident_value }}</td>
                        </tr>
                    @endforeach
                    <tr nobr="true">
                        <td colspan="2">
                            المجموع
                        </td>
                        <td style="background-color: rgb(131, 131, 131)">
                            {{ $item['total_study'] > 0 ? $item['total_study'] : '' }}</td>
                        <td style="background-color: rgb(131, 131, 131)">
                            {{ $item['total_file'] > 0 ? $item['total_file'] : '' }}</td>
                        <td style="font-weight:bold;background-color: rgb(131, 131, 131)">
                            {{ $item['total_resident'] > 0 ? $item['total_resident'] : '' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>


    </body>
@endif

</html>
