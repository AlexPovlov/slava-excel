<?php

namespace App\Http\Controllers;

use App\Http\Requests\ExcelRequest;
use App\Services\ExcelParseService;
use Illuminate\Http\Request;

class ExcelController extends Controller
{
    function index()
    {
        return view('excel');
    }

    function store(ExcelRequest $request, ExcelParseService $excel_service)
    {
        $validated = $request->validated();
        $file = $excel_service->saveFile($validated['file']);
        $excel_service->saveRows($file);

        return back();
    }
}
