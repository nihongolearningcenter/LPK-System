<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public function index()
    {
        $payments = Payment::where('student_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('student.pembayaran.index', compact('payments'));
    }

    public function show(Payment $payment)
    {
        if ($payment->student_id !== Auth::id()) {
            abort(403);
        }

        return view('student.pembayaran.show', compact('payment'));
    }

    public function confirm(Request $request, Payment $payment)
    {
        if ($payment->student_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'payment_proof'  => 'required|image|max:3072', // Max 3MB
            'payment_method' => 'required|in:transfer,tunai,lainnya',
        ]);

        $proofPath = $request->file('payment_proof')->store('payments', 'public');

        $payment->update([
            'payment_proof'  => $proofPath,
            'payment_method' => $request->payment_method,
            'status'         => 'partial', // Menunggu konfirmasi admin
            'notes'          => 'Bukti pembayaran diunggah oleh siswa pada ' . now()->format('d M Y, H:i'),
        ]);

        return redirect()->route('student.pembayaran.show', $payment)->with('success', 'Bukti pembayaran berhasil diunggah dan sedang diverifikasi admin!');
    }
}
