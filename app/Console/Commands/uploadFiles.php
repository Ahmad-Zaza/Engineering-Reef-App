<?php

namespace App\Console\Commands;

use App\Http\Models\Deal;
use App\Http\Models\DealDetail;
use App\Http\Models\FinancialDeal;
use App\Http\Models\PaidDeal;
use App\ImportFileDetails;
use App\TempUser;
use Exception;
use Illuminate\Console\Command;
use Maatwebsite\Excel\Facades\Excel;
use App\TempDeal;
use App\TempFinancialDeals;
use App\TempPaidDeal;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use crocodicstudio_voila\crudbooster\helpers\CRUDBooster;
use PDO;
use PHPExcel_Cell;

class uploadFiles extends Command
{

    protected $signature = 'files:upload';

    protected $description = '';


    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        set_time_limit(0);
        ini_set('memory_limit', '-1');
        $files = ImportFileDetails::orderBy('id')->get();
        try {
            // write excell file rows to temp database table
            foreach ($files as $file) {
                if ($file->file_status != trans('crudbooster.file_status.waiting')) // check the file status
                    continue;
                $filePath = storage_path('app/' . $file->path);
                $rows = Excel::load($filePath, function ($reader) {
                })->get();
                $headerRow = $rows[0]->keys()->toArray();
                $checkCurrectFileType = $this->checkTypeValidate($file->type, $headerRow);
                if (!$checkCurrectFileType) {
                    $file->update([
                        'file_status' => trans('crudbooster.file_status.failed'),
                        'updated_at' => Carbon::now(),
                    ]);
                    exit;
                }
                //---------------------------
                switch ($file->type) {
                    case trans('crudbooster.file_type.personal'):
                        $this->readPersonalsData($file, $rows);
                        break;
                    case trans('crudbooster.file_type.deals'):
                        $this->readDealsData($file, $rows);
                        break;
                    case trans('crudbooster.file_type.paid_stays'):
                        $this->readPaidStaysData($file, $rows);
                        break;
                    case trans('crudbooster.file_type.monthly_ammounts'):
                        $this->readFinancialDealsData($file, $rows);
                        break;
                    default:
                }
            }
        } catch (Exception $ex) {
            Log::log('error', 'Error while reading files to temporary table' . $ex->getMessage());
        }

        // write from temporary table to original table
        foreach ($files as $file) {
            if ($file->file_status == trans('crudbooster.file_status.done') || $file->file_status == trans('crudbooster.file_status.failed')) // check the file status is not done yet
                continue;
            switch ($file->type) {
                case trans('crudbooster.file_type.personal'):
                    $this->writePersonalData($file);
                    break;
                case trans('crudbooster.file_type.deals'):
                    $this->writeDealData($file);
                    break;
                case trans('crudbooster.file_type.paid_stays'):
                    $this->writePaidStaysData($file);
                    break;
                case trans('crudbooster.file_type.monthly_ammounts'):
                    $this->writeFinancialDealsData($file);
                    break;
            }
        }
    }

    public function readPersonalsData($file, $rows)
    {
        foreach ($rows as $key => $value) {
            try {
                TempUser::create([
                    'num' => $value['number'] ?? null,
                    'name' => $value['name'] ?? null,
                    'office_status' => $value['status'] ?? null,
                    'cota' => $value['cota'] ?? null,
                    'operation_id' => $file->id,
                ]);
            } catch (Exception $ex) {
                Log::log("error", "Error whilke create temp users $ex");
            }
        }
        $file->update([
            'file_status' => trans('crudbooster.file_status.in_importing'),
            'total_successfully' => 0,
            'total_file_records' => count($rows),
            'total_failed' => 0
        ]);
        exit;
    }

    public function writePersonalData($file)
    {
        $has_created_at = false;
        if (CRUDBooster::isColumnExists('cms_users', 'created_at')) {
            $has_created_at = true;
        }
        $password = password_hash(123455, PASSWORD_DEFAULT);

        $tempItems = TempUser::where('operation_id', $file->id)->orderBy('id')->get();
        foreach ($tempItems as $value) {
            $username = '';
            if (!$value["username"]) {
                $username = $value["num"];
            }

            $personalData = [
                "id_cms_privileges" => "2",
                "photo" => "/images/portfolio1_logo.png",
                "name" => $value["name"],
                "num" => $value["num"],
                "office_status" => $value["office_status"],
                "cota" => $value["cota"],
                "username" => $username,
                "password" => $password,
            ];

            $user = DB::table('cms_users')->where("num", $personalData["num"])->first();
            //----------------------------------//
            try {
                if ($has_created_at) {
                    $personalData['created_at'] = date('Y-m-d H:i:s');
                }
                $personalData['username'] = $personalData['num'];
                if ($user) {
                    DB::table('cms_users')->where("id", $user->id)->update([
                        "office_status" => $personalData["office_status"],
                        "cota" => $personalData["cota"],
                        "updated_at" => Carbon::now(),
                    ]);
                } else {
                    DB::table('cms_users')->insert($personalData);
                }
                $value->delete();
            } catch (\Exception $e) {
                $e = (string) $e;
                Log::log("error", "Error importing users files $e");
            }
        }
        $file->update([
            'file_status' => $file->total_failed == 0 ? trans('crudbooster.file_status.done') : trans('crudbooster.file_status.failed'),
            'total_successfully' => ($file->total_file_records - $file->total_failed),
            'updated_at' => Carbon::now(),
        ]);
    }

    public function readDealsData($file, $rows)
    {
        foreach ($rows as $value) {
            $real_estate_num = $value['rkm_alaakar'] == ":" ? "" : explode(":", $value['rkm_alaakar']);
            $noteData = explode(" : ", $value["rkm_otarykh_almthkr"]);
            $note_date = null;
            try {
                if (trim($noteData[1])) {
                    $note_date = date('Y-m-d', strtotime(trim($noteData[1])));
                }
            } catch (Exception $e) {
                Log::log("error", "Error Note Date " . json_encode($value) . " " . $e->getMessage());
            }

            $dealData = [
                "operation_id" => $file->id,
                "file_engineer_id" => $value["rkm_mhnds_almaaaml"],
                "close_year" => $value["aaam_aleghlak"],
                "close_month" => $value["shhr_aleghlak"],
                "file_num" => $value["rkm_almaaaml"],
                "file_date" => $value["tarykh_almaaaml"],
                "note_num" => trim($noteData[0]),
                "note_date" => $note_date,
                "file_type" => $value["noaa_almaaaml"],
                "confinement_area" => $value["mntk_alhsr"],
                "real_estate_area" => $value["almntk_alaakary"],
                "real_estate_num" => $real_estate_num ? implode(",", $real_estate_num) : "",
                "owner_name" => $value["sahb_alaalak"],
                "file_status" => $value["hal_almaaaml"],
                "organization_name" => $value["almntk_altnthymy_latthhr_fy_altkryr"],
                "total_space" => $value["almsah_alejmaly"],
                "license_sum" => $value["mjmoaa_alrkhs"],
                "floors_count" => $value["aadd_altoabk"],
                //
                "study_engineer_id" => $value["rkm_mhnds_aldras"],
                "study_name" => $value["noaa_aldras"],
                "study_value" => $value["kym_aldras"],
                "study_resident_value" => $value["mblgh_alekam_moejl_alsrf"],
                "study_file_value" => $value["mblgh_aledbar"],

            ];
            TempDeal::create($dealData);
        }
        $file->update([
            "total_file_records" => count($rows),
            "total_successfully" => 0,
            "total_failed" => 0,
            'file_status' => trans('crudbooster.file_status.in_importing'),
        ]);
        exit;
    }

    public function writeDealData($file)
    {
        $engineers = DB::table('cms_users')->where("id_cms_privileges", 2)->pluck("id", "num")->toArray();
        $dealsArr = DB::table('deals')->where("deleted_at", null)->get()->toArray();
        $tempItems = TempDeal::where('operation_id', $file->id)->orderBy('id')->get();
        foreach ($tempItems as $value) {
            if (!$value["file_num"]) {
                $file->update(['total_failed' => ($file->total_failed + 1)]);
                continue;
            }
            $study_engineer_id = $engineers[$value["study_engineer_id"]];
            $file_engineer_id = $engineers[$value["file_engineer_id"]];
            $dealData = [
                "operation_id" => $file->id,
                "file_engineer_id" => $file_engineer_id,
                "close_year" => $value["close_year"],
                "close_month" => $value["close_month"],
                "file_num" => $value["file_num"],
                "file_date" => $value["file_date"],
                "note_num" => $value["note_num"],
                "note_date" => $value["note_date"],
                "file_type" => $value["file_type"],
                "confinement_area" => $value["confinement_area"],
                "real_estate_area" => $value["real_estate_area"],
                "real_estate_num" => $value["real_estate_num"],
                "owner_name" => $value["owner_name"],
                "file_status" => $value["file_status"],
                "organization_name" => $value["organization_name"],
                "total_space" => $value["total_space"],
                "license_sum" => $value["license_sum"],
                "floors_count" => $value["floors_count"],
            ];

            $dealDetailsData = [
                "operation_id" => $file->id,
                "study_engineer_id" => $study_engineer_id,
                "study_name" => $value["study_name"],
                "study_value" => $value["study_value"],
                "study_resident_value" => $value["study_resident_value"],
                "study_file_value" => $value["study_file_value"],
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now(),
            ];
            try {
                $deal = array_values(array_filter(
                    $dealsArr,
                    function ($item) use ($value) {
                        if (is_string($item->file_date)) {
                            return $item->file_num == intval($value["file_num"]) && $item->file_date == $value["file_date"];
                        } else {
                            return $item->file_num == intval($value["file_num"]) && $item->file_date->format("Y-m-d") == $value["file_date"]->format("Y-m-d");
                        }
                    }
                ))[0];
                if (!$deal) {
                    $deal = Deal::create($dealData);
                    $dealsArr[] = (object) $deal->toArray();
                    DealDetail::where("deal_id", $deal->id)->delete();
                } else {
                    Deal::where("id", $deal->id)->update($dealData);
                }
                $dealDetailsData["deal_id"] = $deal->id;
                DB::table("deal_details")->insert($dealDetailsData);
            } catch (\Exception $e) {
                Log::log("error", "Error Importing deals " . $e->getMessage());
                $file->update(['total_failed' => ($file->total_failed + 1)]);
            }
            $value->delete();
        }
        $file->update([
            'file_status' => $file->total_failed == 0 ? trans('crudbooster.file_status.done') : trans('crudbooster.file_status.failed'),
            'total_successfully' => ($file->total_file_records - $file->total_failed),
            'updated_at' => Carbon::now(),
        ]);
    }

    public function readPaidStaysData($file, $rows)
    {
        foreach ($rows as $value) {
            $paidDealData = [
                "operation_id" => $file->id,
                "engineer_id" => $value["rkm_almhnds"],
                "year" => $value["alaaam"],
                "month" => $value["alshhr"],
                "note_num" => $value["rkm_almthkr"],
                "note_date" => $value["tarykh_almthkr"],
                "application_date" => $value["tarykh_alttbyk"],
                "total_amount" => $value["almblgh"],
                "file_num" => $value["rkm_almaaaml"],
                "file_date" => $value["tarykh_almaaaml"]->format("Y-m-d"),
                "owner_name" => $value["sahb_alaalak"],
                "real_estate_area" => $value["almntk_alaakary"],
                "confinement_area" => optional($value)["mntk_alhsr"],
                "real_estate_num" => $value["arkam_alaakarat"],
                "file_engineer_id" => $value["rkm_mhnds_almaaaml"],
            ];
            TempPaidDeal::create($paidDealData);
        }
        $file->update([
            'total_failed' => 0,
            'total_successfully' => 0,
            'total_file_records' => count($rows),
            'file_status' => trans('crudbooster.file_status.in_importing'),
        ]);
        exit;
    }

    public function writePaidStaysData($file)
    {
        $engineers = DB::table('cms_users')->where("id_cms_privileges", 2)->pluck("id", "num")->toArray();
        $tempItems = TempPaidDeal::where('operation_id', $file->id)->orderBy('id')->get();
        foreach ($tempItems as $value) {
            try {
                if (!$value["file_num"] || !$value["engineer_id"]) {
                    continue;
                }

                if (!$engineers[$value["engineer_id"]]) {
                    $file->update(['total_failed' => ($file->total_failed + 1)]);
                    continue;
                }

                $deal = Deal::with("deal_details")->where("file_num",$value["file_num"])->where("file_date",$value["file_date"])->first();
                if (!$deal && is_numeric($value["file_num"])) {
                    $deal = Deal::create([
                        "operation_id" => $file->id,
                        "file_num" => $value["file_num"],
                        "file_date" => $value["file_date"],
                        "owner_name" => $value["owner_name"],
                        "real_estate_area" => $value["real_estate_area"],
                        "confinement_area" => optional($value)["confinement_area"],
                        "real_estate_num" => $value["real_estate_num"],
                        "file_engineer_id" => $value["file_engineer_id"],
                    ]);
                }
                $engineer_id = $engineers[$value["engineer_id"]];
                $deal_id = $deal->id;
                $paidDealData = [
                    "operation_id" => $file->id,
                    "deal_id" => $deal_id,
                    "engineer_id" => $engineer_id,
                    "year" => $value["year"],
                    "month" => $value["month"],
                    "note_num" => $value["note_num"],
                    "note_date" => $value["note_date"],
                    "application_date" => $value["application_date"],
                    "total_amount" => $value["total_amount"],
                ];

                PaidDeal::create($paidDealData);
                if (!$deal->paid_month && count($deal->deal_details) > 0) {
                    $paidDeals = PaidDeal::where("deal_id", $deal_id)->orderby("year", "desc")->orderby("month", "desc")->get();
                    if ($paidDeals->count() == count($deal->deal_details)) {
                        Deal::where("id", $deal_id)->update([
                            "paid_month" => $paidDeals->last()->month,
                            "paid_year" => $paidDeals->last()->year,
                        ]);
                    }
                }
            } catch (\Exception $e) {
                Log::log("error", "Error Importing PaidDeal " . $e);
                $file->update(['total_failed' => ($file->total_failed + 1)]);
            }

            $value->delete();
        }
        $file->update([
            'file_status' => $file->total_failed == 0 ? trans('crudbooster.file_status.done') : trans('crudbooster.file_status.failed'),
            'total_successfully' => ($file->total_file_records - $file->total_failed),
            'updated_at' => Carbon::now(),
        ]);
    }

    public function readFinancialDealsData($file, $rows)
    {
        foreach ($rows as $value) {
            $financialDealData = [
                "operation_id" => $file->id,
                "engineer_id" => $value["rkm_almhnds"],
                "financial_year" => $value["alsn"],
                "financial_month" => $value["alshhr"],
                "factor" => $value["aaaml_alzmyl"],
                "effort" => $value["alataaab"],
                "financial_system" => $value["alntham_almaly"],
                "percent" => $value["alnsb"],
                "effort_percent" => $value["alhs"],
                "share_out" => $value["mkbod_mshtrk"],
                "share_in" => $value["mord_llmshtrk"],
                "veri_out" => $value["mkbod_tdkyk"],
                "resident_out" => $value["rdyat_mkym"],
                "folder_out" => $value["rdyat_edbar"],
                "supervision" => $value["ashraf"],
                "discount" => $value["hsmyat"],
                "compensation" => $value["taaoydat"],
                "total_amount" => $value["almkbod_alkly"],
                "notes" => $value["mlahthat"],
            ];
            TempFinancialDeals::create($financialDealData);
        }
        $file->update([
            'file_status' => trans('crudbooster.file_status.in_importing'),
            'total_successfully' => 0,
            'total_file_records' => count($rows),
            'total_failed' => 0
        ]);
        exit;
    }

    public function writeFinancialDealsData($file)
    {
        $engineers = DB::table('cms_users')->where("id_cms_privileges", 2)->pluck("id", "num")->toArray();
        $tempItems = TempFinancialDeals::where('operation_id', $file->id)->orderBy('id')->get();
        foreach ($tempItems as $value) {
            if (!$value["engineer_id"]) {
                $file->update(['total_failed' => ($file->total_failed + 1)]);
                continue;
            }
            $engineer_id = $engineers[$value["engineer_id"]];

            $financialDealData = [
                "operation_id" => $file->id,
                "engineer_id" => $engineer_id,
                "financial_year" => $value["financial_year"],
                "financial_month" => $value["financial_month"],
                "factor" => $value["factor"],
                "effort" => $value["effort"],
                "financial_system" => $value["financial_system"],
                "percent" => $value["percent"],
                "effort_percent" => $value["effort_percent"],
                "share_out" => $value["share_out"],
                "share_in" => $value["share_in"],
                "veri_out" => $value["veri_out"],
                "resident_out" => $value["resident_out"],
                "folder_out" => $value["folder_out"],
                "supervision" => $value["supervision"],
                "discount" => $value["discount"],
                "compensation" => $value["compensation"],
                "total_amount" => $value["total_amount"],
                "notes" => $value["notes"],
            ];
            try {
                $deal = FinancialDeal::create($financialDealData);
            } catch (\Exception $e) {
                Log::log("error", "Error Importing FinancialDeals " . $e->getMessage());
                $file->update(['total_failed' => ($file->total_failed + 1)]);
            }
            $value->delete();
        }
        $file->update([
            'file_status' => $file->total_failed == 0 ? trans('crudbooster.file_status.done') : trans('crudbooster.file_status.failed'),
            'total_successfully' => ($file->total_file_records - $file->total_failed),
            'updated_at' => Carbon::now(),
        ]);
    }

    public function checkTypeValidate($type, $headerRow)
    {
        $checkMap = null;
        switch ($type) {
            case trans('crudbooster.file_type.deals'):
                $dealsMap = ['rkm_mhnds_aldras' => 0, 'asm_mhnds_aldras' => 0, 'rkm_almaaaml' => 0, 'tarykh_almaaaml' => 0, 'rkm_otarykh_almthkr' => 0, 'noaa_almaaaml' => 0, 'mntk_alhsr' => 0, 'almntk_alaakary' => 0, 'sahb_alaalak' => 0, 'rkm_mhnds_almaaaml' => 0, 'asm_mhnds_almaaaml' => 0, 'hal_almaaaml' => 0, 'kym_aldras' => 0, 'noaa_aldras' => 0, 'rkm_alaakar' => 0, 'mblgh_aledbar' => 0, 'mblgh_alekam_moejl_alsrf' => 0, 'almntk_altnthymy' => 0, 'shhr_aleghlak' => 0, 'aaam_aleghlak' => 0, 'aadd_altoabk' => 0, 'mjmoaa_alrkhs' => 0, 'almsah_alejmaly' => 0];
                $checkMap = $dealsMap;
                break;
            case trans('crudbooster.file_type.paid_stays'):
                $paidstaysMap = ['tarykh_alttbyk' => 0, 'tarykh_almthkr' => 0, 'rkm_almhnds' => 0, 'asm_almhnds' => 0, 'tarykh_almaaaml' => 0, 'rkm_almaaaml' => 0, 'almblgh' => 0, 'alshhr' => 0, 'alaaam' => 0, 'sahb_alaalak' => 0, 'almntk_alaakary' => 0, 'arkam_alaakarat' => 0, 'rkm_mhnds_almaaaml' => 0, 'asm_mhnds_almaaaml' => 0];
                $checkMap = $paidstaysMap;
                break;
            case trans('crudbooster.file_type.monthly_ammounts'):
                $monthlyMap = ['alshhr' => 0, 'alsn' => 0, 'asm_almhnds' => 0, 'rkm_almhnds' => 0, 'aaaml_alzmyl' => 0, 'alataaab' => 0, 'mkbod_tdkyk' => 0, 'alntham_almaly' => 0, 'alhs' => 0, 'alnsb' => 0, 'ashraf' => 0, 'rdyat_mkym' => 0, 'rdyat_edbar' => 0, 'mkbod_mshtrk' => 0, 'mlahthat' => 0, 'hsmyat' => 0, 'taaoydat' => 0, 'mord_llmshtrk' => 0, 'almkbod_alkly' => 0];
                $checkMap = $monthlyMap;
                break;
            default: // personal
                $personalMap = ['number' => 0, 'name' => 0, 'status' => 0, 'cota' => 0];
                $checkMap = $personalMap;
                break;
        }
        foreach ($checkMap as $key => $header) {
            if (!in_array($key, $headerRow)) {
                return 0;
            }
        }
        return 1;
    }
}