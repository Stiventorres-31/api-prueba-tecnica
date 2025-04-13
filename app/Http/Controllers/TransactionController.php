<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class TransactionController extends Controller
{
    public function getPaymentsMethods(Request $request)
    {
        $paymentMethods = DB::select('SELECT * FROM payment_methods');
        return response()->json($paymentMethods);
    }

    public function generatePayment(Request $request)
    {
        $data = $request->all();
        
        $paymentMethod = DB::table('payment_methods')
            ->where('name', $data['payment_method'])
            ->first();
        
 
        $fee = 0;
        $total = 0;

        if($paymentMethod->name === 'cash') {
            $fee = json_decode($paymentMethod->config)->fee ?? 0;
            $total = $data['amount'] + $fee;
        }else if($paymentMethod->name === 'online') {
            $fee = json_decode($paymentMethod->config)->fee ?? 0;
            $total = $data['amount'] + $fee;
        }else if($paymentMethod->name === 'crypto') {
            $fee = json_decode($paymentMethod->config)->fee ?? 0;
            $total = $data['amount'] + $fee;
        }

        $transaction = DB::table('transactions')->where('id', $data['transaction_id'])->update([
            'customer_id' => $data['customer_id'],
            'payment_method_id' => $paymentMethod->id,
            'amount' => $data['amount'],
            'currency' => $data['currency'],
            'fee' => $fee,
            'total' => $total,
            'status' => 'completed',
            'metadata' => json_encode(['raw_data' => $data]), 
            'updated_at' => now()
        ]);


        
        return response()->json([
            'status' => 'success',
            'transaction_id' => $transaction
        ]);
    }

    public function createPayment(Request $request)
    {
        $data = $request->all();
        
        $customer = DB::table('customers')->insertGetId([
            'name' => $data['name'],
            'email' => $data['email'],
            'type_document' => $data['type_document'],
            'number_document' => $data['number_document'],
            'preferences' => json_encode(['raw_data' => $data]),
        ]);
        
        $transaction = DB::table('transactions')->insertGetId([
            'customer_id' => $customer,
            'amount' => $data['amount'],
            'currency' => $data['currency'],
            'status' => 'pending',
            'metadata' => json_encode(['raw_data' => $data]),
            'created_at' => now() 
        ]);
        
        return response()->json([
            'status' => 'success',
            'transaction_id' => $transaction,
            'url_payment'=> 'http://localhost:5173/transaction/' . $transaction
        ]);
    }

    public function getTransaction(Request $request)
    {
        $transaction = DB::table('transactions')->where('id', $request->id)->first();
        $customer = DB::table('customers')->where('id', $transaction->customer_id)->first();
        $transaction->customer = $customer;
        return response()->json($transaction);
    }

    public function getTransactions(Request $request)
    {
        $transactions = DB::table('transactions')->get();
        $customers = DB::table('customers')->get();
        $paymentMethods = DB::table('payment_methods')->get();
        foreach ($transactions as $transaction) {
            $transaction->customer = $customers->where('id', $transaction->customer_id)->first();
            $transaction->payment_method = $paymentMethods->where('id', $transaction->payment_method_id)->first();
        }
        return response()->json($transactions);
    }
}
