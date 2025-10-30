<?php

namespace App\Http\Controllers;

use App\Services\SmsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SmsController extends Controller
{
    public function __construct(private SmsService $smsService) 
    {
    }

    /**
     * Отправить код верификации
     */
    public function sendCode(Request $request): JsonResponse
    {
        $request->validate([
            'phone' => 'required|string|min:10|max:20'
        ]);

        try {
            $result = $this->smsService->sendVerificationCode($request->phone);
            return response()->json(['success'=> true, 'data' => ['code' => $result]]);
        }
        catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    /**
     * Проверить код верификации
     */
    public function verifyCode(Request $request): JsonResponse
    {
        $request->validate([
            'phone' => 'required|string|min:10|max:20',
            'code' => 'required|string|size:4'
        ]);

        try {
            $result = $this->smsService->verifyCode($request->phone, $request->code);
            if($result){
                return response()->json(['success'=> true, 'data' => []]);
            }
            else{
                return response()->json(['error'=> 'verification error']);
            }
        }
        catch (\Exception $e) {
            return response()->json(['error'=> $e->getMessage()]);
        }
    }
}