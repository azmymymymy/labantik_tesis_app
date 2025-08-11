<?php

namespace App\Http\Controllers\DataPenelitian;

use App\Http\Controllers\Controller;
use App\Models\Angket_motivasi;
use App\Models\AngketMinat;
use App\Models\Kelas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AhpController extends Controller
{
    public function index()
    {
        $kelas = Kelas::all();
        return view('data_penelitian.ahp.index', compact('kelas'));
    }
}
