<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;

class PaymentMethodController extends Controller
{
    public function index()
    {
        $paymentMethods = PaymentMethod::all();
        return ResponseHelper::success("Se ha obtenido correctamente", 200, ["paymentMethods" => $paymentMethods]);
    }
}
