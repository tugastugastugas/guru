<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use App\Models\ActivityLog;
use App\Models\Guru;
use App\Models\UserHistory;
use App\Models\Keterlambatan;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;


class GuruController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function guru()
    {
        ActivityLog::create([
            'action' => 'create',
            'user_id' => Session::get('id'), // ID pengguna yang sedang login
            'description' => 'User Masuk Ke Buat Guru.',
        ]);

        $guru = Guru::all();
        echo view('header');
        echo view('menu');
        echo view('guru', compact('guru'));
        echo view('footer');
    }

    public function buat_guru(Request $request)
    {
        ActivityLog::create([
            'action' => 'create',
            'user_id' => Session::get('id'), // ID pengguna yang sedang login
            'description' => 'User Menambah Guru.',
        ]);

        try {
            // Validasi inputan
            $request->validate([
                'nama_guru' => 'required',
                'mapel_guru' => 'required',
            ]);

            // Simpan data ke tabel user
            $guru = new Guru(); // Ubah variabel dari $quiz menjadi $guru untuk kejelasan
            $guru->nama_guru = $request->input('nama_guru');
            $guru->mapel_guru = $request->input('mapel_guru'); // Enkripsi password

            // Simpan ke database
            $guru->save();

            // Redirect ke halaman lain
            return redirect()->back()->withErrors(['msg' => 'Berhasil Menambahkan Akun.']);
        } catch (\Exception $e) {
            // Redirect kembali dengan pesan kesalahan
            return redirect()->back()->withErrors(['msg' => 'Gagal menambahkan akun. Silakan coba lagi.']);
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
