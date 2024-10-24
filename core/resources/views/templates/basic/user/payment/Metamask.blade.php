@extends($activeTemplate . 'layouts.master')

@section('content')
    @php
        $gatewayCurrency = App\Models\GatewayCurrency::where('method_code', 200)->first();
    @endphp
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card custom--card">
                    <div class="card-header">
                        <h5 class="text-center"> <i class="las la-wallet"></i> @lang('Metamask Payment')</h5>
                    </div>
                    <div class="card-body p-5">
                        <ul class="list-group text-center">
                            <li class="list-group-item d-flex justify-content-between">
                                @lang('You have to pay '):
                                <strong>{{ showAmount($deposit->final_amount, 8, currencyFormat:false) }}
                                    {{ __($deposit->method_currency) }}</strong>
                            </li>
                            <li class="list-group-item d-flex justify-content-between">
                                @lang('You will get '):
                                <strong>{{ showAmount($deposit->amount) }}</strong>
                            </li>
                        </ul>
                        <button type="button" class="btn btn--base w-100 mt-3 payNow"
                            onclick="deposit()">@lang('Pay Now')</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script-lib')
    <script src="{{ asset('assets/global/js/web3.min.js') }}"></script>
@endpush

@push('script')
    <script>
        (function($) {
            "use strict";


            if (typeof window.ethereum !== 'undefined') {
                window.web3 = new Web3(window.ethereum);
            } else {
                notify('error', `@lang('Please install MetaMask to use this feature')`);
            }

            const depositAmount = `{{ getAmount($deposit->final_amount, 8) }}`;
            const weiAmount = web3.utils.toWei(depositAmount, 'ether');
            const decimalNumber = parseInt(weiAmount);
            const hexadecimalString = '0x' + decimalNumber.toString(16);

            const curText = `{{ $gatewayCurrency->currency }}`;
            const network = `{{ gs('network') }}`;
            var chainIdNeed;

            if (curText == 'ETH') {
                chainIdNeed = network == 'mainnet' ? 1 : 5;
            } else if (curText == 'MATIC') {
                chainIdNeed = network == 'mainnet' ? 137 : 80002;
            } else {
                chainIdNeed = network == 'mainnet' ? 56 : 97;
            }

            async function promptNetworkSwitch(chainIdNeed) {
                if (typeof window.ethereum !== 'undefined') {
                    const ethereum = window.ethereum;
                    const hexChainIdNeed = '0x' + parseInt(chainIdNeed).toString(16);
                    if (ethereum.networkVersion !== hexChainIdNeed) {
                        try {
                            await ethereum.request({
                                method: 'wallet_switchEthereumChain',
                                params: [{
                                    chainId: hexChainIdNeed
                                }],
                            });
                        } catch (switchError) {
                            if (switchError.code === 4902) {
                                notify('error', 'The chain has not been added to MetaMask.');
                                $('.payNow').attr('disabled', true);
                            } else {
                                console.error('Error switching network:', switchError);
                            }
                        }
                    }
                }
            }

            const requiredDecimalChainId = chainIdNeed;
            promptNetworkSwitch(requiredDecimalChainId);

            async function deposit() {
                const walletAddress =
                    `{{ json_decode($deposit->gateway->gateway_parameters)->wallet_address->value }}`;

                // Check if MetaMask is connected
                if (window.ethereum.selectedAddress) {
                    try {
                        await produceDeposit(walletAddress, hexadecimalString);

                    } catch (error) {
                        notify('error', "Deposit failed: " + error.message);
                        $('.payNow').attr('disabled', false).text(`@lang('Pay Now')`);
                    }
                } else {
                    ethereum.enable()
                        .then(function(accounts) {
                            produceDeposit(walletAddress, hexadecimalString);
                        })
                        .catch(function(error) {
                            console.error('MetaMask connection error:', error);
                        })
                        .finally(function() {

                        });
                }
            }

            async function produceDeposit(walletAddress, hexadecimalString) {
                const txHash = await window.ethereum.request({
                    method: 'eth_sendTransaction',
                    params: [{
                        to: walletAddress,
                        from: window.ethereum.selectedAddress,
                        value: hexadecimalString
                    }]
                });

                await waitForTransactionConfirmation(txHash);

                let data = {
                    trx_hash: txHash,
                    trx: `{{ $deposit->trx }}`,
                    custom_string: `{{ $deposit->custom_string }}`,
                    form_wallet: window.ethereum.selectedAddress
                };

                // Perform post request after confirmation
                $.post(`{{ route('ipn.Metamask') }}`, data, function(response) {
                    if (response) {
                        notify('success', `@lang('Deposit successful')`);
                        setTimeout(function() {
                            window.location.href = `{{ $deposit->success_url }}`;
                        }, 1000);
                    } else {
                        notify('error', `@lang('Deposit failed')`);
                        setTimeout(function() {
                            window.location.href = `{{ $deposit->failed_url }}`;
                        }, 1000);
                    }
                });
                $('.payNow').text(`@lang('Verifying........')`);
            }

            async function waitForTransactionConfirmation(txHash) {
                let currentBlockNumber = await web3.eth.getBlockNumber();
                let transactionBlockNumber = null;
                let requiredBlockConfirmations = 10;

                while (true) {
                    try {
                        const receipt = await web3.eth.getTransactionReceipt(txHash);
                        if (receipt && receipt.blockNumber !== undefined) {
                            transactionBlockNumber = receipt.blockNumber;
                            const confirmations = currentBlockNumber - transactionBlockNumber + 1;
                            if (confirmations >= requiredBlockConfirmations) {
                                return true;
                            }
                        }
                    } catch (error) {
                        console.error("Error checking transaction status:", error);
                    }

                    currentBlockNumber = await web3.eth.getBlockNumber();
                    await new Promise(resolve => setTimeout(resolve, 3000));
                }
            }

            $('.payNow').on('click', function() {
                $(this).attr('disabled', true).text(`@lang('Processing........')`);
            });

            window.deposit = deposit;

        })(jQuery);
    </script>
@endpush
