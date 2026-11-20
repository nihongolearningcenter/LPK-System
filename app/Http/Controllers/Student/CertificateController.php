<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CertificateController extends Controller
{
    public function index()
    {
        $certificates = Certificate::with(['program', 'classRoom'])
            ->where('student_id', Auth::id())
            ->latest('issue_date')
            ->get();

        return view('student.sertifikat.index', compact('certificates'));
    }

    public function download(Certificate $certificate)
    {
        if ($certificate->student_id !== Auth::id() && !Auth::user()->isAdmin()) {
            abort(403);
        }

        if ($certificate->file_path && Storage::disk('public')->exists($certificate->file_path)) {
            return Storage::disk('public')->download($certificate->file_path);
        }

        $certificate->load(['student.studentProfile', 'classRoom', 'program', 'issuer']);
        return view('admin.sertifikat.show', compact('certificate'));
    }
}
