<?php

//Кодировка base64url
function base64url_encode($data) {
    return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
};

//build the headers
$headers = ['alg'=>'HS256','typ'=>'JWT'];
$headers_encoded = base64url_encode(json_encode($headers));

$data = $connection->query('SELECT * FROM bdb.users');

foreach ($data as $log) {
    if ($_SESSION['email'] == $log['email']) {
        $email = $log['email'];
        $name = $log['name'];
        $surname = $log['surname'];
        $mobile = $log['mobile'];
        unset($log);
        $data->close();
        break;
    }
};


//build the payload
$payload = ['email' => $email, 'name' => $name, 'surname' => $surname, 'mobile' => $mobile];
$payload_encoded = base64url_encode(json_encode($payload));

//build the signature
$key = 'secret_key';
$signature = hash_hmac('SHA256',"$headers_encoded.$payload_encoded",$key,true);
$signature_encoded = base64url_encode($signature);

//build and return the token
$token = "$headers_encoded.$payload_encoded.$signature_encoded";
?>
