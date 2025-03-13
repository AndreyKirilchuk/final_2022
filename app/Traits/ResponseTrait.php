<?php

namespace App\Traits;

trait ResponseTrait
{
    public function errors($code = 400, $errors = null)
    {
        return response()->json([
            "data" => [
              "errors" => $errors
            ],
            "status" => "error"
        ], $code);
    }

    public function success($data = null, $code = 200)
    {
        if($data)
        {
            $newData["data"] = $data;
        }

        $newData["status"] = "ok";

        return response()->json($newData, $code);
    }
}
