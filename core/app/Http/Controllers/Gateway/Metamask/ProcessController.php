<?php

namespace App\Http\Controllers\Gateway\Metamask;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Gateway\PaymentController;
use App\Models\Deposit;
use App\Models\GatewayCurrency;
use Illuminate\Http\Request;

class ProcessController extends Controller
{
    public static function process($deposit)
    {
        $alias = $deposit->gateway->alias;

        $send['track']  = $deposit->trx;
        $send['view']   = 'user.payment.' . $alias;
        $send['method'] = 'post';
        $send['url']    = route('ipn.' . $alias);
        return json_encode($send);
    }

    public function ipn(Request $request)
    {
        $deposit = Deposit::where('trx_hash', $request->trx_hash)->exists();
        if ($deposit) {
            return false;
        }

        $deposit = Deposit::where('trx', $request->trx)->first();

        if (!$deposit) {
            return false;
        }

        try {
            $customString = decrypt($request->custom_string);
            $amount       = $customString['amount'];
            $trx          = $customString['trx'];
        } catch (\Throwable $th) {
            return false;
        }

        if ($deposit->final_amo != $amount || $deposit->trx != $trx) {
            return false;
        }

        $gatewayCurrency  = GatewayCurrency::where('method_code', 200)->first();
        $gatewayParameter = json_decode($gatewayCurrency->gateway_parameter);

        if ($gatewayCurrency->currency == 'ETH') {
            $mainUrl = gs('network') == 'testnet' ? "https://api-goerli.etherscan.io" : "https://api.etherscan.io";
        } else if ($gatewayCurrency->currency == 'MATIC') {
            $mainUrl = gs('network') == 'testnet' ? "https://api-amoy.polygonscan.com" : "https://api.polygonscan.com";
        } else {
            $mainUrl = gs('network') == 'testnet' ? "https://api-testnet.bscscan.com" : "https://api.bscscan.com";
        }

        $apiUrl = "$mainUrl/api?module=transaction&action=gettxreceiptstatus&txhash=$request->trx_hash&apikey=$gatewayParameter->api_key";

        $attempts   = 10;
        $retryDelay = 5;

        for ($i = 0; $i < $attempts; $i++) {
            sleep($retryDelay);

            $response     = file_get_contents($apiUrl);
            $responseData = json_decode($response, true);

            if (isset($responseData['status']) && isset($responseData['result'])) {
                $transactionData = $responseData['result'];
                if (@$transactionData['status'] == 1) {
                    break;
                }
            }

            if ($i == $attempts - 1) {
                return false;
            }
        }

        $deposit->trx_hash = $request->trx_hash;
        $deposit->save();

        if (@$responseData['status'] == '1' && isset($responseData['result'])) {
            $transactionData = $responseData['result'];

            if (@$transactionData['status'] == 1) {
                try {
                    $trxUrl = "$mainUrl/api?module=account&action=txlist&address=$gatewayParameter->wallet_address&startblock=0&endblock=99999999&page=1&offset=10&sort=desc&apikey=$gatewayParameter->api_key";

                    $trxResponse       = file_get_contents($trxUrl);
                    $trxResponseData   = json_decode($trxResponse, true);
                    $trxResponseResult = $trxResponseData['result'];
                    $lastTransaction   = $trxResponseResult[0];

                    if ($lastTransaction['hash'] == $request->trx_hash && $lastTransaction['from'] == $request->form_wallet && $lastTransaction['isError'] == 0 && $deposit->final_amo ==  bcdiv($lastTransaction['value'], "1000000000000000000", 18)) {
                        PaymentController::userDataUpdate($deposit);
                        return true;
                    }
                    return false;
                } catch (\Throwable $th) {
                    return false;
                }
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
}
