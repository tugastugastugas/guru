<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use App\Models\ActivityLog;
use App\Models\Periode;
use App\Models\UserHistory;
use App\Models\Keterlambatan;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;


class PeriodeController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function periode()
    {
        ActivityLog::create([
            'action' => 'create',
            'user_id' => Session::get('id'), // ID pengguna yang sedang login
            'description' => 'User Masuk Ke Buat Periode.',
        ]);

        $periode = Periode::all();
        echo view('header');
        echo view('menu');
        echo view('periode', compact('periode'));
        echo view('footer');
    }

    public function buat_periode(Request $request)
    {
        ActivityLog::create([
            'action' => 'create',
            'user_id' => Session::get('id'), // ID pengguna yang sedang login
            'description' => 'User Menambah Periode.',
        ]);

        try {
            // Validasi inputan
            $request->validate([
                'nama_periode' => 'required',
                'tgl_mulai' => 'required',
                'tgl_akhir' => 'required',
            ]);

            // Simpan data ke tabel user
            $periode = new Periode(); // Ubah variabel dari $quiz menjadi $periode untuk kejelasan
            $periode->nama_periode = $request->input('nama_periode');
            $periode->tgl_mulai = $request->input('tgl_mulai'); // Enkripsi password
            $periode->tgl_akhir = $request->input('tgl_akhir');
            $periode->status = "TIDAK AKTIF";

            // Simpan ke database
            $periode->save();

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
            'description' => 'User Mengupdate Periode.',
        ]);

        try {
            $request->validate([
                'nama_periode' => 'required|string',
                'tgl_mulai' => 'required|date',
                'tgl_akhir' => 'required|date',
            ]);

            $id_user = Session::get('id');
            $periode = Periode::findOrFail($id);

            // Update data paket
            $periode->nama_periode = $request->nama_periode;
            $periode->tgl_mulai = $request->tgl_mulai;
            $periode->tgl_akhir = $request->tgl_akhir;

            // Simpan perubahan ke database
            $periode->save();

            return redirect()->back()->with('success', 'Pengajuan periode berhasil diperbarui');
        } catch (\Exception $e) {
            Log::error('Gagal memperbarui periode: ' . $e->getMessage());
            Log::info('Request Input:', $request->all());
            return redirect()->back()->withErrors(['msg' => 'Gagal memperbarui periode. Silakan coba lagi.']);
        }
    }

    public function periode_destroy($id)
    {
        ActivityLog::create([
            'action' => 'create',
            'user_id' => Session::get('id'), // ID pengguna yang sedang login
            'description' => 'User Menghapus Periode.',
        ]);

        $periode = Periode::findOrFail($id);

        // Hapus data periode (soft delete)
        $periode->delete();

        // Redirect dengan pesan sukses
        return redirect()->route('periode')->with('success', 'Data user berhasil dihapus');
    }


    public function aktif($id)
{
    ActivityLog::create([
        'action' => 'create',
        'user_id' => Session::get('id'), // ID pengguna yang sedang login
        'description' => 'User Mengaktifkan Periode.',
    ]);

    try {
        // Dapatkan ID user dari session
        $id_user = Session::get('id');

        // Temukan periode berdasarkan ID yang diberikan
        $periode = Periode::findOrFail($id);

        // Menonaktifkan semua periode yang statusnya "AKTIF"
        $periodeLain = Periode::where('status', 'AKTIF')->get();
        foreach ($periodeLain as $item) {
            $item->status = 'TIDAK AKTIF';
            $item->save();
        }

        // Mengupdate periode yang dipilih menjadi "AKTIF"
        $periode->status = "AKTIF";
        $periode->save();

        return redirect()->back()->with('success', 'Pengajuan paket berhasil diperbarui');
    } catch (\Exception $e) {
        Log::error('Gagal: ' . $e->getMessage());

        return redirect()->back()->withErrors(['msg' => 'Gagal memperbarui paket. Silakan coba lagi.']);
    }
}


    public function tidak_aktif($id)
    {
        ActivityLog::create([
            'action' => 'create',
            'user_id' => Session::get('id'), // ID pengguna yang sedang login
            'description' => 'User Menonaktifkan Pemesanan Periode.',
        ]);
        try {
            $id_user = Session::get('id');
            $periode = Periode::findOrFail($id);

            // Update data paket
            $periode->status = "TIDAK AKTIF";

            // Simpan perubahan ke database
            $periode->save();

            return redirect()->back()->with('success', 'Pengajuan paket berhasil diperbarui');
        } catch (\Exception $e) {
            Log::error('Gagal: ' . $e->getMessage());

            return redirect()->back()->withErrors(['msg' => 'Gagal memperbarui paket. Silakan coba lagi.']);
        }
    }
}
