select l.pac_nome as "Nome", l.pac_sas_cod_unico as "CUSS", l.pac_matricula as "Numero da Carteirinha", l.peso as "Peso",  replace(l.altura, '.', ',' ) as "Altura", l.circuferencia_abdominal as "Circunferência Abdominal"
from(
select k.pac_codigo, k.pac_nome, k.pac_sas_cod_unico, k.pac_matricula, k.peso,if(k.altura>=1.20 and k.altura<=2.50,k.altura,'') as altura,
if(SUBSTRING_INDEX(k.circuferencia_abdominal, '.', 1)>=50 and SUBSTRING_INDEX(k.circuferencia_abdominal, '.', 1)<=180,SUBSTRING_INDEX(k.circuferencia_abdominal, '.', 1),'') as circuferencia_abdominal
from(
select z.pac_codigo, z.pac_nome, z.pac_sas_cod_unico, z.pac_matricula, z.circuferencia_abdominal,
if(SUBSTRING_INDEX(z.peso, '.', 1)>=30 and SUBSTRING_INDEX(z.peso, '.', 1)<=200,SUBSTRING_INDEX(z.peso, '.', 1),'') as peso,
if(z.altura>=1.20 and z.altura<=2.50,z.altura,concat(substr(SUBSTRING_INDEX(z.altura, '.', 1),1,1),'.',substr(SUBSTRING_INDEX(z.altura, '.', 1),2,2))) as altura
from(
select w.pac_codigo, w.pac_nome, w.pac_sas_cod_unico, w.pac_matricula,
if(w.pac_codigo in (286768,105549,145108,253183,165945,166530,107888,125160,111093,286143,241721,277995,241766,253287,109875,111267,152154,241710,249756,286584,144075,263763,158083,286778,135089,165523,243813,244978,167982,244074,36363,109004,159741,286441,164992,133730,158554,158655,263990,274180,168208,235689,125151,165058,278935,250553,274102,166617,153341,277627,138579),w.altura,w.peso) as peso,
if(w.pac_codigo in (286768,105549,145108,253183,165945,166530,107888,125160,111093,286143,241721,277995,241766,253287,109875,111267,152154,241710,249756,286584,144075,263763,158083,286778,135089,165523,243813,244978,167982,244074,36363,109004,159741,286441,164992,133730,158554,158655,263990,274180,168208,235689,125151,165058,278935,250553,274102,166617,153341,277627,138579),w.peso,w.altura) as altura,
w.circuferencia_abdominal
from(
select y.pac_codigo, y.pac_nome, y.pac_sas_cod_unico, y.pac_matricula, y.peso, y.altura, y.circuferencia_abdominal
from(
select x.pac_codigo, x.pac_nome, x.pac_sas_cod_unico, x.pac_matricula, x.peso, x.altura, x.circuferencia_abdominal
from(
SELECT
a.pac_nome, a.pac_sas_cod_unico, a.pac_matricula,
a.pac_codigo,
ifnull(c.peso,ifnull(c_ant.peso_antigo,ifnull(h.peso_multi,i.peso_viva))) as peso,
ifnull(d.altura,ifnull(d_antigo.altura_antigo,ifnull(m.altura_multi,n.altura_viva))) as altura,
ifnull(e.circuferencia_abdominal,ifnull(f.circuferencia_abdominal_multi,g.circuferencia_abdominal_viva)) as circuferencia_abdominal
FROM gsc_paciente a
LEFT JOIN
(
SELECT x.peso, x.pac_codigo as pac_novo, x.dta
from(
SELECT b.mov_resposta as peso, a.pac_codigo,concat(a.monit_sys_dt_inc,' ',a.monit_sys_hr_inc,':00') as dta
FROM gsc_monitoramento_360 a
INNER JOIN gsc_monitoramento_mov_360 b on b.monit_codigo = a.monit_codigo
INNER JOIN gsc_respostas_360 j on j.resp_codigo = b.resp_codigo
INNER JOIN gsc_paciente pa on pa.pac_codigo = a.pac_codigo
WHERE  b.perg_codigo = 46
and b.resp_codigo = 345
and pa.cnv_codigo = 50
and pa.sts_codigo = 7
and b.mov_resposta not in('0.00', '00.00', '000.00')
ORDER BY a.monit_codigo DESC
)x group by x.pac_codigo
)c on c.pac_novo = a.pac_codigo
LEFT JOIN
(
select x.peso_antigo, x.dta, x.pac_codigo_antigo
from(
SELECT a.est_per_02_peso as peso_antigo, concat(est_dt_inc,' ',est_hr_inc,':00')as dta, pa.pac_codigo as pac_codigo_antigo
FROM gss_estratificacao a
INNER JOIN gsc_paciente pa on pa.pac_codigo = a.pac_codigo
WHERE pa.cnv_codigo = 50
AND pa.sts_codigo = 7
AND a.est_per_02_peso not in('0.000','0.00')
ORDER BY est_seq DESC
)x group by x.pac_codigo_antigo
)c_ant on c_ant.pac_codigo_antigo = a.pac_codigo
LEFT JOIN
(
SELECT x.campo_082 as peso_multi, x.pac_codigo as pac_codigo_peso_multi
from(
SELECT a.campo_082, a.pac_codigo
FROM gss_evolucao_multidimensional a
INNER JOIN gsc_paciente pa on pa.pac_codigo = a.pac_codigo
and pa.cnv_codigo = 50
and pa.sts_codigo = 7
and a.campo_082 not in ('','0','0.00')
order by a.evo_seq DESC
)x group by x.pac_codigo
)h on h.pac_codigo_peso_multi = a.pac_codigo
LEFT JOIN
(
SELECT x.campo_198 as peso_viva, x.pac_codigo as pac_codigo_viva_novo
from(
SELECT a.campo_198, a.pac_codigo
FROM gss_evolucao_viva a
INNER JOIN gsc_paciente pa on pa.pac_codigo = a.pac_codigo
and pa.cnv_codigo = 50
and pa.sts_codigo = 7
and a.campo_198 is not null
and a.campo_198 not in ('','0','0.00')
order by a.evo_seq DESC
)x group by x.pac_codigo
)i on i.pac_codigo_viva_novo = a.pac_codigo

LEFT JOIN
(
SELECT x.altura, x.pac_codigo as pac_novo_altura, x.dta
from(
SELECT b.mov_resposta as altura, a.pac_codigo,concat(a.monit_sys_dt_inc,' ',a.monit_sys_hr_inc,':00') as dta
FROM gsc_monitoramento_360 a
INNER JOIN gsc_monitoramento_mov_360 b on b.monit_codigo = a.monit_codigo
INNER JOIN gsc_respostas_360 j on j.resp_codigo = b.resp_codigo
INNER JOIN gsc_paciente pa on pa.pac_codigo = a.pac_codigo
WHERE  b.perg_codigo = 48
and b.resp_codigo = 345
and pa.cnv_codigo = 50
and pa.sts_codigo = 7
and b.mov_resposta not in('0.00', '00.00', '000.00')
ORDER BY a.monit_codigo DESC
)x group by x.pac_codigo
)d on d.pac_novo_altura = a.pac_codigo
LEFT JOIN
(
select x.altura_antigo, x.dta, x.pac_codigo_antigo_altura
from(
SELECT a.est_per_02_altura as altura_antigo, concat(est_dt_inc,' ',est_hr_inc,':00')as dta, pa.pac_codigo as pac_codigo_antigo_altura
FROM gss_estratificacao a
INNER JOIN gsc_paciente pa on pa.pac_codigo = a.pac_codigo
WHERE pa.cnv_codigo = 50
AND pa.sts_codigo = 7
AND a.est_per_02_altura not in('0.000')
ORDER BY est_seq DESC
)x group by x.pac_codigo_antigo_altura
)d_antigo on d_antigo.pac_codigo_antigo_altura = a.pac_codigo
LEFT JOIN
(
SELECT x.campo_083 as altura_multi, x.pac_codigo as pac_codigo_mult_altura
from(
SELECT a.campo_083, a.pac_codigo
FROM gss_evolucao_multidimensional a
INNER JOIN gsc_paciente pa on pa.pac_codigo = a.pac_codigo
and pa.cnv_codigo = 50
and pa.sts_codigo = 7
and a.campo_083 not in ('','0')
order by a.evo_seq DESC
)x group by x.pac_codigo
)m on m.pac_codigo_mult_altura = a.pac_codigo
LEFT JOIN
(
SELECT x.campo_197 as altura_viva, x.pac_codigo as pac_codigo_viva_altura
from(
SELECT a.campo_197, a.pac_codigo
FROM gss_evolucao_viva a
INNER JOIN gsc_paciente pa on pa.pac_codigo = a.pac_codigo
and pa.cnv_codigo = 50
and pa.sts_codigo = 7
and a.campo_197 is not null
and a.campo_197 not in ('','0')
order by a.evo_seq DESC
)x group by x.pac_codigo
)n on n.pac_codigo_viva_altura = a.pac_codigo

LEFT JOIN
(
SELECT x.circuferencia_abdominal, x.pac_codigo as pac_novo_circu, x.dta
from(
SELECT b.mov_resposta as circuferencia_abdominal, a.pac_codigo, concat(a.monit_sys_dt_inc,' ',a.monit_sys_hr_inc,':00') as dta
FROM gsc_monitoramento_360 a
INNER JOIN gsc_monitoramento_mov_360 b on b.monit_codigo = a.monit_codigo
INNER JOIN gsc_paciente pa on pa.pac_codigo = a.pac_codigo
WHERE b.perg_codigo in (233,333)
and pa.cnv_codigo = 50
and pa.sts_codigo = 7
and b.mov_resposta not in('0.00', '00.00', '000.00')
ORDER BY a.monit_codigo DESC
)x group by x.pac_codigo
)e on e.pac_novo_circu = a.pac_codigo
LEFT JOIN
(
SELECT x.campo_085 as circuferencia_abdominal_multi, x.pac_codigo as pac_novo_circu_multi
from(
SELECT a.campo_085, a.pac_codigo
FROM gss_evolucao_multidimensional a
INNER JOIN gsc_paciente pa on pa.pac_codigo = a.pac_codigo
and pa.cnv_codigo = 50
and pa.sts_codigo = 7
and a.campo_085 not in ('','0')
order by a.evo_seq DESC
)x group by x.pac_codigo
)f on f.pac_novo_circu_multi = a.pac_codigo
LEFT JOIN
(
SELECT x.campo_199 as circuferencia_abdominal_viva, x.pac_codigo as pac_codigo_viva
from(
SELECT a.campo_199, a.pac_codigo
FROM gss_evolucao_viva a
INNER JOIN gsc_paciente pa on pa.pac_codigo = a.pac_codigo
and pa.cnv_codigo = 50
and pa.sts_codigo = 7
and a.campo_199 is not null
and a.campo_199 not in ('','0')
order by a.evo_seq DESC
)x group by x.pac_codigo
)g on g.pac_codigo_viva = a.pac_codigo
WHERE a.cnv_codigo = 50
and a.sts_codigo = 7
)x
)y
)w
)z
)k
)l
