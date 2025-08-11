<?php

namespace App\Http\Controllers\DataPenelitian;

use App\Http\Controllers\Controller;
use App\Models\Angket_motivasi;
use App\Models\AngketMinat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AhpController extends Controller
{
    public function index(Request $request, $id)
    {
        $kelasId = $request->query('kelas_id');
        $angketMotivasi = Angket_motivasi::where('kelas_id', $id)
            ->select('kelas_id', DB::raw('AVG(total) as rata_rata'))
            ->groupBy('kelas_id')
            ->first();
        $angketMinat = Angket_motivasi::where('kelas_id', $id)
            ->select('kelas_id', DB::raw('AVG(total) as rata_rata'))
            ->groupBy('kelas_id')
            ->first();
        dump($angketMotivasi);
    }
}
