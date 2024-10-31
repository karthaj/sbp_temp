<?php

namespace Shopbox\Transformers;

use Shopbox\Models\Zpanel\Transaction;
use League\Fractal\TransformerAbstract;

class PayoutTransformer extends TransformerAbstract
{
	public function transform(Transaction $transaction)
	{
		return [
			'date' => $transaction->created_at->toFormattedDateString(),
			'amount' => $transaction->amount > 0 ? $transaction->currency.' '.number_format($transaction->amount, 2) : '-',
			'transaction_fee' => $transaction->fee > 0 ? $transaction->currency.' '.number_format($transaction->fee, 2) : '-',
			'debit' => $transaction->debit > 0 ? $transaction->currency.' '.number_format($transaction->debit, 2) : '-',
			'credit' => $transaction->credit > 0 ?  $transaction->currency.' '.number_format($transaction->credit, 2) : '-',
			'balance' => $transaction->currency.' '.number_format($transaction->balance, 2),
			'remarks' => $transaction->remarks
		];
	}
	
}