<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    protected string $settingsFile = 'settings.json';

    public function index()
    {
        $settings = $this->getSettings();
        return view('admin.pengaturan.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'institution_name' => 'required|string|max:255',
            'japanese_name'    => 'nullable|string|max:255',
            'permit_number'    => 'nullable|string|max:255',
            'director_name'    => 'nullable|string|max:255',
            'email'            => 'required|email',
            'phone'            => 'nullable|string|max:50',
            'whatsapp'         => 'nullable|string|max:50',
            'address'          => 'nullable|string',
            'bank_name'        => 'nullable|string|max:100',
            'bank_account'     => 'nullable|string|max:100',
            'bank_holder'      => 'nullable|string|max:100',
            'registration_fee' => 'nullable|numeric|min:0',
            'academic_year'    => 'nullable|string|max:50',
        ]);

        Storage::put($this->settingsFile, json_encode($validated, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

        return redirect()->route('admin.pengaturan.index')
                         ->with('success', 'Pengaturan sistem berhasil disimpan.');
    }

    private function getSettings(): array
    {
        if (Storage::exists($this->settingsFile)) {
            $data = json_decode(Storage::get($this->settingsFile), true);
            if (is_array($data)) {
                return $data;
            }
        }

        return [
            'institution_name' => 'LPK Nihongo Learning Center',
            'japanese_name'    => '日本語学習センター',
            'permit_number'    => '560/342/LPK/2024',
            'director_name'    => 'Kenji Sato, M.Ed.',
            'email'            => 'info@nihongolc.id',
            'phone'            => '021-7890123',
            'whatsapp'         => '0812-3456-7890',
            'address'          => 'Jl. Raya Sakura No. 12, Kebayoran Baru, Jakarta Selatan',
            'bank_name'        => 'Bank Central Asia (BCA)',
            'bank_account'     => '8820123456',
            'bank_holder'      => 'LPK Nihongo Learning Center',
            'registration_fee' => 250000,
            'academic_year'    => '2026/2027',
        ];
    }
}
