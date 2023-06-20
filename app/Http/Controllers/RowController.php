<?php

namespace App\Http\Controllers;

use App\Http\Requests\RowRequest;
use App\Models\Row;
use Illuminate\Http\Request;

class RowController extends Controller
{
    function index()
    {
        $rows = Row::all();

        return view('row', compact('rows'));
    }

    function store(RowRequest $request)
    {
        $validated = $request->validated();
    }
}
