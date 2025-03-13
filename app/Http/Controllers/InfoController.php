<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class InfoController extends Controller
{
    public function server()
    {
        return response()->json([
            'php_version' => phpversion()
        ]);
    }

    public function client(Request $request)
    {
        return response()->json([
            'ip' => $request->ip(),
            'useragent' => $request->userAgent()
        ]);
    }

    public function database()
    {
        return response()->json([
            'db_connection' => DB::connection()->getDatabaseName(),
            'db_driver' => DB::connection()->getDriverName()
        ]);
    }
}
