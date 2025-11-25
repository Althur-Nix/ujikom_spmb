<?php

namespace App\Http\Controllers;

use App\Services\OtpService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OtpController extends Controller
{
    protected $otpService;
    
    public function __construct(OtpService $otpService)
    {
        $this->otpService = $otpService;
    }
    
    public function send(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:users,email',
        ]);
        
        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => 'Email sudah terdaftar'], 422);
        }
        
        // Hapus OTP lama untuk email ini (termasuk yang sudah verified)
        \App\Models\Otp::where('email', $request->email)->delete();
        
        $this->otpService->generate($request->email);
        
        return response()->json(['success' => true, 'message' => 'Kode OTP telah dikirim ke email Anda']);
    }
    
    public function verify(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'code' => 'required|string|size:6',
        ]);
        
        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first()], 422);
        }
        
        $verified = $this->otpService->verify($request->email, $request->code);
        
        if ($verified) {
            return response()->json(['success' => true, 'message' => 'Kode OTP valid']);
        }
        
        return response()->json(['success' => false, 'message' => 'Kode OTP tidak valid atau sudah kadaluarsa'], 422);
    }
}
