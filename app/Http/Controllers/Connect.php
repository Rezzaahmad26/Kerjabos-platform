<?php

namespace App\Http\Controllers;

use App\Models\cr;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\ConnectTopup;
use App\Models\User;

class Connect extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function connect()
    {
        $authUser = auth()->user();
        $connect = ConnectTopup::where('user_id', $authUser->id)
            ->orderByDesc('id')
            ->get();
        return view('front.connect', compact('connect'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $request->validate([
            'payment_proof' => 'required|image|max:2048|mimetypes:image/jpeg,image/png,image/gif,image/webp',
        ]);

        $user = auth()->user();

        if (!$user->hasPermissionTo('topup connect')) {
            abort(403, 'Unauthorized');
        }

        // Upload bukti transfer
        $path = $request->file('payment_proof')->store('payment_proofs', 'public');


        ConnectTopup::create([
            'user_id' => $user->id,
            'connect_amount' => 10,
            'price' => 100000,
            'is_paid' => false, // default: belum diverifikasi
            'payment_proof' => $path,
        ]);


        return redirect()->route('dashboard.connect')->with('success', 'Permintaan topup connect berhasil dikirim. Menunggu verifikasi admin.');
    }


         public function adminTopupIndex()
            {
                $topups = ConnectTopup::with('user')->orderByDesc('created_at')->get();
                return view('admin.connect_topups.index', compact('topups'));
            }

        public function approveTopup($id)
        {
            $topup = ConnectTopup::findOrFail($id);
            if (!$topup->is_paid) {
                $topup->update(['is_paid' => true]);
                $topup->user->increment('connect', $topup->connect_amount);
            }

            return redirect()->back()->with('success', 'Topup connect berhasil diverifikasi.');
        }
    /**
     * Display the specified resource.
     */
    public function show(cr $cr)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(cr $cr)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, cr $cr)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(cr $cr)
    {
        //
    }
}
