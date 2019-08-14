<?php

    include('Config/databaseConnection.php');
    include('token.php');
    include('functions.php');
    ini_set('max_execution_time', 1200); //300 seconds = 5 minutes
    set_time_limit(1200);
    $ambiente = 'homolog';
    $cuidadoCoordenado = new SulamericaApi();

      
    //generatePayLoad($conn, $cuidadoCoordenado);

    $tabela_control_token = 'gsc_sulamerica_control_token';

    $sql = $conn->prepare('select * from '.$tabela_control_token.' limit 1');
    $sql->execute();
    $tokenArray = $sql->fetchAll(PDO::FETCH_ASSOC);
    date_default_timezone_set('America/Sao_Paulo');
    $data_atual = date('Y-m-d H:i');
    $data_aux = DateTime::createFromFormat('Y-m-d H:i', $data_atual);

    if (empty($tokenArray) || $data_atual >= $tokenArray[0]['expira_em']){
        $access_token = $cuidadoCoordenado->GetOAuthToken($ambiente);
        $expira_em = $data_aux->add(new DateInterval('PT3600S'));
        if ($access_token) {
            echo "<pre>";
            print_r("Success Token Created: ".$access_token);
            echo "</pre>";
            $sql = $conn->prepare('UPDATE '.$tabela_control_token.' SET access_token = "'.$access_token.'" , gerado_em = "'.$data_atual.'" , expira_em = "'.$expira_em->format('Y-m-d H:i').'" WHERE id = 1');
            $sql->execute();
            generatePayLoad($access_token, $conn, $cuidadoCoordenado, $ambiente); # Indicadores
            // generatePayLoadCids($access_token, $conn, $cuidadoCoordenado, $ambiente); # CIDs
        } else {
            print_r("<strong>Application Error:</strong> Failed to Generate AccessToken.");
            exit();
        }
    } else {
        $access_token = $tokenArray[0]['access_token'];
        generatePayLoad($access_token, $conn, $cuidadoCoordenado, $ambiente); # Indicadores
        // generatePayLoadCids($access_token, $conn, $cuidadoCoordenado, $ambiente); # CIDs
    }

?>



