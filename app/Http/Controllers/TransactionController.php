<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreatePaymentRequest;
use App\Http\Requests\GeneratePaymentRequest;
use App\Http\Requests\GetTransactionRequest;
use App\Models\Customer;
use App\Models\PaymentMethod;
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

    public function generatePayment(GeneratePaymentRequest $request)
    {
        DB::beginTransaction();

        try {
            // Buscar método de pago
            $paymentMethod = PaymentMethod::where('name', $request->payment_method)->firstOrFail();

            $fee = $paymentMethod->config['fee'] ?? 0;
            $total = $request->amount + $fee;

            // Buscar transacción
            $transaction = Transaction::findOrFail($request->transaction_id);

            // Actualizar datos de la transacción
            $transaction->update([
                'customer_id' => $request->customer_id,
                'payment_method_id' => $paymentMethod->id,
                'amount' => $request->amount,
                'currency' => $request->currency,
                'fee' => $fee,
                'total' => $total,
                'status' => 'completed',
                'metadata' => [
                    'raw_data' => $request->validated(),
                ],
            ]);

            DB::commit();

            return ResponseHelper::error("Se ha generado correctamente", 200, [
                'transaction_id' => $transaction->id,
                'url_payment' => 'http://localhost:5173/transaction/' . $transaction->id,
            ]);

        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Error al generar el pago', ['error' => $e->getMessage()]);

            return ResponseHelper::error("Error interno al generar el pago.",500);
        }
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
                'payment_method_id' => null,
                'amount' => $request->amount,
                'currency' => $request->currency,
                'status' => 'pending',
                'metadata' => [
                    'raw_data' => $request->validated(),
                ]
            ]);

            DB::commit();

            return ResponseHelper::error("Se ha creado correctamente", 201, [
                'transaction_id' => $transaction->id,
                'url_payment' => 'http://localhost:5173/transaction/' . $transaction->id
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Error al crear el pago', ['error' => $e->getMessage()]);

            return ResponseHelper::error("Error interno al procesar el pago", 500);
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
