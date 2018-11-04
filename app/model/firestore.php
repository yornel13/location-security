<?php

//$id = $_GET['id'];
//$fields = file_get_contents('php://input');
$url = 'https://firestore.googleapis.com/v1beta1/projects/icsseseguridad-6f751/databases/(default)/documents/test_alert2'; // for test
$headers = array(
    'Content-Type: application/json'
);
$ch = curl_init();
curl_setopt($ch,CURLOPT_URL, $url.'/5');
curl_setopt($ch,CURLOPT_TIMEOUT, 3);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PATCH');
curl_setopt($ch,CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch,CURLOPT_RETURNTRANSFER, 1);
//curl_setopt($ch,CURLOPT_SSL_VERIFYPEER, false);
//curl_setopt($ch,CURLOPT_POSTFIELDS, $fields);
$result = curl_exec($ch);
curl_close($ch);