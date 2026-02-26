<?php

if (!function_exists('send_telegram_notif')) {
    function send_telegram_notif($message)
    {
        $token = getenv('TELEGRAM_BOT_TOKEN');
        $chat_id = getenv('TELEGRAM_GROUP_ID');

        // Batalkan jika token atau chat ID kosong
        if (empty($token) || empty($chat_id)) {
            return false; 
        }

        $url = "https://api.telegram.org/bot" . $token . "/sendMessage";
        
        $data = [
            'chat_id'    => $chat_id,
            'text'       => $message,
            'parse_mode' => 'HTML' // Mengaktifkan format teks (bold, italic, dll)
        ];

        // Eksekusi pengiriman menggunakan cURL
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $result = curl_exec($ch);
        curl_close($ch);

        return $result;
    }
}