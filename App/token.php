<?php
class SulamericaApi {

    public function GetOAuthToken($ambiente) {

        # Alimentando as variáveis para utilização no cUrl.
        $endpoint = "https://apisulamerica.sensedia.com".$ambiente."/coordinated-care/v1/oauth/access-token";
        $clientID = "de9c04dd-d298-3976-9a82-3f795a1315b0";
        $clientSecret = "99fa8e66-45be-3741-b1ea-71b6c57ad757";
        $body = array("grant_type" => "client_credentials");
        $payload = json_encode($body);
        $addHeaders = "client_id: de9c04dd-d298-3976-9a82-3f795a1315b0";

        $ch = curl_init($endpoint);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', $addHeaders));
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_USERPWD, $clientID . ":" . $clientSecret);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

        $output = curl_exec($ch);
        $json = json_decode($output, true);
        $return = isset($json['access_token']) ? $json['access_token'] : false;
        curl_close($ch);
        
        # Uncomment this line below if you need to visualize the HTTP return code.
        // return $httpCode = intval(curl_getinfo($ch, CURLINFO_HTTP_CODE));
        return $return;
    }

    public function SendData($access_token, $body, $ambiente){

        $endpoint = "https://apisulamerica.sensedia.com".$ambiente."/coordinated-care/v1/clinical-indicators";
        // $endpoint = "https://teste-api-sulamerica3.free.beeceptor.com";
        $clientID = "de9c04dd-d298-3976-9a82-3f795a1315b0";
        $addHeaders = "client_id: de9c04dd-d298-3976-9a82-3f795a1315b0";

        $ch2 = curl_init($endpoint);
        curl_setopt($ch2, CURLOPT_HTTPHEADER, array('ACCESS_TOKEN: '.$access_token, 'Content-Type: application/json', $addHeaders));
        curl_setopt($ch2, CURLOPT_HEADER, 0);
        curl_setopt($ch2, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch2, CURLOPT_POST, 1);
        curl_setopt($ch2, CURLOPT_POSTFIELDS, $body);
        curl_setopt($ch2, CURLOPT_RETURNTRANSFER, TRUE);

        $returnCurl = curl_exec($ch2);
        $httpCode = curl_getinfo($ch2, CURLINFO_HTTP_CODE);

        curl_close($ch2);
        // $response = 'Status: <b>'.$httpCode.'</b><br><br>'.'<pre>'.$returnCurl.'</pre>';
        $response = Array (
            "statusCode" => $httpCode,
            "returnBody" => $returnCurl,
        );
        
        return $response;
    }

    public function SendDataCid($access_token, $body, $ambiente){

        $endpoint = "https://apisulamerica.sensedia.com".$ambiente."/coordinated-care/v1/medical-records";
        // $endpoint = "https://teste-api-sulamerica3.free.beeceptor.com";
        $clientID = "afb85967-389f-3ab3-ba40-64efd24607bf";
        $addHeaders = "client_id: afb85967-389f-3ab3-ba40-64efd24607bf";

        $ch2 = curl_init($endpoint);
        curl_setopt($ch2, CURLOPT_HTTPHEADER, array('ACCESS_TOKEN: '.$access_token, 'Content-Type: application/json', $addHeaders));
        curl_setopt($ch2, CURLOPT_HEADER, 0);
        curl_setopt($ch2, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch2, CURLOPT_POST, 1);
        curl_setopt($ch2, CURLOPT_POSTFIELDS, $body);
        curl_setopt($ch2, CURLOPT_RETURNTRANSFER, TRUE);

        $returnCurl = curl_exec($ch2);
        $httpCode = curl_getinfo($ch2, CURLINFO_HTTP_CODE);

        curl_close($ch2);
        // $response = 'Status: <b>'.$httpCode.'</b><br><br>'.'<pre>'.$returnCurl.'</pre>';
        $response = Array (
            "statusCode" => $httpCode,
            "returnBody" => $returnCurl,
        );
        
        return $response;
    }

}