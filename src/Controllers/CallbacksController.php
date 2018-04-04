<?php
namespace Solstis86\Blockchain\Controllers;


use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Solstis86\Blockchain\Models\BlockchainTransaction;
use Solstis86\Blockchain\Models\BlockchainWallet;

class CallbacksController extends Controller
{
    public function balanceUpdate(Request $request)
    {
        try {
            $wallet = BlockchainWallet::findByCallbackKey($request->input('callback_key'));

            $wallet->transactions()->updateOrCreate([
                'transaction_type' => $wallet->address == $request->input('address') ? BlockchainTransaction::RECEIVE : BlockchainTransaction::SPEND,
                'transaction_hash' => $request->input('transaction_hash'),
                'to_address' => $request->input('address'),
                'confirmations' => $request->input('confirmations'),
                'value' => $request->input('value') / 100000000,
            ]);

        } catch (ModelNotFoundException $exception) {

        }
    }
}