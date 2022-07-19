<html dir="rtl">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<style>
    * {
        direction: rtl;
        font-size: 11px;
        box-sizing: border-box;
    }
    .studies td,.studies th{
        border: 0.7px solid #494444;
        vertical-align: center;
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
        <table cellpadding="2" cellspacing="4" border="0" style="border-collapse: collapse;background-color: #9b9999;" width="100%">
            <tbody>
                <tr nobr="true" style="">
                    <td width="15%" style="font-family:arialbd;">تفاصيل المعاملة:</td>
                    <td width="8%" style="border: 0.7px solid #494444;font-family:arialbd;background-color:white;">
                        {{ $deal->file_num }}
                    </td>
                    <td width="20%" style="border: 0.7px solid #494444;font-family:arialbd;background-color:white;">
                        {{ $deal->file_date }}
                    </td>
                    <td width="15%" style="font-family:arialbd;">مهندس المعاملة:</td>
                    <td width="42%" style="font-family:arialbd;border: 0.7px solid #494444;background-color:white;">
                        {{ $deal->file_engineer->name . ' - ' . $deal->file_engineer->num }}
                    </td>
                </tr>
            </tbody>
        </table>
        <table cellpadding="2" cellspacing="4" border="0" style="border-collapse: collapse;margin:auto;" width="100%">
            <tbody>
                <tr>
                    <td colspan="8" style="line-height:3px;"></td>
                </tr>
                <tr nobr="true">
                    <td width="16%" style="font-family:arialbd;">صاحب العلاقة</td>
                    <td style="border: 0.7px solid #494444;" width="16%">&nbsp;{{ $deal->owner_name }}&nbsp;</td>
                    <td width="2%"></td>
                    <td width="16%" style="font-family:arialbd;">منطقة حصر العقار</td>
                    <td style="border: 0.7px solid #494444;" width="16%">&nbsp;{{ $deal->confinement_area }}&nbsp;</td>
                    <td width="2%"></td>
                    <td width="16%" style="font-family:arialbd;">نوع الرخصة</td>
                    <td style="border: 0.7px solid #494444;" width="16%">&nbsp;{{ $deal->file_type }}&nbsp;</td>
                </tr>
                <tr>
                    <td colspan="8" style="line-height:0px;"></td>
                </tr>
                <tr nobr="true">
                    <td width="16%" style="font-family:arialbd;">المنطقة العقارية</td>
                    <td style="border: 0.7px solid #494444;" width="16%">&nbsp;{{ $deal->real_estate_area }}&nbsp;</td>
                    <td width="2%"></td>
                    <td width="16%" style="font-family:arialbd;">عدد الطوابق</td>
                    <td style="border: 0.7px solid #494444;" width="16%">&nbsp;{{ $deal->floors_count }}&nbsp;</td>
                    <td width="2%"></td>
                    <td width="16%" style="font-family:arialbd;">المساحة الإجمالية</td>
                    <td style="border: 0.7px solid #494444;" width="16%">&nbsp;{{ $deal->total_space }}&nbsp;</td>
                </tr>
                <tr>
                    <td colspan="8" style="line-height:0px;"></td>
                </tr>
                <tr nobr="true">
                    <td width="16%" style="font-family:arialbd;">أرقام العقارات</td>
                    <td style="border: 0.7px solid #494444;" width="16%">&nbsp;{{ $deal->real_estate_num }}&nbsp;</td>
                    <td width="2%"></td>
                    <td width="16%" style="font-family:arialbd;">تاريخ المذكرة ورقمها</td>
                    <td style="border: 0.7px solid #494444;" width="16%">
                        &nbsp;{{ $deal->note_num ? $deal->note_num . ' - ' . $deal->note->date : '' }}&nbsp;</td>
                    <td width="2%"></td>
                    <td width="16%" style="font-family:arialbd;">مجموع الرخصة</td>
                    <td style="border: 0.7px solid #494444;" width="16%">&nbsp;{{ $deal->license_sum }}&nbsp;</td>
                </tr>

            </tbody>
        </table>
        <br>
        <br>
        <table class="studies" cellpadding="2" border="0" style="border-collapse: collapse;border: 0.7px solid #494444;" width="100%">
            <thead>
                <tr>
                    <th align="center" style="font-family:arialbd;">نوع الدراسة</th>
                    <th align="center" style="font-family:arialbd;">رقم المهندس</th>
                    <th align="center" style="font-family:arialbd;">اسم المهندس</th>
                    <th align="center" style="font-family:arialbd;">الأتعاب</th>
                    <th align="center" style="font-family:arialbd;">اضبارة</th>
                    <th align="center" style="font-family:arialbd;">مقيم</th>
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
                            <td align="center">{{ $subitem->study_engineer->num }}</td>
                            <td align="center">{{ $subitem->study_engineer->name }}</td>
                            <td align="center">{{ $subitem->study_value }}</td>
                            <td align="center">{{ $subitem->study_file_value }}</td>
                            <td align="center">{{ $subitem->study_resident_value }}</td>
                        </tr>
                    @endforeach
                    <tr nobr="true">
                        <td align="center" colspan="2" style="font-family:arialbd;">
                            المجموع
                        </td>
                        <td align="center" style="font-family:arialbd;background-color: #9b9999">
                            {{ $item['total_study'] > 0 ? $item['total_study'] : '' }}</td>
                        <td align="center" style="font-family:arialbd;background-color: #9b9999">
                            {{ $item['total_file'] > 0 ? $item['total_file'] : '' }}</td>
                        <td align="center" style="font-family:arialbd;background-color: #9b9999">
                            {{ $item['total_resident'] > 0 ? $item['total_resident'] : '' }}</td>
                    </tr>
                    <tr>
                        <td colspan="6" style="line-height:0px;"></td>
                    </tr>
                @endforeach
            </tbody>
        </table>


    </body>
@endif

</html>
