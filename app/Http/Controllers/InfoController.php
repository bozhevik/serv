<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\DTO\InfoDTO; 

class InfoController extends Controller
{
    public function server()
    {
        $dto = new InfoDTO([
            'php_version' => phpversion()
        ]);

        return response()->json($dto->toArray());
    }

    public function client(Request $request)
    {
        $dto = new InfoDTO([
            'ip' => $request->ip(),
            'useragent' => $request->userAgent()
        ]);

        return response()->json($dto->toArray());
    }

    public function database()
    {
        $dto = new InfoDTO([
            'db_connection' => DB::connection()->getDatabaseName(),
            'db_driver' => DB::connection()->getDriverName()
        ]);

        return response()->json($dto->toArray());
    }
}
