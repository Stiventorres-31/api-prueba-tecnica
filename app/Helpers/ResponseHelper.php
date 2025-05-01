<?php
namespace App\Helpers;

class ResponseHelper{
    public static function success(string $message ="Se ha obtenido correctamente",int $code=200,array $data=[]){
        return response()->json([
            "success"=>true,
            "code"=>$code,
            "message"=>$message,
            "result"=>$data
        ],$code);
    }

    public static function error(string $message ="Error en el servidor interno",int $code=500,array $data=[]){
        return response()->json([
            "success"=>false,
            "code"=>$code,
            "message"=>$message,
            "result"=>$data
        ],$code);
    }

}
