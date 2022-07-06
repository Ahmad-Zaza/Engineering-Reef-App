<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DealDetail extends Model
{
    use SoftDeletes;
    protected $table = 'deal_details';
    public $timestamps = true;

    public function study_engineer(){
        return $this->belongsTo(User::class,"study_engineer_id");
    }
}
