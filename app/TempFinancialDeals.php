<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TempFinancialDeals extends Model
{
    protected $table = 'temp_financial_deals';
    protected $fillable = [
        "engineer_id",
        "financial_year",
        "financial_month",
        "factor",
        "effort",
        "financial_system",
        "percent",
        "effort_percent",
        "share_in",
        "share_out",
        "veri_out",
        "resident_out",
        "folder_out",
        "supervision",
        "discount",
        "compensation",
        "total_amount",
        "notes",
        "operation_id"
    ];
}
