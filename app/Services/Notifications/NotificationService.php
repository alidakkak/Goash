<?php

namespace App\Services\Notifications;

use Exception;

class NotificationService
{

    public static function sendNotification($device_key, $content, $type,$points)
    {
        try {
            $URL = 'https://fcm.googleapis.com/fcm/send';

            $data = [
                'to' => $device_key,
                'notification' => [
                    'title' => "You Have New Notification",
                    'body' => $content,
                ],
                'data' => [
                    "type" => $type,
                    'body' => $points,
                ]
            ];

            $json_data = json_encode($data);

            $crl = curl_init();

            $header = array();
            $header[] = 'Content-type: application/json';
            $header[] = 'Authorization: key=' . env('SERVER_API_KEY');
            curl_setopt($crl, CURLOPT_SSL_VERIFYPEER, false);

            curl_setopt($crl, CURLOPT_URL, $URL);
            curl_setopt($crl, CURLOPT_HTTPHEADER, $header);

            curl_setopt($crl, CURLOPT_POST, true);
            curl_setopt($crl, CURLOPT_POSTFIELDS, $json_data);
            curl_setopt($crl, CURLOPT_RETURNTRANSFER, true);
          $s=  curl_exec($crl);
        } catch (Exception $e) {
            return "NOTIFICATION FAILED !";
        }
    }
}
