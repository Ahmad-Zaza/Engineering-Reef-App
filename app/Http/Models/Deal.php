<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class Deal extends Model
{
    protected $table = 'deals';
    public $timestamps = true;
    public $fillable = ["file_engineer_id", "close_year", "close_month", "file_num", "file_date", "file_type", "note_num", "note_date", "confinement_area", "real_estate_area", "real_estate_num", "owner_name", "file_status", "organization_name","operation_id"];
}
