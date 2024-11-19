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

        // $guru = Guru::all();
        $id_user = Session::get('id'); // ID pengguna yang sedang login
        $guru = DB::table('guru')
            ->leftJoin('ulasan', 'guru.id_guru', '=', 'ulasan.id_guru')
            ->select('guru.*', 'ulasan.kritikan', 'ulasan.pujian', 'ulasan.id_ulasan')
            ->get();


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

            $existingUlasan = Ulasan::where('id_user', $id_user)
                ->where('id_guru', $id_guru)
                ->exists();

            if ($existingUlasan) {
                // Menampilkan notifikasi bahwa ulasan sudah dibuat
                return redirect()->back()->with('notification', 'Anda sudah memberikan ulasan untuk guru ini.');
            }

            // Simpan data ke tabel user
            $ulasan = new Ulasan(); // Ubah variabel dari $quiz menjadi $ulasan untuk kejelasan
            $ulasan->kritikan = $request->input('kritikan');
            $ulasan->pujian = $request->input('pujian'); // Enkripsi password
            $ulasan->id_user = $id_user;
            $ulasan->id_guru = $id_guru;
            $ulasan->id_periode = $periodeAktif->id_periode;

            // Simpan ke database
            $ulasan->save();

            // Redirect ke halaman lain
            return redirect()->back()->with('notification', 'Berhasil menambahkan ulasan.');
        } catch (\Exception $e) {
            // Redirect kembali dengan pesan kesalahan
            Log::error('Gagal memperbarui periode: ' . $e->getMessage());
            Log::info('Request Input:', $request->all());
            return redirect()->back()->withErrors(['msg' => 'Gagal menambahkan akun. Silakan coba lagi.']);
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
                'id_ulasan' => 'required|exists:ulasan,id_ulasan',
            ]);

            // Cari ulasan berdasarkan ID
            $ulasan = Ulasan::findOrFail($request->input('id_ulasan'));

            // Perbarui data ulasan
            $ulasan->kritikan = $request->input('kritikan');
            $ulasan->pujian = $request->input('pujian');
            $ulasan->updated_at = now();
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
}
