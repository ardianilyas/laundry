<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class UserService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function getAllUsers() {
        return User::query()->orderByDesc('created_at')->get();
    }

    public function storeUser(array $data) {
        $password = Str::random(8);

        $data['phone'] = preg_replace('/^0/', '62', $data['phone']);
        
        $data['password'] = bcrypt($password);

        $user = User::create($data);

        $this->sendWhatsAppMessage($data['phone'], $data['name'], $data['email'], $password);

        Log::info("User created: ", [$user, $password]);

        return $user;
    }

    private function sendWhatsAppMessage($phone, $name, $mail, $password) 
    {
        $token = env("FONNTE_TOKEN");
        $message = "Halo $name, akun anda telah berhasil dibuat, berikut informasi akun untuk dapat login melalui website Ibuk Laundry :\n\nUsername: *$name*\nEmail: *$mail*\nPassword: *$password*";

        if (app()->environment('local')) {
            $devPhone = env('DEV_WHATSAPP', '6287877239702'); 
            Log::info("Development mode: redirect WhatsApp from $phone to $devPhone");
            $phone = $devPhone;
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://api.fonnte.com/send");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, [
            "target" => $phone,
            "message" => $message,
        ]);

        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Authorization: $token",
        ]);

        $result = curl_exec($ch);

        if (curl_errno($ch)) {
            Log::error('Fonnte CURL Error: ' . curl_error($ch));
        }

        // curl_close($ch);

        return $result;
    }
}
