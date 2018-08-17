<?php
/**
 * Created by PhpStorm.
 * User: Yornel
 * Date: 16/08/2018
 * Time: 9:52
 */

namespace App\Model;


class FirebaseNotification
{
    private $url = 'https://fcm.googleapis.com/fcm/send';
    private $API_ACCESS_KEY = 'AAAA4DA7bSU:APA91bErm3rES3xAUDKX8KMBVDpiENq16FvpcScn3XEGHkIMm1yP4WwqvP_JkQUw0ny2LnElrcsXJcRs6eNI2awjHpnnwem5AxpL-0KgM9XvYMyok1f9L7SZx_KGVJuqxJEaGB09i0t9D2dBP0k-0y8ecsLx4U4O6Q';

    public function send($message, $to) {

        $registrationIds = array($to);

        $fields = array
        (
            'registration_ids' => $registrationIds,
            'data'             => $message
        );

        $headers = array
        (
            'Authorization: key=' . $this->API_ACCESS_KEY,
            'Content-Type: application/json'
        );

        $ch = curl_init();
        curl_setopt( $ch,CURLOPT_URL, $this->url);
        curl_setopt( $ch,CURLOPT_POST, true );
        curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
        curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
        curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
        curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
        $result = curl_exec($ch );
        curl_close( $ch );
        return $result;
    }
}