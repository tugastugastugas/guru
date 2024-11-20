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


class UlasanController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function ulasan()
    {
        ActivityLog::create([
            'action' => 'create',
            'user_id' => Session::get('id'), // ID pengguna yang sedang login
            'description' => 'User Masuk Ke Buat Guru.',
        ]);
        $id_user = Session::get('id');
        // Ambil periode yang aktif
        $periode_aktif = DB::table('periode')
            ->where('status', 'AKTIF')
            ->first();  // Ambil satu periode yang aktif


        if ($periode_aktif) {
            // Jika periode aktif ditemukan, ambil guru dan ulasan berdasarkan periode tersebut
            $guru = DB::table('guru')
                ->leftJoin('ulasan', function ($join) use ($id_user, $periode_aktif) {
                    $join->on('guru.id_guru', '=', 'ulasan.id_guru')
                        ->where('ulasan.id_user', '=', $id_user)
                        ->where('ulasan.id_periode', '=', $periode_aktif->id_periode);
                })
                ->select('guru.*', 'ulasan.kritikan', 'ulasan.pujian', 'ulasan.id_ulasan')
                ->get();
        } else {
            $guru = collect(); // Jika tidak ada periode aktif, kosongkan daftar guru
        }

        echo view('header');
        echo view('menu');
        echo view('ulasan', compact('guru'));
        echo view('footer');
    }



    public function buat_ulasan(Request $request)
    {
        ActivityLog::create([
            'action' => 'create',
            'user_id' => Session::get('id'), // ID pengguna yang sedang login
            'description' => 'User Menambah Ulasan.',
        ]);

        try {
            // Validasi inputan
            $request->validate([
                'kritikan' => 'required',
                'pujian' => 'required',
                'id_guru' => 'required',
            ]);

            $id_user = Session::get('id');
            $id_guru = $request->input('id_guru');

            // Ambil id_periode dengan status AKTIF
            $periodeAktif = DB::table('periode')->where('status', 'AKTIF')->first();
            if (!$periodeAktif) {
                return redirect()->back()->withErrors(['msg' => 'Tidak ada periode aktif saat ini.']);
            }

            // Periksa apakah user sudah memberikan ulasan untuk guru ini pada periode yang aktif
            $existingUlasan = Ulasan::where('id_user', $id_user)
                ->where('id_guru', $id_guru)
                ->where('id_periode', $periodeAktif->id_periode) // Tambahkan pengecekan id_periode
                ->exists();

            if ($existingUlasan) {
                // Menampilkan notifikasi bahwa ulasan sudah dibuat
                return redirect()->back()->with('notification', 'Anda sudah memberikan ulasan untuk guru ini pada periode ini.');
            }

            // Simpan data ke tabel ulasan
            $ulasan = new Ulasan();
            $ulasan->kritikan = $request->input('kritikan');
            $ulasan->pujian = $request->input('pujian');
            $ulasan->id_user = $id_user;
            $ulasan->id_guru = $id_guru;
            $ulasan->id_periode = $periodeAktif->id_periode;

            // Simpan ke database
            $ulasan->save();

            // Redirect ke halaman lain
            return redirect()->back()->with('notification', 'Berhasil menambahkan ulasan.');
        } catch (\Exception $e) {
            // Redirect kembali dengan pesan kesalahan
            Log::error('Gagal menambahkan ulasan: ' . $e->getMessage());
            Log::info('Request Input:', $request->all());
            return redirect()->back()->withErrors(['msg' => 'Gagal menambahkan ulasan. Silakan coba lagi.']);
        }
    }


    public function gantiUlasan(Request $request)
    {

        ActivityLog::create([
            'action' => 'update',
            'user_id' => Session::get('id'), // ID pengguna yang sedang login
            'description' => 'User Mengganti Ulasan.',
        ]);

        try {
            // Validasi inputan
            $request->validate([
                'kritikan' => 'required',
                'pujian' => 'required',
                'id_ulasan' => 'required',
            ]);

            // Cari ulasan berdasarkan ID
            $ulasan = Ulasan::findOrFail($request->input('id_ulasan'));

            // Perbarui data ulasan
            $ulasan->kritikan = $request->input('kritikan');
            $ulasan->pujian = $request->input('pujian');
            $ulasan->save();

            // Redirect dengan notifikasi berhasil
            return redirect()->back()->with('notification', 'Ulasan berhasil diperbarui.');
        } catch (\Exception $e) {
            Log::error('Gagal mengganti ulasan: ' . $e->getMessage());
            return redirect()->back()->withErrors(['msg' => 'Gagal mengganti ulasan. Silakan coba lagi.']);
        }
    }


    public function update(Request $request, $id)
    {
        ActivityLog::create([
            'action' => 'create',
            'user_id' => Session::get('id'), // ID pengguna yang sedang login
            'description' => 'User Mengupdate Guru.',
        ]);

        try {
            $request->validate([
                'nama_guru' => 'required|string',
                'mapel_guru' => 'required|string',
            ]);

            $id_user = Session::get('id');
            $guru = Guru::findOrFail($id);

            // Update data paket
            $guru->nama_guru = $request->nama_guru;
            $guru->mapel_guru = $request->mapel_guru;

            // Simpan perubahan ke database
            $guru->save();

            return redirect()->back()->with('success', 'Pengajuan periode berhasil diperbarui');
        } catch (\Exception $e) {
            Log::error('Gagal memperbarui periode: ' . $e->getMessage());
            Log::info('Request Input:', $request->all());
            return redirect()->back()->withErrors(['msg' => 'Gagal memperbarui periode. Silakan coba lagi.']);
        }
    }

    public function guru_destroy($id)
    {
        ActivityLog::create([
            'action' => 'create',
            'user_id' => Session::get('id'), // ID pengguna yang sedang login
            'description' => 'User Menghapus Guru.',
        ]);

        $guru = Guru::findOrFail($id);

        // Hapus data guru (soft delete)
        $guru->delete();

        // Redirect dengan pesan sukses
        return redirect()->route('guru')->with('success', 'Data user berhasil dihapus');
    }

    public function history()
    {
        ActivityLog::create([
            'action' => 'create',
            'user_id' => Session::get('id'), // ID pengguna yang sedang login
            'description' => 'User Masuk Ke Buat Guru.',
        ]);

        $id_user = Session::get('id');

        // Mengambil data dengan pengelompokan berdasarkan periode
        $ulasan = DB::table('ulasan')
            ->join('user', 'user.id_user', '=', 'ulasan.id_user')
            ->join('periode', 'periode.id_periode', '=', 'ulasan.id_periode')
            ->join('guru', 'guru.id_guru', '=', 'ulasan.id_guru')
            ->select(
                'user.username',
                'guru.nama_guru',
                'guru.mapel_guru',
                'periode.nama_periode',
                'ulasan.kritikan',
                'ulasan.pujian'
            )
            ->where('user.id_user', $id_user) // Mengambil data sesuai session id_user
            ->groupBy('periode.nama_periode', 'user.username', 'guru.nama_guru', 'guru.mapel_guru', 'ulasan.kritikan', 'ulasan.pujian') // Mengelompokkan berdasarkan periode
            ->get();

        echo view('header');
        echo view('menu');
        echo view('history', compact('ulasan'));
        echo view('footer');
    }


    public function ulasan_guru()
    {
        ActivityLog::create([
            'action' => 'create',
            'user_id' => Session::get('id'), // ID pengguna yang sedang login
            'description' => 'User Masuk Ke Buat Guru.',
        ]);

        $id_user = Session::get('id');

        // Mengambil data dengan pengelompokan berdasarkan periode
        $ulasan = DB::table('ulasan')
            ->join('user', 'user.id_user', '=', 'ulasan.id_user')
            ->join('periode', 'periode.id_periode', '=', 'ulasan.id_periode')
            ->join('guru', 'guru.id_guru', '=', 'ulasan.id_guru')
            ->select(
                'user.username',
                'guru.nama_guru',
                'guru.mapel_guru',
                'periode.nama_periode',
                'ulasan.kritikan',
                'ulasan.pujian'
            )
            ->groupBy('periode.nama_periode', 'user.username', 'guru.nama_guru', 'guru.mapel_guru', 'ulasan.kritikan', 'ulasan.pujian') // Mengelompokkan berdasarkan periode
            ->get();

        echo view('header');
        echo view('menu');
        echo view('ulasan_guru', compact('ulasan'));
        echo view('footer');
    }

    public function ulasan_saya()
    {
        ActivityLog::create([
            'action' => 'create',
            'user_id' => Session::get('id'), // ID pengguna yang sedang login
            'description' => 'User Masuk Ke Buat Guru.',
        ]);

        $id_user = Session::get('id');

        // Mengambil data dengan pengelompokan berdasarkan periode
        $ulasan = DB::table('ulasan')
            ->join('user', 'user.id_user', '=', 'ulasan.id_user')
            ->join('periode', 'periode.id_periode', '=', 'ulasan.id_periode')
            ->join('guru', 'guru.id_guru', '=', 'ulasan.id_guru')
            ->select(
                'user.username',
                'guru.nama_guru',
                'guru.mapel_guru',
                'periode.nama_periode',
                'ulasan.kritikan',
                'ulasan.pujian',
                'ulasan.id_guru'
            )
    
            ->groupBy('periode.nama_periode', 'user.username', 'guru.nama_guru', 'guru.mapel_guru', 'ulasan.kritikan', 'ulasan.pujian', 'ulasan.id_guru') // Mengelompokkan berdasarkan periode
            ->get();

        echo view('header');
        echo view('menu');
        echo view('ulasan_saya', compact('ulasan'));
        echo view('footer');
    }
}
