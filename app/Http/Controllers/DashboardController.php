<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTopupWalletRequest;
use App\Http\Requests\StoreWithdrawWalletRequest;
use App\Models\Project;
use App\Models\ProjectApplicant;
use App\Models\WalletTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function proposals() {
        return view('dashboard.proposals');
    }

    public function proposal_details(Project $project, ProjectApplicant $projectApplicant) {
        if($projectApplicant->freelancer_id != auth()->id()) {
            abort(403, 'You are not authorized to see this page.');
        }
        return view('dashboard.proposal_details', compact('project', 'projectApplicant'));
    }

    //function untuk Menampilkan halaman wallet
    public function wallet() {
        $user = Auth::user();

        //manampilkan transaksi topup, withdraw, revenue, dan expense
        $wallet_transactions = WalletTransaction::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('dashboard.wallet', compact('wallet_transactions'));
    }

    public function wallet_withdrawals() {
        //function untuk menampilkan halaman withdraw wallet
        return view('dashboard.withdraw_wallet');
    }

    public function withdraw_wallet_store(StoreWithdrawWalletRequest $request) {
        $user = Auth::user();

        if ($user->wallet->balance < 100000) {
            return redirect()->back()->withErrors([
                'amount' => 'Saldo wallet tidak cukup untuk melakukan withdraw.']);
        }

        DB::transaction(function () use ($request, $user) {
            $validated = $request->validated();

            if($request->hasFile('proof')) {
                $proofPath = $request->file('proof')->store('proofs', 'public');
                $validated['proof'] = $proofPath;
            }

            $validated['type'] = 'Withdraw';
            $validated['amount'] = $user->wallet->balance;
            $validated['is_paid'] = false;
            $validated['user_id'] = $user->id;

            $newWithdrawWallet = WalletTransaction::create($validated);

            $user->wallet->update([
                'balance' => 0, // agar saldo wallet menjadi 0 setelah withdraw
            ]);
        });

        return redirect()->route('dashboard.wallet');
    } // function untuk menyimpan data withdraw wallet baru

    public function topup_wallet() {
        //function untuk menampilkan halaman topup wallet
        return view('dashboard.topup_wallet');
    }

    public function topup_wallet_store(StoreTopupWalletRequest $request) {
        $user = Auth::user();

        DB::transaction(function () use ($request, $user) {
            $validated = $request->validated();

            if($request->hasFile('proof')) {
                $proofPath = $request->file('proof')->store('proofs', 'public');
                $validated['proof'] = $proofPath;
            }

            $validated['type'] = 'Topup';
            $validated['is_paid'] = false;
            $validated['user_id'] = $user->id;

            $newTopupWallet = WalletTransaction::create($validated);
        });

        return redirect()->route('dashboard.wallet');
    } // function untuk menyimpan data topup wallet baru
}
