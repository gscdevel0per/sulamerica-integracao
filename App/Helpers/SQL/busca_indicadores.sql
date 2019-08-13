/* Query p/ buscar todos os CUSS da base de dados
-- select distinct pac_sas_cod_unico from gsc_paciente where cnv_codigo = 50;
*/
/* Buscar dados Antropometricos */
SELECT 
    cuss
    , numero_da_carteirinha
    , pac_codigo
    , nome_beneficiario    
    , peso
    , altura
    , circunferencia_abdominal
FROM
    gsc_sulamerica_antropometricos 
WHERE
    cuss = 6729371;

/* Buscar dados Antecedentes Pessoais */
SELECT 
    cuss
    , numero_da_carteirinha
    , pac_codigo
    , nome_participante    
    , num_cid
    , DATE_FORMAT(data_inicio,'%Y-%m-%d')
    , referido
FROM
    gsc_sulamerica_antecedentes_pessoais 
WHERE
    cuss = 8483606;
    
/* Buscar dados Autocontrole */
SELECT 
    cuss
    , numero_da_carteirinha
    , pac_codigo
    , nome_participante    
    , adesao_ao_medico
    , adesao_ao_exame
    , adesao_ao_medicamento_de_uso_junto
FROM
    gsc_sulamerica_autocontrole 
WHERE
    cuss = 6729371;

/* Buscar dados Medicamentos */
SELECT 
    cuss
    , numero_da_carteirinha
    , pac_codigo
    , nome_participante    
    , medicamento
    , dosagem
    , posologia
FROM
    gsc_sulamerica_medicamentos
WHERE
    cuss = 8483606;

/* Buscar dados Habitos */
SELECT 
    cuss
    , numero_da_carteirinha
    , pac_codigo
    , nome_participante    
    , alimentacao
    , tabagismo
    , atividade_fisica
FROM
    gsc_sulamerica_habitos
WHERE
    cuss = 8483606;

/* Buscar dados Tratamento */
SELECT 
    cuss
    , numero_da_carteirinha
    , pac_codigo
    , nome_participante    
    , risco_de_internacao
    , nivel_atencao
    , grau_de_fragilidade
    , avd
    , aivd
    , acompanhamento_medico
    , arranjo_domiciliar
    , oculos
    , audicao
    , queda
FROM
    gsc_sulamerica_tratamento
WHERE
    cuss = 8483606;