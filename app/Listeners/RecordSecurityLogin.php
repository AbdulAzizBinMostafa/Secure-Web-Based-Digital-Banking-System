<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use Illuminate\Http\Request;
use App\Models\LoginHistory;
use Illuminate\Support\Facades\Log;

class RecordSecurityLogin
{
    public $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function handle(Login $event): void
    {
        try {
            $user = $event->user;
            
            // Safely grab IP and User Agent, preventing nulls and trimming long strings
            $ip = $this->request->ip() ?? '127.0.0.1';
            $userAgent = substr($this->request->userAgent() ?? 'Unknown Device', 0, 250);
            
            // Determine Device Type
            $deviceType = 'Desktop';
            if (preg_match('/(android|mobile|iphone|ipad)/i', $userAgent)) {
                $deviceType = 'Mobile';
            }

            // 1. Record to Database
            LoginHistory::create([
                'user_id' => $user->id,
                'ip_address' => $ip,
                'user_agent' => $userAgent,
                'device_type' => $deviceType,
                'login_at' => now(),
            ]);

            // 2. Generate Log File Entry (Fallback to default laravel.log if security channel fails)
            try {
                Log::channel('security')->info("Security Event: User Logged In", [
                    'user_id' => $user->id,
                    'email' => $user->email,
                    'ip' => $ip
                ]);
            } catch (\Exception $e) {
                Log::info("Security Event: User Logged In - " . $user->email);
            }

        } catch (\Exception $e) {
            // Log the exact error if the database insert fails so we can see why
            Log::error("LoginHistory Failed: " . $e->getMessage());
        }
    }
}