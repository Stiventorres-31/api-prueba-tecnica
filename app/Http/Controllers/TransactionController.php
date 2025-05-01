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
use App\Services\TransactionService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class TransactionController extends Controller
{

    protected $service;

    public function __construct(TransactionService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $transactions = $this->service->listPaginated(5);
        return ResponseHelper::success("Se han obtenidos correctamente", 200, ["transactions" => $transactions]);
    }

    public function show(GetTransactionRequest $request)
    {
        $transaction = $this->service->getById($request->id);
        return ResponseHelper::success("Se ha obtenido correctamente", 200, ["transactions" => $transaction]);
    }

    public function store(CreatePaymentRequest $request)
    {
        try {
            $transaction = $this->service->createTransaction($request->validated());

            return ResponseHelper::success("Se ha creado correctamente", 201, [
                'transaction_id' => $transaction->id,
                'url_payment' => 'http://localhost:5173/transaction/' . $transaction->id,
            ]);
        } catch (\Throwable $e) {
            Log::error('Error al crear el pago', ['error' => $e->getMessage()]);
            return ResponseHelper::error("Error interno al procesar el pago", 500);
        }
    }

    public function update(GeneratePaymentRequest $request)
    {
        try {
            $result = $this->service->generatePayment($request->validated());

            return ResponseHelper::success("Se ha generado correctamente", 200, $result);
        } catch (\Throwable $e) {
            Log::error('Error al generar el pago', ['error' => $e->getMessage()]);
            return ResponseHelper::error("Error interno al generar el pago", 500);
        }
    }
}
