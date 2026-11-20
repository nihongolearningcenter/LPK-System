<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use App\Models\ClassRoom;
use App\Models\Program;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class CertificateController extends Controller
{
    public function index(Request $request)
    {
        $query = Certificate::with(['student', 'classRoom', 'program', 'issuer'])->latest('issue_date');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('certificate_number', 'like', "%{$search}%")
                  ->orWhere('certificate_name', 'like', "%{$search}%")
                  ->orWhere('verification_code', 'like', "%{$search}%")
                  ->orWhereHas('student', function ($sq) use ($search) {
                      $sq->where('name', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('program_id')) {
            $query->where('program_id', $request->program_id);
        }

        $certificates = $query->paginate(15)->withQueryString();
        $programs = Program::orderBy('title')->get();

        $stats = [
            'total'      => Certificate::count(),
            'this_year'  => Certificate::whereYear('issue_date', now()->year)->count(),
            'grade_a'    => Certificate::where('grade_letter', 'A')->count(),
        ];

        return view('admin.sertifikat.index', compact('certificates', 'programs', 'stats'));
    }

    public function create()
    {
        $students = User::where('role', 'student')->orderBy('name')->get();
        $classes  = ClassRoom::with('program')->orderBy('name')->get();
        $programs = Program::orderBy('title')->get();

        // Generate suggested certificate number
        $nextId = (Certificate::max('id') ?? 0) + 1;
        $suggestedNumber = 'CERT/LPK/' . date('Y') . '/' . str_pad($nextId, 4, '0', STR_PAD_LEFT);

        return view('admin.sertifikat.create', compact('students', 'classes', 'programs', 'suggestedNumber'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id'         => 'required|exists:users,id',
            'certificate_name'   => 'required|string|max:255',
            'certificate_number' => 'required|string|max:100|unique:certificates,certificate_number',
            'class_id'           => 'nullable|exists:classes,id',
            'program_id'         => 'nullable|exists:programs,id',
            'issue_date'         => 'required|date',
            'expiry_date'        => 'nullable|date|after_or_equal:issue_date',
            'final_grade'        => 'nullable|numeric|min:0|max:100',
            'grade_letter'       => 'nullable|string|max:5',
        ]);

        $verificationCode = strtoupper(Str::random(10));

        Certificate::create([
            'certificate_number' => $validated['certificate_number'],
            'student_id'         => $validated['student_id'],
            'class_id'           => $validated['class_id'] ?? null,
            'program_id'         => $validated['program_id'] ?? null,
            'certificate_name'   => $validated['certificate_name'],
            'issue_date'         => $validated['issue_date'],
            'expiry_date'        => $validated['expiry_date'] ?? null,
            'final_grade'        => $validated['final_grade'] ?? null,
            'grade_letter'       => $validated['grade_letter'] ?? null,
            'verification_code'  => $verificationCode,
            'issued_by'          => Auth::id(),
        ]);

        return redirect()->route('admin.sertifikat.index')
                         ->with('success', 'Sertifikat berhasil diterbitkan.');
    }

    public function show(Certificate $sertifikat)
    {
        $sertifikat->load(['student.studentProfile', 'classRoom', 'program', 'issuer']);
        return view('admin.sertifikat.show', ['certificate' => $sertifikat]);
    }

    public function destroy(Certificate $sertifikat)
    {
        $sertifikat->delete();
        return redirect()->route('admin.sertifikat.index')
                         ->with('success', 'Sertifikat berhasil dihapus.');
    }
}
