<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ImportFileDetails extends Model
{
    protected $table = 'import_file_details';

    protected $fillable = [
        'id',
        'name',
        'total_successfully',
        'total_file_records',
        'total_failed',
        'file_status',
        'type',
        'created_at',
        'updated_at',
        'active',
        'sorting'
    ];
}
