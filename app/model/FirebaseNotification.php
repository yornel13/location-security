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

    public function send($message, $registrationIds) {

        // $message['message']['create_at'] = date('Y-m-d G:i:s', (time() - (5 * 60 * 60)));

        $notification = array(
            'title' => 'Nuevo Mensaje',
            'body'  => 'Tienes un nuevo mensaje',
            'icon'  => 'https://firebasestorage.googleapis.com/v0/b/icsseseguridad-6f751.appspot.com/o/ic_launcher.png?alt=media&token=402016ac-218e-4542-a3b7-bd039eaef8bd',
            'sound' => 'default',
            'android_channel_id' => 'Mensajes',
            'click_action' => 'https://www.icsseseguridad.com/u/messaging',
        );
        $fields = array(
            'registration_ids' => $registrationIds,
            'data'             => $message,
            'notification'     => $notification,
            'webpush'          => array("headers" => array("TTL" => "900")),
            'android'          => array("ttl" => "2419200s"),
            "ttl"              => '3600'
        );

        $headers = array(
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

//    public function send_alert_web($message, $registrationIds) {
//
//        $expire = array("TTL" => "0");
//        $web_push = array ("headers" => $expire);
//        $notification = array(
//            'title' => 'Nuevo Alerta',
//            'body'  => 'Tienes una notificación',
//            'icon'  => 'https://firebasestorage.googleapis.com/v0/b/icsseseguridad-6f751.appspot.com/o/ic_launcher.png?alt=media&token=402016ac-218e-4542-a3b7-bd039eaef8bd',
//            'sound' => 'default',
//        );
//        $fields = array(
//            'registration_ids' => $registrationIds,
//            'data'             => $message,
//            'notification'     => $notification,
//            'webpush'          => $web_push
//        );
//
//        $headers = array(
//            'Authorization: key=' . $this->API_ACCESS_KEY,
//            'Content-Type: application/json'
//        );
//
//        $ch = curl_init();
//        curl_setopt( $ch,CURLOPT_URL, $this->url);
//        curl_setopt( $ch,CURLOPT_POST, true );
//        curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
//        curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
//        curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
//        curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
//        $result = curl_exec($ch );
//        curl_close( $ch );
//        return $result;
//    }

    public function send_report($message, $registrationIds) {

        $notification = array(
            'title' => 'Nuevo Comentario Recibido',
            'body'  => 'Tienes una notificación',
            'icon'  => 'https://firebasestorage.googleapis.com/v0/b/icsseseguridad-6f751.appspot.com/o/ic_launcher.png?alt=media&token=402016ac-218e-4542-a3b7-bd039eaef8bd',
            'sound' => 'default',
            'android_channel_id' => 'Reportes',
            'click_action' => 'https://www.icsseseguridad.com/u/control/bitacora/reportes',
        );
        $fields = array(
            'registration_ids' => $registrationIds,
            'data'             => $message,
            'notification'     => $notification,
            'webpush'          => array("headers" => array("TTL" => "900")),
            'android'          => array("ttl" => "2419200s"),
            "ttl"              => '3600'
        );

        $headers = array(
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