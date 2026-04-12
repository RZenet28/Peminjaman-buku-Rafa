<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class SettingsController extends Controller
{
    /**
     * Display settings page
     */
    public function index()
    {
        $settings = [
            'library_name' => Cache::get('library_name', 'BookHub Library'),
            'library_address' => Cache::get('library_address', ''),
            'library_phone' => Cache::get('library_phone', ''),
            'library_email' => Cache::get('library_email', ''),
            'borrowing_period' => Cache::get('borrowing_period', 7),
            'fine_per_day' => Cache::get('fine_per_day', 5000),
            'max_books_per_user' => Cache::get('max_books_per_user', 3),
        ];

        return view('admin.settings.index', compact('settings'));
    }

    /**
     * Update settings
     */
    public function update(Request $request)
    {
        $request->validate([
            'library_name' => 'required|string|max:255',
            'library_address' => 'nullable|string|max:500',
            'library_phone' => 'nullable|string|max:20',
            'library_email' => 'nullable|email',
            'borrowing_period' => 'required|integer|min:1|max:30',
            'fine_per_day' => 'required|integer|min:0',
            'max_books_per_user' => 'required|integer|min:1|max:10',
        ]);

        Cache::put('library_name', $request->library_name);
        Cache::put('library_address', $request->library_address);
        Cache::put('library_phone', $request->library_phone);
        Cache::put('library_email', $request->library_email);
        Cache::put('borrowing_period', $request->borrowing_period);
        Cache::put('fine_per_day', $request->fine_per_day);
        Cache::put('max_books_per_user', $request->max_books_per_user);

        return back()->with('success', 'Pengaturan berhasil diperbarui!');
    }

    /**
     * Display system info
     */
    public function systemInfo()
    {
        $info = [
            'app_version' => config('app.version', '1.0.0'),
            'laravel_version' => app()->version(),
            'php_version' => phpversion(),
            'database' => config('database.default'),
        ];

        return view('admin.settings.system-info', compact('info'));
    }
}
