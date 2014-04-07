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
    $documento = new Documento($_POST);

    $numero_processo = $documento->numero;
    if ($documento->tipo_vinculacao != "") {
        $documento->numero .= " " . $documento->tipo_vinculacao . $documento->numero_peca;
    }
    unset($documento->documento->tipo_vinculacao);
    unset($documento->documento->numero_peca);

    $processo = DaoProcesso::getProcesso($numero_processo);

    if (!Processo::validarProcessoAreaDeTrabalho($numero_processo)) {
        $out = new Output(array('success' => 'false', 'error' => 'Processo não se encontra na área de trabalho do ARQUIVO'));
        print(json_encode($out->toArray()));
        exit;
    }

    $processo['nm_interessado'] = Processo::getInteressado($processo['interessado'], 'interessado');
    $processo['nm_assunto'] = Processo::getAssunto($processo['assunto'], 'assunto');
    if ($processo['procedencia'] == 'I') {
        $processo['nm_origem'] = DaoUnidade::getUnidade($processo['origem'], 'nome');
    } else {
        $tmp = Processo::getOrigemExterna($processo['origem'], 'origem');
        $processo['nm_origem'] = $tmp['origem'];
    }

    $processo['dt_autuacao'] = Util::formatDate($processo['dt_autuacao']);
    
    $documento->data_documento = $processo['dt_autuacao'];
    $documento->data_entrada = $processo['dt_autuacao'];
    $documento->origem = $processo['nm_origem'];
    $documento->tipo = "DIGITALIZACAO DE PROCESSO";
    $documento->assunto = 2; // Abertura de Processo
    $documento->interessado = $processo['nm_interessado'];
    $documento->id_unid_area_trabalho = $processo['id_unid_area_trabalho'];
    $documento->assunto_complementar = $processo['assunto_complementar'];
    $documento->procedencia = $processo['procedencia'];

    $unique = DaoDocumento::uniqueDocumento($documento)->toArray();

    if ($unique['success'] == 'false') {
        $unique['error'] .= "\nFavor alterar o tipo de vinculação para adicionar um novo anexo ou volume,\n ou altere o número do volume/anexo.";
        print(json_encode($unique));
        exit;
    }

    $out = DaoDocumento::salvarDocumento($documento)->toArray();
    if ($out['success'] == 'true') {
        // Cadastrou documento, agora adicionar como peça
        $id_documento = $out['id'];
        $id_classificacao = $_POST['classificacao'];
        // Alterar classificacao do documento
        $out = DaoClassificacao::alterarClassificacaoDocumento($id_documento, $id_classificacao);

        $vinculacao = new Vinculacao();
        // Vincula documento ao processo, sem checar se o processo encontra-se na área de trabalho
        $out = $vinculacao->adicionarPecaProcesso($numero_processo, $documento->digital)->toArray();
    }
    print(json_encode($out));
} catch (Exception $e) {
    print($e->getMessage());
}