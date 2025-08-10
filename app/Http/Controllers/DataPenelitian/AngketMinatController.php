<?php

namespace App\Http\Controllers\DataPenelitian;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AngketMinatController extends Controller
{
    public function index()
    {
        return view('data_penelitian.angket_minat.index');
    }
}
