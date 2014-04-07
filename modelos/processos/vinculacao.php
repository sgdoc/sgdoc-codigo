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

try {

    switch ($_REQUEST['acao']) {

        case 'carregar-pecas':
            $vinculacao = new Vinculacao();
            $pecas = $vinculacao->getPecasProcesso($_REQUEST['numero_processo']);
            foreach ($pecas as $key => $peca) {
                //@todo Verificar se os administradores do sistema podem remover a primeira peca do processo se necessario!
                $out[] = array((($key == 0) ? "X{$peca['DIGITAL']}" : $peca['DIGITAL']) => $peca['DIGITAL'] . (($key == 0) ? ' - Peça principal, não pode ser removida!' : ''));
            }
            break;

        case 'carregar-anexos':
            $vinculacao = new Vinculacao();
            $anexos = $vinculacao->getAnexosProcesso($_REQUEST['numero_processo']);
            foreach ($anexos as $key => $anexo) {
                $out[] = array($anexo['ANEXOS'] => $anexo['ANEXOS']);
            }
            break;

        case 'adicionar-peca':
            /**
             * Verificar se o processo esta na area de trabalho
             */
            if (!Processo::validarProcessoAreaDeTrabalho($_REQUEST['numero_processo'])) {
                print(json_encode(array('success' => 'false', 'message' => 'Este processo não está na sua área de trabalho!')));
                exit();
            }
            /**
             * Verificar se o documento esta na area de trabalho
             */
            if (!Documento::validarDigitalDocumento($_REQUEST['digital'])) {
                print(json_encode(array('success' => 'false', 'message' => 'Este documento não está na sua área de trabalho!')));
                exit();
            }
            $vinculacao = new Vinculacao();
            $out = $vinculacao->adicionarPecaProcesso($_REQUEST['numero_processo'], $_REQUEST['digital'])->toArray();
            break;

        case 'remover-peca':
            $vinculacao = new Vinculacao();
            $out = $vinculacao->removerPecaProcesso($_REQUEST['numero_processo'], $_REQUEST['digital'])->toArray();
            break;

        case 'desanexar':
            $vinculacao = new Vinculacao();
            $out = $vinculacao->removerAnexoProcesso($_REQUEST['numero_processo'], $_REQUEST['anexo'])->toArray();
            break;

        default:
            break;
    }

    print(json_encode($out));
} catch (PDOException $e) {
    print($e->getMessage());
}