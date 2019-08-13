<?php

include('Config/databaseConnection.php');
include('token.php');
$cuidadoCoordenado = new SulamericaApi();

function sendItensRoutine($access_token, $conn, $cuidadoCoordenado) {
    $getAllCuss = $conn->prepare('select pac_sas_cod_unico from gsc_paciente where cnv_codigo = 50 and pac_sas_cod_unico = 8483606');
    $getAllCuss->execute();
    $allCuss = $getAllCuss->fetchAll(PDO::FETCH_ASSOC);
    $cussRows = $getAllCuss->rowCount();
    //echo $cussRows."<br>";
    // foreach ($allCuss as $cuss) {
    for ($x=0; $x < $cussRows; $x++) { 
        //print_r($allCuss[$x]);
    }
        $sql = $conn->prepare('SELECT 
            a.cuss
            , a.pac_codigo
            , a.nome_beneficiario
            , a.numero_da_carteirinha
            , a.peso
            , a.altura
            , a.circunferencia_abdominal
            -- , b.num_cid
            -- , b.referido
            , c.adesao_ao_medico
            , c.adesao_ao_exame
            , c.adesao_ao_medicamento_de_uso_junto
            , d.medicamento
            , d.dosagem
            , d.posologia
            , e.alimentacao
            , e.tabagismo
            , e.atividade_fisica
            , f.risco_de_internacao
            , f.nivel_atencao
            , f.grau_de_fragilidade
        FROM
            gsc_sulamerica_antropometricos a
        -- INNER JOIN
        --     gsc_sulamerica_antecedentes_pessoais b ON a.cuss = b.cuss
        INNER JOIN
            gsc_sulamerica_autocontrole c ON a.cuss = c.cuss
        INNER JOIN
            gsc_sulamerica_medicamentos d ON a.cuss = d.cuss
        INNER JOIN
            gsc_sulamerica_habitos e ON a.cuss = e.cuss
        INNER JOIN
            gsc_sulamerica_tratamento f ON a.cuss = f.cuss
        WHERE
            a.cuss = 8483606');
            //.$cuss['pac_sas_cod_unico']);
        $sql->execute();
        $results = $sql->fetchAll(PDO::FETCH_ASSOC);
        $rows = $sql->rowCount();
        date_default_timezone_set('America/Sao_Paulo');
        $data_atual = date('Y-m-d H:i');
        $jsonIndicadores = "";
        $listaMedicamentos = '';

        if ($rows != 0) {
            for ($i=0; $i < $rows; $i++) {
                $listaMedicamentos .= '{
                    "nomeMedicamento": "'.$results[$i]['medicamento'].'",
                    "dosagem": "'.$results[$i]['dosagem'].'",
                    "posologia": "'.$results[$i]['posologia'].'"
                },';
            }
            $listaMedicamentos .= '{
                "nomeMedicamento": "",
                "dosagem": "",
                "posologia": ""
            }';
            switch ($results[0]['adesao_ao_medico']) {
                case 'Sim':
                    $adesaoAoMedico = 53;
                    break;
                case 'Não':
                    $adesaoAoMedico = 54;
                    break;                
                default:
                    $adesaoAoMedico = "";
                    break;
            }
            switch ($results[0]['adesao_ao_exame']) {
                case 'Sim':
                    $adesaoAoExame = 55;
                    break;
                case 'Não':
                    $adesaoAoExame = 56;
                    break;                
                default:
                    $adesaoAoExame = "";
                    break;
            }
            switch ($results[0]['adesao_ao_exame']) {
                case 'Sim':
                    $adesaoAoExame = 55;
                    break;
                case 'Não':
                    $adesaoAoExame = 56;
                    break;                
                default:
                    $adesaoAoExame = "";
                    break;
            }
    
            $json = '{
                "segurado": 8483606,
                "prestador": "025063150001",
                "programa": 1,
                "lista-indicadores": [
                    {
                        "codigoIndicador": 11,
                        "codigoIndicadorDetalhe": 43,
                        "valorReferenteIndicador": "'.$results[0]['peso'].'"
                    },
                    {
                        "codigoIndicador": 12,
                        "codigoIndicadorDetalhe": 44,
                        "valorReferenteIndicador": "'.$results[0]['altura'].'"
                    },
                    {
                        "codigoIndicador": 13,
                        "codigoIndicadorDetalhe": 45,
                        "valorReferenteIndicador": "'.$results[0]['circunferencia_abdominal'].'"
                    },
                    {
                        "codigoIndicador": 18,
                        "codigoIndicadorDetalhe": '.$adesaoAoMedico.'
                    },
                    {
                        "codigoIndicador": 19,
                        "codigoIndicadorDetalhe": '.$adesaoAoExame.'
                    },
                    {
                        "codigoIndicador": 20,
                        "codigoIndicadorDetalhe": 57
                    },
                    {
                        "codigoIndicador": 15,
                        "codigoIndicadorDetalhe": 47
                    },
                    {
                        "codigoIndicador": 16,
                        "codigoIndicadorDetalhe": 49
                    },
                    {
                        "codigoIndicador": 1,
                        "codigoIndicadorDetalhe": 3
                    },
                    {
                        "codigoIndicador": 2,
                        "codigoIndicadorDetalhe": 4
                    },
                    {
                        "codigoIndicador": 3,
                        "codigoIndicadorDetalhe": 11
                    },
                    {
                        "codigoIndicador": 4,
                        "codigoIndicadorDetalhe": 15
                    },
                    {
                        "codigoIndicador": 5,
                        "codigoIndicadorDetalhe": 18
                    },
                    {
                        "codigoIndicador": 6,
                        "codigoIndicadorDetalhe": 21
                    },
                    {
                        "codigoIndicador": 7,
                        "codigoIndicadorDetalhe": 24
                    },
                    {
                        "codigoIndicador": 8,
                        "codigoIndicadorDetalhe": 30
                    },
                    {
                        "codigoIndicador": 9,
                        "codigoIndicadorDetalhe": 35
                    },
                    {
                        "codigoIndicador": 10,
                        "codigoIndicadorDetalhe": 40
                    },
                    {
                        "codigoIndicador": 21,
                        "codigoIndicadorDetalhe": 60
                    }
                ],
                "lista-medicamentos": [
                    '.$listaMedicamentos.'
                ]
            }';
        }  

        echo "<pre>";
        echo $json;

        //print_r($cuidadoCoordenado->SendData($access_token, $json));
        //$retornoCurl = $cuidadoCoordenado->SendData($access_token, $json);
        print_r($retornoCurl);
        echo "</pre>";
        //$log = $conn->prepare("INSERT INTO gsc_sulamerica_logs SELECT null, ".intval($results[$i]['cuss']).", '".$data_atual."', ".intval($retornoCurl['statusCode']).", '".$retornoCurl['returnBody']."', 'Ok'");
        //$log->execute();
        sleep(2);
    }
 //}

$tabela_control_token = 'gsc_sulamerica_control_token';

$sql = $conn->prepare('select * from '.$tabela_control_token.' limit 1');
$sql->execute();
$tokenArray = $sql->fetchAll(PDO::FETCH_ASSOC);
date_default_timezone_set('America/Sao_Paulo');
$data_atual = date('Y-m-d H:i');
$data_aux = DateTime::createFromFormat('Y-m-d H:i', $data_atual);

if (empty($tokenArray) || $data_atual >= $tokenArray[0]['expira_em']){
    $access_token = $cuidadoCoordenado->GetOAuthToken();
    $expira_em = $data_aux->add(new DateInterval('PT3600S'));
    if ($access_token) {
        echo "<pre>";
        print_r("Success Token Created: ".$access_token);
        echo "</pre>";
        $sql = $conn->prepare('UPDATE '.$tabela_control_token.' SET access_token = "'.$access_token.'" , gerado_em = "'.$data_atual.'" , expira_em = "'.$expira_em->format('Y-m-d H:i').'" WHERE id = 1');
        $sql->execute();
        sendItensRoutine($access_token, $conn, $cuidadoCoordenado);
    } else {
        print_r("<strong>Application Error:</strong> Failed to Generate AccessToken.");
        exit();
    }
} else {
    $access_token = $tokenArray[0]['access_token'];
    sendItensRoutine($access_token, $conn, $cuidadoCoordenado);
}



