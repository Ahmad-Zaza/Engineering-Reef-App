<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PaidDeal extends Model
{
    use SoftDeletes;
    protected $table = 'paid_deals';
    public $timestamps = true;
    public $fillable = [
        "engineer_id", "deal_id", "month", "year", "total_amount", "note_num", "note_date","operation_id","application_date"];

    public function engineer()
    {
        return $this->belongsTo(User::class, "engineer_id");
    }
    public function deal()
    {
        return $this->belongsTo(Deal::class, "deal_id");
    }
}
