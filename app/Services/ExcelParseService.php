<?php

namespace App\Services;

use App\Models\Row;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class ExcelParseService
{
    function saveFile(UploadedFile $file)
    {
        $name = md5($file->getClientOriginalName());
        $extension = $file->extension();
        $file_name = $name . "." . $extension;

        if(Storage::has('imports/'. $file_name))
            return 'imports/' . $file_name;

        return $file->storeAs('imports', $file_name);
    }

    function saveRows($file_name)
    {
        if (!Storage::has($file_name))
            throw new Exception('Файл не найден повторите попытку');

        $path = Storage::path($file_name);
        $spreadsheet = IOFactory::load($path);

        $worksheet = $spreadsheet->getActiveSheet();
        $highestRow = $worksheet->getHighestRow();
        $highestColumn = $worksheet->getHighestColumn();

        if($highestColumn != 'C') {
            throw new Exception("Проверьте длину строк документа");
        }

        $highestColumn++;

        $inserting = [];
        $inserting_row = [];

        $savedRows = Cache::remember($file_name, 1000, function () {
            return 2;
        });

        for ($row = $savedRows; $row <= $highestRow; ++$row) {
            for ($col = 'B'; $col != $highestColumn; ++$col) {

                $value = $worksheet->getCell($col . $row)->getValue();

                if (empty($value)) {
                    Cache::forget($file_name);
                    Storage::delete($file_name);
                    Row::insert($inserting);
                    return true;
                }

                if($col == 'B')
                    $inserting_row['name'] = $value;
                if ($col == 'C')
                    $inserting_row['created_at'] = Carbon::parse(Date::excelToDateTimeObject($value))->toDateTimeString();
            }

            $inserting[] = $inserting_row;

            if(($highestRow < 1002 and $highestRow == $row) or $row == 1002){
                Cache::add($file_name, $row, 1000);
                Row::insert($inserting);
                $inserting = [];
            }

            if ($highestRow == $row) {
                Cache::forget($file_name);
                Storage::delete($file_name);
            }
        }
    }
}
