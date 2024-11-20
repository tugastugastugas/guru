<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use App\Models\ActivityLog;
use App\Models\Guru;
use App\Models\Ulasan;
use App\Models\UserHistory;
use App\Models\Keterlambatan;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;


class LaporanController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function print_laporan()
    {
        ActivityLog::create([
            'action' => 'create',
            'user_id' => Session::get('id'), // ID pengguna yang sedang login
            'description' => 'User Masuk Ke Buat Guru.',
        ]);

        $id_user = Session::get('id');

        // Mengambil semua periode
        $periodes = DB::table('periode')->select('id_periode', 'nama_periode')->get();

        // Mengambil ulasan (optional: tambahkan filter jika periode dipilih)
        $ulasan = DB::table('ulasan')
            ->join('user', 'user.id_user', '=', 'ulasan.id_user')
            ->join('periode', 'periode.id_periode', '=', 'ulasan.id_periode')
            ->join('guru', 'guru.id_guru', '=', 'ulasan.id_guru')
            ->select(
                'user.username',
                'guru.id_guru',
                'guru.nama_guru',
                'guru.mapel_guru',
                'periode.nama_periode',
                'ulasan.kritikan',
                'ulasan.pujian'
            )
            ->when(request('id_periode'), function ($query) {
                $query->where('periode.id_periode', request('id_periode'));
            })
            ->orderBy('guru.id_guru')
            ->get();

        echo view('header');
        echo view('menu');
        echo view('print_laporan', compact('ulasan', 'periodes'));
        echo view('footer');
    }

    public function printLaporan(Request $request)
    {
        $id_periode = $request->input('id_periode');

        $ulasan = DB::table('ulasan')
            ->join('user', 'user.id_user', '=', 'ulasan.id_user')
            ->join('periode', 'periode.id_periode', '=', 'ulasan.id_periode')
            ->join('guru', 'guru.id_guru', '=', 'ulasan.id_guru')
            ->select(
                'user.username',
                'guru.id_guru',
                'guru.nama_guru',
                'guru.mapel_guru',
                'periode.nama_periode',
                'ulasan.kritikan',
                'ulasan.pujian'
            )
            ->when($id_periode, function ($query) use ($id_periode) {
                return $query->where('periode.id_periode', $id_periode);
            })
            ->orderBy('guru.id_guru')
            ->get();

        $pdf = \Barryvdh\Snappy\Facades\SnappyPdf::loadView('laporan.pdf', compact('ulasan'))
            ->setOption('no-images', false)
            ->setOption('enable-local-file-access', true);

        return $pdf->download('laporan.pdf');
    }
}
