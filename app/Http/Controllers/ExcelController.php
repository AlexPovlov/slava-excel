<?php

namespace App\Http\Controllers;

use App\Http\Requests\ExcelRequest;
use App\Jobs\ParseExcelJob;
use App\Services\ExcelParseService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;
use Inertia\Inertia;

class ExcelController extends Controller
{
    function index()
    {
        return Inertia::render('Excel');
    }

    function store(ExcelRequest $request, ExcelParseService $excel_service)
    {
        $validated = $request->validated();
        $file_path = $excel_service->saveFile($validated['file']);
        $excel_service->saveRows($file_path);
        // ParseExcelJob::dispatch($file_path);

        return back();
    }
}
