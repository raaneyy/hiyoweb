<?php

namespace App\Http\Controllers;

use App\Models\Diary;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function landing()
    {
        if (session('is_reader_verified') || session('is_admin')) {
            $diary = Diary::first();
            if ($diary) {
                return redirect()->route('diary.show', $diary->id);
            }
        }

        return view('landing');
    }

    public function showLogin(Request $request)
    {
        $activeTab = $request->query('tab', 'reader');

        return view('auth.login', compact('activeTab'));
    }

    public function verifyReader(Request $request)
    {
        $request->validate([
            'hari' => 'required|numeric|min:1|max:31',
            'bulan' => 'required|numeric|min:1|max:12',
            'tahun' => 'required|numeric',
        ]);

        $hari = (int) $request->hari;
        $bulan = (int) $request->bulan;
        $tahun = (int) $request->tahun;

        $isValidDate = ($hari === 6 && $bulan === 3 && $tahun === 2026);

        if ($isValidDate) {
            session(['is_reader_verified' => true]);

            $diary = Diary::first();
            $diaryId = $diary ? $diary->id : 1;

            return redirect()->route('diary.show', $diaryId)
                ->with('success', 'Selamat Membaca❤️');
        }

        return back()
            ->withInput()
            ->with('error', 'Tanggal verifikasi salah!Fix ga sayang.');
    }

    public function verifyAdmin(Request $request)
    {
        $request->validate([
            'password' => 'required|string',
        ]);

        $adminPassword = config('app.admin_password', 'admin123');

        if ($request->password === $adminPassword) {
            session([
                'is_admin' => true,
                'is_reader_verified' => true,
            ]);

            return redirect()->route('admin.pages.index')
                ->with('success', 'Silahkan tambahkan cerita kamu.');
        }

        return back()
            ->withInput()
            ->with('admin_error', 'Password Admin salah!');
    }

    public function logout()
    {
        session()->forget(['is_reader_verified', 'is_admin']);

        return redirect()->route('landing')
            ->with('success', 'Anda telah keluar.');
    }
}
