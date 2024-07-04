<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use Exception;
use Illuminate\Http\Request;

class BannerController extends Controller
{
    public function __construct()
    {
    }

    public function createBannerData(Request $request)
    {
        error_log($request);
        try {
            $result = Banner::create([
                'common_user_id'=>$request->input('common_user_id'),
                'training_id'=>$request->input('training_id'),
            ]);
            return response()->json([
                'success' => true,
                'message' => 'Registro feito com sucesso...',
                'data'   => $result,

            ], 200);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage(), 'data' => null], 500);
        }
    }
}
