<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Series;

class SeriesController extends Controller
{
    public function index(){
        $series = Series::all();
        return response()->json([
            "series"=>$series
        ]);
    }
}
