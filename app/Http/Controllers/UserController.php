<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use App\Models\ActivityLog;
use App\Models\User;
use App\Models\UserHistory;
use App\Models\Keterlambatan;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;


class UserController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function user()
    {
        ActivityLog::create([
            'action' => 'create',
            'user_id' => Session::get('id'), // ID pengguna yang sedang login
            'description' => 'User Masuk Ke User.',
        ]);
        $user = User::all();
        echo view('header');
        echo view('menu');
        echo view('user', compact('user'));
        echo view('footer');
    }

    public function t_user(Request $request)
    {
        ActivityLog::create([
            'action' => 'create',
            'user_id' => Session::get('id'), // ID pengguna yang sedang login
            'description' => 'User Menambah User.',
        ]);

        try {
            // Validasi inputan
            $request->validate([
                'username' => 'required',
                'password' => 'required',
                'level' => 'required',
            ]);

            // Simpan data ke tabel user
            $user = new User(); // Ubah variabel dari $quiz menjadi $user untuk kejelasan
            $user->username = $request->input('username');
            $user->password = md5($request->input('password')); // Enkripsi password
            $user->level = $request->input('level');

            // Simpan ke database
            $user->save();

            // Redirect ke halaman lain
            return redirect()->back()->withErrors(['msg' => 'Berhasil Menambahkan Akun.']);
        } catch (\Exception $e) {
            // Redirect kembali dengan pesan kesalahan
            return redirect()->back()->withErrors(['msg' => 'Gagal menambahkan akun. Silakan coba lagi.']);
        }
    }

    public function t_murid(Request $request)
    {
        ActivityLog::create([
            'action' => 'create',
            'user_id' => Session::get('id'), // ID pengguna yang sedang login
            'description' => 'User Menambah User.',
        ]);

        try {
            // Validasi inputan
            $request->validate([
                'username' => 'required',
                'password' => 'required',
                'nis' => 'required',
            ]);

            // Simpan data ke tabel user
            $user = new User(); // Ubah variabel dari $quiz menjadi $user untuk kejelasan
            $user->username = $request->input('username');
            $user->password = md5($request->input('password')); // Enkripsi password
            $user->nis = $request->input('nis');
            $user->level = 'Murid';

            // Simpan ke database
            $user->save();

            // Redirect ke halaman lain
            return redirect()->back()->withErrors(['msg' => 'Berhasil Menambahkan Akun.']);
        } catch (\Exception $e) {
            Log::error('Gagal: ' . $e->getMessage());

            // Redirect kembali dengan pesan kesalahan
            return redirect()->back()->withErrors(['msg' => 'Gagal menambahkan akun. Silakan coba lagi.']);
        }
    }


    public function user_destroy($id)
    {
        ActivityLog::create([
            'action' => 'create',
            'user_id' => Session::get('id'), // ID pengguna yang sedang login
            'description' => 'User Menghapus User.',
        ]);
        // Cari data user berdasarkan ID
        $user = User::findOrFail($id);

        // Set kolom deleted_at ke waktu saat ini
        $user->deleted_at = now();
        $user->save(); // Simpan perubahan


        // Redirect dengan pesan sukses
        return redirect()->route('user')->with('success', 'Data user berhasil dihapus');
    }


    public function resetPassword($id)
    {
        ActivityLog::create([
            'action' => 'create',
            'user_id' => Session::get('id'), // ID pengguna yang sedang login
            'description' => 'User Mereset Password User.',
        ]);

        try {
            // Mencari pengguna berdasarkan ID
            $user = User::findOrFail($id);

            // Mengatur ulang password ke '1'
            $user->password = md5('1'); // Enkripsi password

            // Simpan perubahan ke database
            $user->save();

            // Redirect dengan pesan sukses
            return redirect()->back()->with('success', 'Password berhasil direset menjadi 1.');
        } catch (\Exception $e) {
            // Log kesalahan
            Log::error('Gagal mereset password: ' . $e->getMessage());

            // Redirect kembali dengan pesan kesalahan
            return redirect()->back()->withErrors(['msg' => 'Gagal mereset password. Silakan coba lagi.']);
        }
    }

    public function detail($id)
    {
        ActivityLog::create([
            'action' => 'create',
            'user_id' => Session::get('id'), // ID pengguna yang sedang login
            'description' => 'User Masuk Ke Detail.',
        ]);

        // Mencari pengguna berdasarkan ID
        $user = User::findOrFail($id);

        // Mengembalikan view dengan data pengguna dan level
        echo view('header');
        echo view('menu');
        echo view('detail', compact('user'));
        echo view('footer');
    }

    public function updateDetail(Request $request)
    {
        ActivityLog::create([
            'action' => 'create',
            'user_id' => Session::get('id'), // ID pengguna yang sedang login
            'description' => 'User Mengupdate User.',
        ]);

        try {
            // Validasi input
            $request->validate([
                'username' => 'required',
                'level' => 'required',
                // Validasi lain sesuai kebutuhan
            ]);

            // Mencari user berdasarkan ID
            $user = User::findOrFail($request->input('id'));

            // Simpan versi lama ke tabel user_history
            UserHistory::create([
                'id_user' => $user->id_user,
                'username' => $user->username,
                'nis' => $user->nis,
                'level' => $user->level,
                'created_at' => $user->created_at,
                'updated_at' => $user->updated_at,
            ]);

            // Perbarui data user
            $user->username = $request->input('username');
            $user->nis = $request->input('nis');
            $user->level = $request->input('level');
            $user->save();

            // Redirect dengan pesan sukses
            return redirect()->route('user', $user->id)->with('success', 'Detail pengguna berhasil diperbarui.');
        } catch (\Exception $e) {
            // Log error
            Log::error('Gagal memperbarui detail pengguna: ' . $e->getMessage());

            // Redirect kembali dengan pesan kesalahan
            return redirect()->back()->withErrors(['msg' => 'Gagal memperbarui detail pengguna. Silakan coba lagi.']);
        }
    }

    public function changePassword(Request $request)
{
    try {
    // Validasi input
    $request->validate([
        'old_password' => 'required',
        'new_password' => 'required',
        'confirm_password' => 'required|same:new_password',
    ]);
   
    // Ambil id_user dari session
    $id_user = session()->get('id');

    if (!$id_user) {
        return back()->with('error', 'Session expired. Please log in again.');
    }

    // Ambil data user dari database
    $user = DB::table('user')->where('id_user', $id_user)->first();
    if (!$user) {
        return back()->with('error', 'User not found.');
    }

    // Cocokkan old password
    if ($user->password !== md5($request->old_password)) {
        return back()->with('error', 'Old password is incorrect.');
    }

    

    // Update password
    $update = DB::table('user')
        ->where('id_user', $id_user)
        ->update([
            'password' => md5($request->new_password),
            'updated_at' => now(),
        ]);

  
        return back()->with('success', 'Password updated.');
    
} catch (\Exception $e) {
    Log::error('Gagal : ' . $e->getMessage());
    
    return back()->with('success', 'Password has been updated successfully.');
}
}

}
