<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Models\PaymentMethod;
use App\Services\PaymentMethodService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentMethodController extends Controller
{
    protected $service;

    public function __construct(PaymentMethodService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        try {
            $methods = $this->service->listAll();
            return ResponseHelper::success("Métodos de pago obtenidos correctamente", 200, ["payment_methods" => $methods]);
        } catch (\Throwable $e) {
            Log::error('Error al obtener métodos de pago', ['error' => $e->getMessage()]);
            return ResponseHelper::error("Error interno al obtener métodos de pago", 500);
        }
    }
}
