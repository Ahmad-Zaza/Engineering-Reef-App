<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TempPaidDeal extends Model
{
    protected $table = 'temp_paid_deals';

    protected $fillable = [
        "engineer_id",
        "deal_id",
        "month",
        "year",
        "total_amount",
        "note_num",
        "note_date",
        "operation_id",
        "application_date",
        "file_num",
        "file_date",
        "owner_name",
        "real_estate_area",
        "confinement_area",
        "real_estate_num",
        "file_engineer_id"
    ];
}
