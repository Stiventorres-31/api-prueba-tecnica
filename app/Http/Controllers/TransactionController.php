<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreatePaymentRequest;
use App\Http\Requests\GetTransactionRequest;
use App\Models\Customer;
use App\Models\Transaction;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

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

        if ($paymentMethod->name === 'cash') {
            $fee = json_decode($paymentMethod->config)->fee ?? 0;
            $total = $data['amount'] + $fee;
        } else if ($paymentMethod->name === 'online') {
            $fee = json_decode($paymentMethod->config)->fee ?? 0;
            $total = $data['amount'] + $fee;
        } else if ($paymentMethod->name === 'crypto') {
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

    public function createPayment(CreatePaymentRequest $request)
    {
        DB::beginTransaction();

        try {
            // Crear cliente
            $customer = Customer::create([
                'name' => $request->name,
                'email' => $request->email,
                'type_document' => $request->type_document,
                'number_document' => $request->number_document,
                'preferences' => [
                    'raw_data' => $request->validated(),
                ],
            ]);

            // Crear transacción
            $transaction = Transaction::create([
                'customer_id' => $customer->id,
                'amount' => $request->amount,
                'currency' => $request->currency,
                'status' => 'pending',
                'metadata' => [
                    'raw_data' => $request->validated(),
                ],
                'created_at' => now(),
            ]);

            DB::commit();

            return ResponseHelper::error("Se ha creado correctamente",201,[
                'transaction_id' => $transaction->id,
                'url_payment' => 'http://localhost:5173/transaction/' . $transaction->id
            ]);

        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Error al crear el pago', ['error' => $e->getMessage()]);

            return ResponseHelper::error("Error interno al procesar el pago",500);
        }
    }

    public function getTransaction(GetTransactionRequest $request)
    {
        $transaction = Transaction::with('customer')->findOrFail($request->id);

        return ResponseHelper::success("Se ha obtenido correctamente", 200, ["transactions" => $transaction]);
    }

    public function getTransactions()
    {
        $transactions = Transaction::with(['customer', 'paymentMethod'])
            ->paginate(15);


        return ResponseHelper::success("Se han obtenidos correctamente", 200, ["transactions" => $transactions]);
    }
}
