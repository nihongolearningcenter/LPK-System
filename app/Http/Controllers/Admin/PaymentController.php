<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\User;
use App\Models\ClassRoom;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $query = Payment::with(['student', 'classRoom'])->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('invoice_number', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%')
                  ->orWhereHas('student', fn($sq) => $sq->where('name', 'like', '%' . $request->search . '%'));
            });
        }

        $payments = $query->paginate(15)->withQueryString();

        $stats = [
            'total'    => Payment::sum('amount'),
            'paid'     => Payment::where('status', 'paid')->sum('amount'),
            'unpaid'   => Payment::where('status', 'unpaid')->sum('amount'),
            'pending'  => Payment::where('status', 'partial')->count(),
        ];

        return view('admin.pembayaran.index', compact('payments', 'stats'));
    }

    public function create()
    {
        $students = User::where('role', 'student')->where('is_active', true)->get();
        $classes = ClassRoom::where('status', 'active')->get();

        return view('admin.pembayaran.create', compact('students', 'classes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id'     => 'required|exists:users,id',
            'class_id'       => 'nullable|exists:classes,id',
            'description'    => 'required|string|max:255',
            'amount'         => 'required|numeric|min:0',
            'due_date'       => 'nullable|date',
            'payment_method' => 'nullable|in:tunai,transfer,lainnya',
            'status'         => 'required|in:unpaid,paid,partial,cancelled',
        ]);

        $validated['invoice_number'] = 'INV-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -4));
        $validated['recorded_by']    = auth()->id();

        if ($validated['status'] === 'paid') {
            $validated['paid_at'] = now();
            $validated['amount_paid'] = $validated['amount'];
        }

        $payment = Payment::create($validated);

        if ($payment->status === 'paid' && $payment->class_id) {
            \App\Models\ClassStudent::firstOrCreate(
                ['class_id' => $payment->class_id, 'student_id' => $payment->student_id],
                ['joined_at' => now(), 'status' => 'active']
            );
        }

        return redirect()->route('admin.pembayaran.index')->with('success', 'Tagihan invoice berhasil diterbitkan!');
    }

    public function show(Payment $pembayaran)
    {
        $pembayaran->load(['student.studentProfile', 'classRoom.program', 'recorder']);
        return view('admin.pembayaran.show', compact('pembayaran'));
    }

    public function update(Request $request, Payment $pembayaran)
    {
        $validated = $request->validate([
            'status' => 'required|in:unpaid,paid,partial,cancelled',
            'notes'  => 'nullable|string',
        ]);

        if ($validated['status'] === 'paid' && $pembayaran->status !== 'paid') {
            $validated['paid_at'] = now();
            $validated['amount_paid'] = $pembayaran->amount;

            if ($pembayaran->class_id) {
                \App\Models\ClassStudent::firstOrCreate(
                    ['class_id' => $pembayaran->class_id, 'student_id' => $pembayaran->student_id],
                    ['joined_at' => now(), 'status' => 'active']
                );
            }
        }

        $pembayaran->update($validated);

        return redirect()->back()->with('success', 'Status pembayaran berhasil diperbarui!');
    }

    public function destroy(Payment $pembayaran)
    {
        $pembayaran->delete();
        return redirect()->route('admin.pembayaran.index')->with('success', 'Tagihan pembayaran berhasil dihapus.');
    }
}
