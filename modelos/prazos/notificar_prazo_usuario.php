<?php

/*
 * Copyright 2008 ICMBio
 * Este arquivo é parte do programa SISICMBio
 * O SISICMBio é um software livre; você pode redistribuíção e/ou modifição dentro dos termos
 * da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão
 * 2 da Licença.
 *
 * Este programa é distribuíção na esperança que possa ser útil, mas SEM NENHUMA GARANTIA; sem
 * uma garantia implícita de ADEQUAÇÃO a qualquer MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a
 * Licença Pública Geral GNU/GPL em português para maiores detalhes.
 * Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "LICENCA.txt",
 * junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço
 * www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF)
 * Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA
 * */

/**
 * @todo Refatorar e Encapsular...
 */
set_time_limit(60000);
date_default_timezone_set('America/Sao_Paulo');

/* * ***************** NOTIFICAR PRAZOS DO USUÁRIO ***************************** */

function notificaPrazoUsuario() {
    if (acesso()) {
        sendEmail();
    }
    return true;
}

function getPrazo() {


    $stmt = Controlador::getInstance()->getConnection()->connection->prepare("
        SELECT SQ_PRAZO AS ID, (cp.dt_prazo - 'now'::text::date) AS DIAS_RESTANTES, to_char(cp.dt_prazo, 'dd/mm/yyyy') AS DT_PRAZO,
            ID_USUARIO_ORIGEM, ID_USUARIO_DESTINO, ID_UNID_ORIGEM, ID_UNID_DESTINO,
            UD.EMAIL AS EMAIL_DESTINO, UO.EMAIL AS EMAIL_ORIGEM, UNO.EMAIL as EMAIL_UNID_ORIGEM,
            UND.EMAIL as EMAIL_UNID_DESTINO
        FROM TB_CONTROLE_PRAZOS CP
            LEFT JOIN TB_USUARIOS UO ON UO.ID = CP.ID_USUARIO_ORIGEM
            LEFT JOIN TB_USUARIOS UD ON UD.ID = CP.ID_USUARIO_DESTINO
            LEFT JOIN TB_UNIDADES UNO ON UNO.ID = CP.ID_UNID_ORIGEM
            LEFT JOIN TB_UNIDADES UND ON UND.ID = CP.ID_UNID_DESTINO
        WHERE FG_STATUS = 'AR'");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function acesso() {


    $stmt = Controlador::getInstance()->getConnection()->connection->prepare("SELECT (DT_ACESSO - 'now'::text::date) AS DIAS FROM TB_CONTROLE_ACESSO ORDER BY ID DESC LIMIT 1");
    $stmt->execute();
    $resul = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!empty($resul) && $resul['DIAS'] <= -1) {
        return true;
    }
    return false;
}

function makeArrayPrazos() {
    $data = array();
    $array = getPrazo();
    if (empty($array)) {
        return null;
    }
    foreach ($array as $key => $value) {

        if (strlen($value['EMAIL_DESTINO']) > 0) {

            $data[$value['EMAIL_DESTINO']]['pendentes'][$value['ID']]['ID'] = $value['ID'];
            $data[$value['EMAIL_DESTINO']]['pendentes'][$value['ID']]['DIAS_RESTANTES'] = $value['DIAS_RESTANTES'];
            $data[$value['EMAIL_DESTINO']]['pendentes'][$value['ID']]['DT_PRAZO'] = $value['DT_PRAZO'];

            $data[$value['EMAIL_ORIGEM']]['aguardando'][$value['ID']]['ID'] = $value['ID'];
            $data[$value['EMAIL_ORIGEM']]['aguardando'][$value['ID']]['DIAS_RESTANTES'] = $value['DIAS_RESTANTES'];
            $data[$value['EMAIL_ORIGEM']]['aguardando'][$value['ID']]['DT_PRAZO'] = $value['DT_PRAZO'];
        } else {
            $data[$value['EMAIL_UNID_DESTINO']]['pendentes'][$value['ID']]['ID'] = $value['ID'];
            $data[$value['EMAIL_UNID_DESTINO']]['pendentes'][$value['ID']]['DIAS_RESTANTES'] = $value['DIAS_RESTANTES'];
            $data[$value['EMAIL_UNID_DESTINO']]['pendentes'][$value['ID']]['DT_PRAZO'] = $value['DT_PRAZO'];

            $data[$value['EMAIL_ORIGEM']]['aguardando'][$value['ID']]['ID'] = $value['ID'];
            $data[$value['EMAIL_ORIGEM']]['aguardando'][$value['ID']]['DIAS_RESTANTES'] = $value['DIAS_RESTANTES'];
            $data[$value['EMAIL_ORIGEM']]['aguardando'][$value['ID']]['DT_PRAZO'] = $value['DT_PRAZO'];
        }
    }
    return $data;
}

function sendEmail() {
    $array = makeArrayPrazos();

    if (!empty($array)) {
        foreach ($array as $key => $data) {
            $email = $key;
            $corpo = "<html>
    <head>
        <title>CDOC/ICMBio</title>
        <meta http-equiv='Content-Type' content='text/html; charset=UTF-8'>
    </head>
    <body>
         <div id='geral' style='margin: 0 auto; width: 500px; height: auto; padding: 5px;
            font-family: Verdana, Geneva, sans-serif; font-size: 12px; color: #799936;
            border: 1px solid #799936; -moz-border-radius: 6px; -webkit-border-radius:6px;'>
            <div id='image'><img style ='width: 120px; height: 120px;' src='" . __URLSERVERAPP__ . "/imagens/icone.png'></div>
            <div id='conteudo'>
                <center><b style='font-size: 20px'>SGDoc</b></center><br>
                <b>Notificação de prazos SGDoc :</b><br>
                <ul>";

            if ($data['pendentes'] != null) {
                $corpo .= "<li><b>Meus prazos: </b>total(" . count($data['pendentes']) . ")<ul>";
                foreach ($data['pendentes'] as $pendentes) {
                    if ($pendentes['DIAS_RESTANTES'] < 0) {
                        $dias = -1 * $pendentes['DIAS_RESTANTES'];
                        $corpo .= "<li>O Prazo <b>N.{$pendentes['ID']}</b> esgotou em {$pendentes['DT_PRAZO']} ({$dias} dia(s) atrás. )</li>";
                    } else {
                        $corpo .= "<li>O Prazo <b>N.{$pendentes['ID']}</b> esgotará em {$pendentes['DT_PRAZO']} ( {$pendentes['DIAS_RESTANTES']} dia(s). )</li>";
                    }
                }
                $corpo .= "</ul></li><br>";
            }
            if ($data['aguardando'] != null) {
                $corpo .= "  <li><b>Meus prazos aguardando reposta:</b>total(" . count($data['pendentes']) . ")<ul>";
                foreach ($data['aguardando'] as $pendentes) {
                    if ($pendentes['DIAS_RESTANTES'] < 0) {
                        $dias = -1 * $pendentes['DIAS_RESTANTES'];
                        $corpo .= "<li>O Prazo <b>N.{$pendentes['ID']}</b> esgotou em {$pendentes['DT_PRAZO']} ({$dias} dia(s) atrás. )</li>";
                    } else {
                        $corpo .= "<li>O Prazo <b>N.{$pendentes['ID']}</b> esgotará em {$pendentes['DT_PRAZO']} ( {$pendentes['DIAS_RESTANTES']} dia(s). )</li>";
                    }
                }
                $corpo .= "</ul></li><br>";
            }
            $corpo .= "</div></div></body></html>";

            if (!is_null($email)) {
                $mail = new PHPMailer();
                $mail->IsSendmail();
                $mail->SetFrom('sgdoc@sgdoc.gov.br', 'SGDoc');
                $mail->AddAddress($email);
                $mail->Subject = "SGDoc - Notificação de prazos.";
                $mail->MsgHTML($corpo);
                $mail->Send();
                echo "enviado";
            }
            $corpo = null;
        }
    }
}