<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TempDeal extends Model
{
    protected $table = 'temp_deals';
    protected $fillable = [
        "file_engineer_id",
        "close_year",
        "close_month",
        "file_num",
        "file_date",
        "file_type",
        "note_num",
        "note_date",
        "confinement_area",
        "real_estate_area",
        "real_estate_num",
        "owner_name",
        "file_status",
        "organization_name",
        "operation_id",
        "paid_year",
        "paid_month",
        //
        "total_space",
        "license_sum",
        "floors_count",
        "study_engineer_id",
        "study_name",
        "study_value",
        "study_resident_value",
        "study_file_value"
    ];
}
