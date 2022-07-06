<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FinancialDeal extends Model
{
    use SoftDeletes;
    protected $table = 'financial_deals';
    public $timestamps = true;
    public $fillable = [
        "engineer_id", "financial_year", "financial_month", "factor", "effort", "financial_system", "percent", "effort_percent",
    "share_in", "share_out", "veri_out", "resident_out", "folder_out","supervision","discount","compensation","total_amount","notes","operation_id"];

    public function file_engineer(){
        return $this->belongsTo(User::class,"file_engineer_id");
    }
}
