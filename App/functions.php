<?php 
    # Indicadores 
    function getCuss($conn) {
        $sql = $conn->prepare('
        SELECT DISTINCT
            cuss as pac_sas_cod_unico
        FROM
            gsc_sulamerica_header_indicadores
        WHERE
            status_transacao = 1
        AND (flag_antropometricos = 1
        OR flag_autocontrole = 1 
        OR flag_habitos = 1
        OR flag_medicamentos = 1
        OR flag_tratamento = 1)
        ;
        ');
        try {
            $sql->execute();
            $return = $sql->fetchAll(PDO::FETCH_ASSOC);
            return $return;
        } catch (Exception $e) {
            echo 'Error: ' . $e->getMessage();
            return false;
        }
    }

    function getCussCids($conn) {
        $sql = $conn->prepare("
        SELECT DISTINCT
            cuss as pac_sas_cod_unico
        FROM
            gsc_sulamerica_header_indicadores
        WHERE
            status_transacao_cids = 1
        AND flag_cids = 1
        ");
        $sql->execute();
        $return = $sql->fetchAll(PDO::FETCH_ASSOC);

        return $return;
    }

    function getAntropometricos($cuss, $conn) {
        $sql = $conn->prepare("
        SELECT 
            h.peso
            , h.altura
            , h.circunferencia_abdominal
        FROM
            gsc_sulamerica_antropometricos h
        INNER JOIN
            gsc_sulamerica_header_indicadores he
            -- ON DATE_FORMAT(h.data_inclusao, '%Y-%m-%d') = DATE_FORMAT(he.data_transacao, '%Y-%m-%d') 
            ON h.data_inclusao = he.data_transacao 
            AND h.cuss = he.cuss 
        WHERE h.cuss = ".$cuss."
        AND he.status_transacao = 1;
        ");
        $sql->execute();
        $return = $sql->fetchAll(PDO::FETCH_ASSOC);

        return $return;
    }

    function getAntecedentesPessoais($cuss, $conn) {
        $sql = $conn->prepare('
        SELECT 
            h.num_cid
            , h.data_inicio
            , h.referido
        FROM
            gsc_sulamerica_antecedentes_pessoais h
        INNER JOIN
		    gsc_sulamerica_header_indicadores he
        ON h.data_inclusao = he.data_transacao 
        AND h.cuss = he.cuss 
        WHERE
            h.cuss = '.$cuss.'
        AND he.status_transacao_cids = 1
        ');
        $sql->execute();
        $return = $sql->fetchAll(PDO::FETCH_ASSOC);

        return $return;
    }

    function getAutocontrole($cuss, $conn) {
        $sql = $conn->prepare("
        SELECT  
            h.adesao_ao_medico
            , h.adesao_ao_exame
            , h.adesao_ao_medicamento_de_uso_junto
        FROM
            gsc_sulamerica_autocontrole h
        INNER JOIN
            gsc_sulamerica_header_indicadores he
            -- ON DATE_FORMAT(h.data_inclusao, '%Y-%m-%d') = DATE_FORMAT(he.data_transacao, '%Y-%m-%d') 
            ON h.data_inclusao = he.data_transacao 
            AND h.cuss = he.cuss 
        WHERE h.cuss = ".$cuss."
        AND he.status_transacao = 1;
        ");
        $sql->execute();
        $return = $sql->fetchAll(PDO::FETCH_ASSOC);

        return $return;
    }

    function getMedicamentos($cuss, $conn) {
        $sql = $conn->prepare("
        SELECT 
            h.medicamento
            , h.dosagem
            , h.posologia
        FROM
            gsc_sulamerica_medicamentos h
        INNER JOIN
            gsc_sulamerica_header_indicadores he
            -- ON DATE_FORMAT(h.data_inclusao, '%Y-%m-%d') = DATE_FORMAT(he.data_transacao, '%Y-%m-%d') 
            ON h.data_inclusao = he.data_transacao 
            AND h.cuss = he.cuss 
        WHERE h.cuss = ".$cuss."
        AND he.status_transacao = 1;
        ");
        $sql->execute();
        $return = array(
            "dados" => $sql->fetchAll(PDO::FETCH_ASSOC),
            "linhas" => $sql->rowCount()
        );       

        return $return;
    }

    function getHabitos($cuss, $conn) {
        $sql = $conn->prepare("
        SELECT 
            h.alimentacao
            , h.tabagismo
            , h.atividade_fisica
        FROM
            gsc_sulamerica_habitos h
        INNER JOIN
            gsc_sulamerica_header_indicadores he
            -- ON DATE_FORMAT(h.data_inclusao, '%Y-%m-%d') = DATE_FORMAT(he.data_transacao, '%Y-%m-%d') 
            ON h.data_inclusao = he.data_transacao 
            AND h.cuss = he.cuss 
        WHERE h.cuss = ".$cuss."
        AND he.status_transacao = 1;
        ");
        $sql->execute();
        $return = $sql->fetchAll(PDO::FETCH_ASSOC);

        return $return;
    }

    function getTratamentos($cuss, $conn) {
        $sql = $conn->prepare("
        SELECT 
            h.risco_de_internacao
            , h.nivel_atencao
            , h.grau_de_fragilidade
            , h.avd
            , h.aivd
            , h.acompanhamento_medico
            , h.arranjo_domiciliar
            , h.oculos
            , h.audicao
            , h.queda
        FROM
            gsc_sulamerica_tratamento h
        INNER JOIN
            gsc_sulamerica_header_indicadores he
            -- ON DATE_FORMAT(h.data_inclusao, '%Y-%m-%d') = DATE_FORMAT(he.data_transacao, '%Y-%m-%d') 
            ON h.data_inclusao = he.data_transacao 
            AND h.cuss = he.cuss 
        WHERE h.cuss = ".$cuss."
        AND he.status_transacao = 1;
        ");
        $sql->execute();
        $return = $sql->fetchAll(PDO::FETCH_ASSOC);

        return $return;
    }

    function totalMedicamentos($cuss, $conn) {
        $sql = $conn->prepare("
        SELECT 
            count(h.cuss) as total_medicamentos 
        FROM 
            gsc_sulamerica_medicamentos h
        INNER JOIN
            gsc_sulamerica_header_indicadores he
            -- ON DATE_FORMAT(h.data_inclusao, '%Y-%m-%d') = DATE_FORMAT(he.data_transacao, '%Y-%m-%d') 
            ON h.data_inclusao = he.data_transacao 
        AND h.cuss = he.cuss
        WHERE h.cuss = ".$cuss."
        AND he.status_transacao = 1
        ");
        $sql->execute();
        $return = $sql->fetchAll(PDO::FETCH_ASSOC);

        return $return;
    }

    function getMedicamentosFor($conn, $cuss, $loop) {
        $sql = $conn->prepare("
        SELECT 
            h.medicamento
            , h.dosagem
            , h.posologia
        FROM
            gsc_sulamerica_medicamentos h
        INNER JOIN
            gsc_sulamerica_header_indicadores he
            -- ON DATE_FORMAT(h.data_inclusao, '%Y-%m-%d') = DATE_FORMAT(he.data_transacao, '%Y-%m-%d') 
            ON h.data_inclusao = he.data_transacao 
            AND h.cuss = he.cuss 
        WHERE h.cuss = ".$cuss."
        AND he.status_transacao = 1;
        ");
        $sql->execute();
        $dados = $sql->fetchAll(PDO::FETCH_ASSOC);
        //print_r($dados);
        $payload2 = "";
        for ($i=0; $i < $loop; $i++) {
            $payload2 .= '
            {
                "nomeMedicamento": "'.$dados[$i]['medicamento'].'",
                "dosagem": "'.$dados[$i]['dosagem'].'",
                "posologia": "'.$dados[$i]['posologia'].'"
            },';
        }
        return $payload2;
    }

    function generatePayLoad($access_token, $conn, $cuidadoCoordenado, $ambiente, $programa, $clientID) {
        $arrayCuss = getCuss($conn);
        $payload2 = ""; 
        date_default_timezone_set('America/Sao_Paulo');
        $data_atual = date('Y-m-d H:i');
        $data_transacao = date('Y-m-d');

        for ($i=0; $i < count($arrayCuss); $i++) { 
            $dadosAntropometricos   = getAntropometricos($arrayCuss[$i]['pac_sas_cod_unico'], $conn);
            $dadosAutocontrole      = getAutocontrole($arrayCuss[$i]['pac_sas_cod_unico'], $conn);
            $dadosMedicamentos      = getMedicamentos($arrayCuss[$i]['pac_sas_cod_unico'], $conn);
            $dadosHabitos           = getHabitos($arrayCuss[$i]['pac_sas_cod_unico'], $conn);
            $dadosTratamentos       = getTratamentos($arrayCuss[$i]['pac_sas_cod_unico'], $conn);
            $totalMedicamentos      = totalMedicamentos($arrayCuss[$i]['pac_sas_cod_unico'], $conn);            

            if ($arrayCuss[$i]['pac_sas_cod_unico'] != 0) {
                
                // Iniciando montagem do cabeçalho do JSON.
                
                $payload = '{ 
                    "segurado": '.$arrayCuss[$i]['pac_sas_cod_unico'].',
                    "prestador": "025063150001",
                    "programa": '.$programa.',
                '; 
                $payload .= '"lista-indicadores": [';
    
                if (!empty($dadosAntropometricos)) {
                    for ($in=0; $in < count($dadosAntropometricos); $in++) { 
                        if (!empty($dadosAntropometricos[$in]['peso'])) {
                            $payload .= '
                            {
                                "codigoIndicador": 11,
                                "codigoIndicadorDetalhe": 43,
                                "valorReferenteIndicador": "'.$dadosAntropometricos[$in]['peso'].'"
                            },';
                        }
                        if (!empty($dadosAntropometricos[$in]['altura'])) {
                            $payload .= '
                            {
                                "codigoIndicador": 12,
                                "codigoIndicadorDetalhe": 44,
                                "valorReferenteIndicador": "'.$dadosAntropometricos[$in]['altura'].'"
                            },';
                        }
                        if (!empty($dadosAntropometricos[$in]['circunferencia_abdominal'])) {
                            $payload .= '
                            {
                                "codigoIndicador": 13,
                                "codigoIndicadorDetalhe": 45,
                                "valorReferenteIndicador": "'.$dadosAntropometricos[$in]['circunferencia_abdominal'].'"
                            },';
                        }
                    }
                }
                
                if (!empty($dadosAutocontrole)) {
                    for ($ind=0; $ind < count($dadosAutocontrole); $ind++) { 
                        if (!empty($dadosAutocontrole[$ind]['adesao_ao_medico'])) {
                            switch ($dadosAutocontrole[$ind]['adesao_ao_medico']) {
                                case 'Sim':
                                    $adesaoAoMedico = 53;
                                    break;
                                case 'Não':
                                    $adesaoAoMedico = 54;
                                    break;
                            }
                            $payload .= '
                            {
                                "codigoIndicador": 18,
                                "codigoIndicadorDetalhe": '.$adesaoAoMedico.'
                            },';
                        }
                        if (!empty($dadosAutocontrole[$ind]['adesao_ao_exame'])) {
                            switch ($dadosAutocontrole[$ind]['adesao_ao_exame']) {
                                case 'Sim':
                                    $adesaoAoExame = 55;
                                    break;
                                case 'Não':
                                    $adesaoAoExame = 56;
                                    break;
                            }
                            $payload .= '
                            {
                                "codigoIndicador": 19,
                                "codigoIndicadorDetalhe": '.$adesaoAoExame.'
                            },';
                        }
                        if (!empty($dadosAutocontrole[$ind]['adesao_ao_medicamento_de_uso_junto'])) {
                            switch ($dadosAutocontrole[$ind]['adesao_ao_medicamento_de_uso_junto']) {
                                case 'Sim':
                                    $adesaoAoMedicamento = 57;
                                    break;
                                case 'Não':
                                    $adesaoAoMedicamento = 58;
                                    break;
                                case 'Não faz uso de medicamento continuo':
                                    $adesaoAoMedicamento = 59;
                                    break;
                            }
                            $payload .= '
                            {
                                "codigoIndicador": 20,
                                "codigoIndicadorDetalhe": '.$adesaoAoMedicamento.'
                            },';
                        }
                    }
                }
    
                if (!empty($dadosHabitos)) {
                    for ($indic=0; $indic < count($dadosHabitos); $indic++) { 
                        if (!empty($dadosHabitos[$indic]['alimentacao'])) {
                            switch ($dadosHabitos[$indic]['alimentacao']) {
                                case 'Sim':
                                    $alimentacao = 47;
                                    break;
                                case 'Não':
                                    $alimentacao = 48;
                                    break;
                            }
                            $payload .= '
                            {
                                "codigoIndicador": 15,
                                "codigoIndicadorDetalhe": '.$alimentacao.'
                            },';
                        }
                        if (!empty($dadosHabitos[$indic]['tabagismo'])) {
                            switch ($dadosHabitos[$indic]['tabagismo']) {
                                case 'Sim':
                                    $tabagismo = 49;
                                    break;
                                case 'Não':
                                    $tabagismo = 50;
                                    break;
                            }
                            $payload .= '
                            {
                                "codigoIndicador": 16,
                                "codigoIndicadorDetalhe": '.$tabagismo.'
                            },';
                        }
                        if (!empty($dadosHabitos[$indic]['atividade_fisica'])) {
                            switch ($dadosHabitos[$indic]['atividade_fisica']) {
                                case 'Sim':
                                    $atividade_fisica = 51;
                                    break;
                                case 'Não':
                                    $atividade_fisica = 52;
                                    break;
                            }
                            $payload .= '
                            {
                                "codigoIndicador": 17,
                                "codigoIndicadorDetalhe": '.$atividade_fisica.'
                            },';
                        }
                    }
                }
                
                if (!empty($dadosTratamentos)) {
                    for ($indice=0; $indice < count($dadosTratamentos); $indice++) { 
                        if (!empty($dadosTratamentos[$indice]['risco_de_internacao'])) {
                            switch ($dadosTratamentos[$indice]['risco_de_internacao']) {
                                case 'Alta':
                                    $risco_de_internacao = 1;
                                    break;
                                case 'Médio':
                                    $risco_de_internacao = 2;
                                    break;
                                case 'Baixo':
                                    $risco_de_internacao = 3;
                                    break;
                            }
                            $payload .= '
                            {
                                "codigoIndicador": 1,
                                "codigoIndicadorDetalhe": '.$risco_de_internacao.'
                            },';
                        }
                        if (!empty($dadosTratamentos[$indice]['nivel_atencao'])) {
                            switch ($dadosTratamentos[$indice]['nivel_atencao']) {
                                case 'IDOSO ROBUSTO':
                                    $nivel_atencao = 4;
                                    break;
                                case 'AUTONOMIA':
                                    $nivel_atencao = 5;
                                    break;
                                case 'DEPENDÊNCIA SEVERA':
                                    $nivel_atencao = 6;
                                    break;
                                case 'DEPENDÊNCIA PARCIAL':
                                    $nivel_atencao = 7;
                                    break;
                                case 'SILVER':
                                    $nivel_atencao = 8;
                                    break;
                                case 'IDOSO FRÁGIL':
                                    $nivel_atencao = 9;
                                    break;
                                case 'CRÔNICO NÍVEL 2':
                                    $nivel_atencao = 10;
                                    break;
                                case 'VIVA':
                                    $nivel_atencao = 10;
                                    break;
                            }
                            $payload .= '
                            {
                                "codigoIndicador": 2,
                                "codigoIndicadorDetalhe": '.$nivel_atencao.'
                            },';
                        }
                        if (!empty($dadosTratamentos[$indice]['grau_de_fragilidade'])) {
                            switch ($dadosTratamentos[$indice]['grau_de_fragilidade']) {
                                case 'Menor que 0,30 baixo':
                                    $grau_de_fragilidade = 11;
                                    break;
                                case '0,31 à 0,39 Médio':
                                    $grau_de_fragilidade = 12;
                                    break;
                                case '0,40 à 0,49 Correção maior ou = 0,4 alto':
                                    $grau_de_fragilidade = 13;
                                    break;
                                case 'Maior ou = 0,5':
                                    $grau_de_fragilidade = 14;
                                    break;
                            }
                            $payload .= '
                            {
                                "codigoIndicador": 3,
                                "codigoIndicadorDetalhe": '.$grau_de_fragilidade.'
                            },';
                        }
                        if (!empty($dadosTratamentos[$indice]['avd'])) {
                            switch ($dadosTratamentos[$indice]['avd']) {
                                case 'Autonomo':
                                    $avd = 15;
                                    break;
                                case 'Dependente Parcial':
                                    $avd = 16;
                                    break;
                                case 'Dependente Severo':
                                    $avd = 17;
                                    break;
                            }
                            $payload .= '
                            {
                                "codigoIndicador": 4,
                                "codigoIndicadorDetalhe": '.$avd.'
                            },';
                        }
                        if (!empty($dadosTratamentos[$indice]['aivd'])) {
                            switch ($dadosTratamentos[$indice]['aivd']) {
                                case 'Autonomo':
                                    $aivd = 18;
                                    break;
                                case 'Dependente Parcial':
                                    $aivd = 19;
                                    break;
                                case 'Dependente Severo':
                                    $aivd = 20;
                                    break;
                            }
                            $payload .= '
                            {
                                "codigoIndicador": 5,
                                "codigoIndicadorDetalhe": '.$aivd.'
                            },';
                        }
                        if (!empty($dadosTratamentos[$indice]['acompanhamento_medico'])) {
                            switch ($dadosTratamentos[$indice]['acompanhamento_medico']) {
                                case 'Sim':
                                    $acompanhamento_medico = 21;
                                    break;
                                case 'Não':
                                    $acompanhamento_medico = 22;
                                    break;
                            }
                            $payload .= '
                            {
                                "codigoIndicador": 6,
                                "codigoIndicadorDetalhe": '.$acompanhamento_medico.'
                            },';
                        }
                        if (!empty($dadosTratamentos[$indice]['arranjo_domiciliar'])) {
                            switch ($dadosTratamentos[$indice]['arranjo_domiciliar']) {
                                case 'Cônjuge':
                                    $arranjo_domiciliar = 23;
                                    break;
                                case 'Filhos':
                                    $arranjo_domiciliar = 24;
                                    break;
                                case 'Sozinho':
                                    $arranjo_domiciliar = 25;
                                    break;
                                case 'Netos':
                                    $arranjo_domiciliar = 26;
                                    break;
                                case 'Cuidador':
                                    $arranjo_domiciliar = 27;
                                    break;
                                case 'Outros arranjos':
                                    $arranjo_domiciliar = 28;
                                    break;
                                case 'Não se aplica':
                                    $arranjo_domiciliar = 68;
                                    break;
                            }
                            $payload .= '
                            {
                                "codigoIndicador": 7,
                                "codigoIndicadorDetalhe": '.$arranjo_domiciliar.'
                            },';
                        }
                        if (!empty($dadosTratamentos[$indice]['oculos'])) {
                            switch ($dadosTratamentos[$indice]['oculos']) {
                                case 'Sim, e enxergo mal':
                                    $oculos = 29;
                                    break;
                                case 'Sim, e enxergo bem':
                                    $oculos = 30;
                                    break;
                                case 'Não, e enxergo mal':
                                    $oculos = 31;
                                    break;
                                case 'Não, e enxergo bem':
                                    $oculos = 32;
                                    break;
                                case 'Só para leitura, e enxergo bem':
                                    $oculos = 33;
                                    break;
                                case 'Baixa visão/cegueira':
                                    $oculos = 34;
                                    break;
                                case 'Não se aplica':
                                    $oculos = 69;
                                    break;
                            }
                            $payload .= '
                            {
                                "codigoIndicador": 8,
                                "codigoIndicadorDetalhe": '.$oculos.'
                            },';
                        }
                        if (!empty($dadosTratamentos[$indice]['audicao'])) {
                            switch ($dadosTratamentos[$indice]['audicao']) {
                                case 'Sim, e escuto mal':
                                    $audicao = 35;
                                    break;
                                case 'Sim, e escuto bem':
                                    $audicao = 36;
                                    break;
                                case 'Não, e escuto mal':
                                    $audicao = 37;
                                    break;
                                case 'Não, e escuto bem':
                                    $audicao = 38;
                                    break;
                                case 'Surdez':
                                    $audicao = 39;
                                    break;
                                case 'Não se aplica':
                                    $audicao = 70;
                                    break;
                            }
                            $payload .= '
                            {
                                "codigoIndicador": 9,
                                "codigoIndicadorDetalhe": '.$audicao.'
                            },';
                        }
                        if ($dadosTratamentos[$indice]['queda'] != "") {
                            switch ($dadosTratamentos[$indice]['queda']) {
                                case '0':
                                    $queda = 40;
                                    break;
                                case '1 a 3':
                                    $queda = 41;
                                    break;
                                case 'maior 3':
                                    $queda = 42;
                                    break;
                            }
                            $payload .= '
                            {
                                "codigoIndicador": 10,
                                "codigoIndicadorDetalhe": '.$queda.'
                            },';
                        }
                    }
                }
                //$payload = substr($payload,0,-1);
                if ($totalMedicamentos[0]['total_medicamentos'] > 0) {

                    $payload .= '
                    {
                        "codigoIndicador": 21,
                        "codigoIndicadorDetalhe": 60
                    }],';

                    $payload .= '"lista-medicamentos": [';
                    $payload2 = getMedicamentosFor($conn, $arrayCuss[$i]['pac_sas_cod_unico'],$totalMedicamentos[0]['total_medicamentos']);
                    $payload .= substr($payload2,0,-1);

                    $payload .= ']';
                } else {
                    $payload = substr($payload,0,-1);
                    $payload .= ']';
                }
                $payload .= '}';

                # Envio de dados chamando a classe Token.
                try {
                    $cod_retorno = $cuidadoCoordenado->SendData($access_token, $payload, $ambiente, $clientID);
                    // $nome_arquivo = 'files_indicadores/'.$arrayCuss[$i]['pac_sas_cod_unico'].'_'.$data_transacao.'.txt';
                    // $arquivo = fopen($nome_arquivo,'w');
                    // //escrevemos no arquivo
                    // $texto = $payload;
                    // fwrite($arquivo, $texto);
                    // //Fechamos o arquivo após escrever nele
                    // fclose($arquivo);
                    // $cod_retorno = Array(
                    //     "statusCode" => 200,
                    //     "returnBody" => "teste"
                    // );
                    # Resgata o retorno do payload e insere no log.
                    $json_aux = json_decode($payload);
                    $json = json_encode($json_aux);                
                    $sql = $conn->prepare("INSERT gsc_sulamerica_logs SELECT null,".$arrayCuss[$i]['pac_sas_cod_unico'].",'".$data_atual."',".$cod_retorno['statusCode'].",'".$cod_retorno['returnBody']."','".$json."','Indicadores'");
                    $sql->execute();
                    if ($cod_retorno['statusCode'] != 200 && $cod_retorno['statusCode'] != 201) {
                        # Atualizar tabela de integração com Status de Transação = 3 (falhou);
                        $sql_fail = $conn->prepare('UPDATE gsc_sulamerica_header_indicadores SET status_transacao = 3 WHERE cuss = '.$arrayCuss[$i]['pac_sas_cod_unico'].' AND status_transacao = 1');
                        $sql_fail->execute();
                    } else {
                        # Atualizar tabela de integração com Status de Transação = 2 (sucesso);
                        $sql_success = $conn->prepare('UPDATE gsc_sulamerica_header_indicadores SET status_transacao = 2 WHERE cuss = '.$arrayCuss[$i]['pac_sas_cod_unico'].' AND status_transacao = 1');
                        $sql_success->execute();
                    }
                } catch (Exception $e) {
                    # Chamada da tabela de ERRO Genérica (variavel $e);
                    # Atualizar tabela de integração com Status de Transação = 3 (falhou);
                    $sql_error = $conn->prepare('UPDATE gsc_sulamerica_header_indicadores SET status_transacao = 4 WHERE cuss = '.$arrayCuss[$i]['pac_sas_cod_unico'].' AND status_transacao = 1');
                    $sql_error->execute();
                }
            }
        }
    }

    function generatePayLoadCids($access_token, $conn, $cuidadoCoordenado, $ambiente, $programa, $clientID) {
        $arrayCuss = getCussCids($conn);  
        $payload2 = "";
        date_default_timezone_set('America/Sao_Paulo');
        $data_atual = date('Y-m-d H:i');
        $data_transacao = date('Y-m-d');

        for ($i=0; $i < count($arrayCuss); $i++) { 
            $dadosAntecedentes   = getAntecedentesPessoais($arrayCuss[$i]['pac_sas_cod_unico'], $conn);
            
            if (!empty($dadosAntecedentes)) {
                if ($arrayCuss[$i]['pac_sas_cod_unico'] != 0) {
                    $payload = '{
                        "segurado": '.$arrayCuss[$i]['pac_sas_cod_unico'].',
                        "prestador": "025063150001",
                        "programa": '.$programa.',
                    ';
                    $payload .= '"lista-CIDs": [';
        
                    if (!empty($dadosAntecedentes)) {
                        for ($in=0; $in < count($dadosAntecedentes); $in++) {
                            switch ($dadosAntecedentes[$in]['referido']) {
                                case 'Sim':
                                    $referido = 'S';
                                    break;
                                case 'Não':
                                    $referido = 'N';
                                    break;
                            }
                            $payload2 .= '
                            {
                                "codigoInterDoenca": "'.str_replace(".","",$dadosAntecedentes[$in]['num_cid']).'",
                                "dataInicio": "'.$dadosAntecedentes[$in]['data_inicio'].'",
                                "dataFim": "'.$dadosAntecedentes[$in]['data_inicio'].'",
                                "flgReferido": "'.$referido.'"
                            },';
                        } # fim do for
                        $payload .= substr($payload2,0,-1);
                        $payload2 = '';
                    }
                    $payload .= ']}';
                    # Envio de dados chamando a classe Token.
                    try {
                        $cod_retorno = $cuidadoCoordenado->SendDataCid($access_token, $payload, $ambiente, $clientID);
                        // $nome_arquivo = 'files_cids/'.$arrayCuss[$i]['pac_sas_cod_unico'].'_'.$data_transacao.'.txt';
                        // $arquivo = fopen($nome_arquivo,'w');
                        // //escrevemos no arquivo
                        // $texto = $payload;
                        // fwrite($arquivo, $texto);
                        // //Fechamos o arquivo após escrever nele
                        // fclose($arquivo);
                        // $cod_retorno = Array(
                        //     "statusCode" => 200,
                        //     "returnBody" => "teste_cids"
                        // );
                        # Resgata o retorno do payload e insere no log.
                        $json_aux = json_decode($payload);
                        $json = json_encode($json_aux);
                        $sql = $conn->prepare("INSERT gsc_sulamerica_logs SELECT null,".$arrayCuss[$i]['pac_sas_cod_unico'].",'".$data_atual."',".$cod_retorno['statusCode'].",'".$cod_retorno['returnBody']."','".$json."','Cids'");
                        $sql->execute();
                        if ($cod_retorno['statusCode'] != 200 && $cod_retorno['statusCode'] != 201) {
                             # Atualizar tabela de integração com Status de Transação = 3 (falhou);
                            $sql_fail = $conn->prepare('UPDATE gsc_sulamerica_header_indicadores SET status_transacao_cids = 3 WHERE cuss = '.$arrayCuss[$i]['pac_sas_cod_unico'].' AND status_transacao_cids = 1');
                            $sql_fail->execute();
                        } else {
                            # Atualizar tabela de integração com Status de Transação = 2 (sucesso);
                            $sql_success = $conn->prepare('UPDATE gsc_sulamerica_header_indicadores SET status_transacao_cids = 2 WHERE cuss = '.$arrayCuss[$i]['pac_sas_cod_unico'].' AND status_transacao_cids = 1');
                            $sql_success->execute();
                        }
                    } catch (Exception $e) {
                        # Chamada da tabela de ERRO Genérica (variavel $e);
                        # Atualizar tabela de integração com Status de Transação = 3 (falhou);
                        $sql_fail = $conn->prepare('UPDATE gsc_sulamerica_header_indicadores SET status_transacao_cids = 4 WHERE cuss = '.$arrayCuss[$i]['pac_sas_cod_unico'].' AND status_transacao_cids = 1');
                        $sql_fail->execute();
                    }                    
                }
            }
        }
    }


?>