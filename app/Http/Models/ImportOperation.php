<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class ImportOperation extends Model
{
    protected $table = 'import_operations';
    public $timestamps = true;
    public $fillable = ["file_name", "type", "date", "total_studies_before", "total_file_records", "total_successfully", "total_failed", "failed_errors"];
}
