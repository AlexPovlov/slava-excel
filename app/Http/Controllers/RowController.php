<?php

namespace App\Http\Controllers;

use App\Http\Requests\RowRequest;
use App\Models\Row;
use Illuminate\Http\Request;
use Inertia\Inertia;

class RowController extends Controller
{
    function index()
    {
        $rows = Row::all();

        return Inertia::render('Row', compact('rows'));
    }

    function store(RowRequest $request)
    {
        $validated = $request->validated();
    }
}
