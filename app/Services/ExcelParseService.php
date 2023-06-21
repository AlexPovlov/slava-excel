<?php

namespace App\Services;

use App\Models\Row;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class ExcelParseService
{
    private $redis;

    function __construct(){
        $this->redis = Redis::connection('cache');
    }

    function saveFile(UploadedFile $file)
    {
        $file_name = $file->getClientOriginalName();

        if(Storage::has('imports/'. $file_name))
            return 'imports/' . $file_name;

        return $file->storeAs('imports', $file_name);
    }

    function saveRows($file_path)
    {
        if (!Storage::has($file_path))
            throw new Exception('Файл не найден повторите попытку');

        $path = Storage::path($file_path);
        $spreadsheet = IOFactory::load($path);

        $worksheet = $spreadsheet->getActiveSheet();
        // $highestRow = $worksheet->getHighestRow();
        $highestRow = $this->highestRow($worksheet);
        $highestColumn = $worksheet->getHighestColumn();

        if($highestColumn != 'C') {
            throw new Exception("Проверьте длину строк документа");
        }

        $highestColumn++;

        $inserting = [];
        $inserting_row = [];
        $cache_id = 'excel_file_' . md5($file_path);

        $savedRows = $this->initCache($cache_id, $highestRow, $file_path);
        // dd($savedRows['value']);
        for ($row = $savedRows['value']; $row <= $highestRow; ++$row) {

            for ($col = 'B'; $col != $highestColumn; ++$col) {
                $value = $worksheet->getCell($col . $row)->getValue();

                if (empty($value)) {
                    $this->forgetCacheAndStorage($file_path, $cache_id);
                    Row::insert($inserting);
                    return true;
                }
                if($col == 'B')
                    $inserting_row['name'] = $value;
                if ($col == 'C')
                    $inserting_row['created_at'] = Carbon::parse(Date::excelToDateTimeObject($value))->toDateTimeString();
            }

            $inserting[] = $inserting_row;

            if($highestRow == $row or $row % 1002 == 0){
                try {
                    Row::insert($inserting);

                    $this->redis->setex($cache_id, 1000, json_encode([
                        'count' => $highestRow,
                        'path' => $file_path,
                        'value' => $row
                    ]));
                } catch (\Throwable $th) {
                    throw $th;
                }

                $inserting = [];
                // throw new Exception("Проверьте длину строк документа");
            }

            if ($highestRow == $row) {
                // $this->forgetCacheAndStorage($file_path, $cache_id);
            }
        }
    }

    function initCache($cache_id, $highestRow, $file_path)
    {
        if ($savedRows = $this->redis->get($cache_id)) {
            $savedRows = json_decode($savedRows, true);
            $savedRows['value']++;
        } else {
            $savedRows = [
                'count' => $highestRow,
                'path' => $file_path,
                'value' => 2
            ];
            $this->redis->setex($cache_id, 1000, json_encode($savedRows));
        }

        return $savedRows;
    }

    function highestRow($worksheet)
    {
        $B = $worksheet->rangeToArray('B');
        $nonEmptyRows = array_filter($B, fn ($value) => !empty($value[0]));
        return count($nonEmptyRows);
    }

    function forgetCacheAndStorage($path, $cache_id)
    {
        $this->redis->del($cache_id);
        Storage::delete($path);
    }
}
