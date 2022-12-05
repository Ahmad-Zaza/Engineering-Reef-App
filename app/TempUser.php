<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TempUser extends Model
{
    protected $table = 'temp_users';

    protected $fillable = [
        'num',
        'name',
        'username',
        'email',
        'cota',
        'office_status',
        'file_count',
        'operation_id',
        'created_at',
        'updated_at'
    ];
}
