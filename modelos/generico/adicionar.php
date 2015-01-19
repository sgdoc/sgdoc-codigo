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

include("function/gera_pdf_sic.php");

function gerarRaiz($digital, $diretorio) {
    $raiz = 'LOTE' . floor($digital / 10000);

    if (is_dir($diretorio . "/" . $raiz)) {
        return $raiz;
    } else {
        mkdir($diretorio . "/" . $raiz, 0777);
        return $raiz;
    }
}

function desfazerPassos(Documento $doc, $passo) {
    $out1 = array();
    switch ((int) $passo) {
        case 3:
            // Deu erro no trâmite ou em outra etapa
            // desfazer tudo
            $tram = new Tramite();
            $out1 = $tram->removerTramite($doc->digital)->toArray();
        case 2:
            // Deu erro na criação do prazo
            // apagar qualquer prazo que tenha sido cadastrado
            // e o historico do prazo
            $out1 = DaoPrazo::removerPrazo($doc->digital)->toArray();
        // e fazer todo o resto
        case 1:
            // Deu erro na persistência das imagens
            // desfazer aqui cadastro do documento e anexação de imagens
            $out1 = DaoDocumento::removerImagensDocumento($doc)->toArray();
            if ($out1['success'] == 'true') {
                // e deletar pastas e arquivos das imagens
                $out1 = DaoDocumento::removerDocumento($doc)->toArray();
            }
    }
    return $out1;
}

try {
    $saida = array();
    $saida['message'] = "";
    $saida['error'] = "";
    $saida['success'] = 'false';

    $nome = $_POST['nome'];
    $cpf = isset($_POST['cpf']) ? $_POST['cpf'] : null;
    $email = isset($_POST['email']) ? $_POST['email'] : null;
    $interessado = $nome;
    $interessado .= ($cpf != null) ? ' - ' . $cpf : '';
    $interessado .= ($email != null) ? ' - ' . $email : '';
    unset($_POST['cpf']);
    unset($_POST['nome']);
    unset($_POST['email']);
    $ocr = $_POST['conteudo'];
    unset($_POST['conteudo']);
    $dig = DaoDocumento::numDigitalDisponivelSic()->toArray();
    if (!$dig['success']) {
        // Digital foi pêga enquanto o form era preenchido
        // retornar erro
        print(json_encode($dig));
        exit;
    }
    $documento = new Documento($_POST);
    $documento->nome = $nome;
    if ($cpf) {
        $documento->cpf = $cpf;
    }
    if ($email) {
        $documento->email = $email;
    }

    $out = DaoDocumento::validarNumSolicitacaoSic($documento->numero)->toArray();

    if ($out['success'] != 'true') {
        // Numero de solicitacao já cadastrado
        print(json_encode($out));
        exit;
    }

    $oDestino = DaoUnidade::getUnidade(null);

    $documento->digital = $dig['digital'];
    $documento->tipo = "INFORMACAO SIC";
    $documento->origem = "CGU";
    $documento->destino = $oDestino['nome'] . ' - ' . $oDestino['sigla'];
    $documento->tecnico_responsavel = "RESPONSÁVEL SIC";
    $documento->assunto_complementar = utf8_decode($ocr);
    $documento->interessado = $interessado;
    $documento->assinatura = null;
    $documento->cargo = null;
    $documento->procedencia = "E";
    $documento->data_entrada = date('d/m/Y');
    $documento->recibo = Zend_Auth::getInstance()->getIdentity()->NOME;
    $documento->diretoria = $_POST['id_unidade_destino'];

    $out = DaoDocumento::salvarDocumento($documento)->toArray();

    if ($out['success'] == 'true') {

        // Documento cadastrado com sucesso, criar imagem
        // Primeiro gera um pdf e pega o texto dele
        $pdf = geraPdfSic($documento);

        $name = $pdf . '.tif';

        $cmd = "gs -SDEVICE=tiffg4 -r300x300 -sPAPERSIZE=a4 -sOutputFile=$name -dNOPAUSE -dBATCH  $pdf";
        $response = shell_exec($cmd);

        $pasta = gerarRaiz($documento->digital, __CAM_UPLOAD__);
        @mkdir(__CAM_UPLOAD__ . "/" . $pasta . "/" . $documento->digital, 0777);

        $new_name = __CAM_UPLOAD__ . '/' . $pasta . '/' . $documento->digital . '/0' . aleatorio();
        shell_exec("tiffsplit {$name} {$new_name}");
        unlink($pdf);
        unlink($name);

        // Imagens criadas, existem arquivos a anexar?
        if (count($_FILES) > 0) {
            // Existe ao menos um, criar folha de rosto
            $preffix = 1;
            $end_folha = "/tmp/folha_" . aleatorio() . ".pdf";
            $folha = new FPDF();
            $folha->AddPage();
            $folha->SetFont('Arial', 'B', 40);
            $folha->Cell(0, 150, "ANEXOS", 0, 0, "C");
            $folha->Output($end_folha, "F");

            $name = $end_folha . '.tif';

            $cmd = "gs -SDEVICE=tiffg4 -r300x300 -sPAPERSIZE=a4 -sOutputFile=$name -dNOPAUSE -dBATCH  $end_folha";
            $response = shell_exec($cmd);

            // separar as páginas em tiff separados
            $new_name = __CAM_UPLOAD__ . '/' . $pasta . '/' . $documento->digital . '/' . $preffix . aleatorio();
            shell_exec("tiffsplit {$name} {$new_name}");
            unlink($end_folha);
            unlink($name);
            $preffix++;
            // Folha de rosto criada e convertida para tif, percorrer lista anexa
            foreach ($_FILES['anexos']['tmp_name'] as $key => $path) {
                if ($_FILES['anexos']['type'][$key] != 'application/pdf') {
                    $saida['message'] = "Um ou mais arquivos anexos nao possuem extensao PDF e foram ignorados.";
                    $saida['error'] = "Um ou mais arquivos anexos nao possuem extensao PDF e foram ignorados.";
                } else {
                    // Imagem do tipo correto, converter para tif
                    $pdf = __CAM_UPLOAD__ . '/TMP/' . aleatorio() . '.pdf';
                    move_uploaded_file($path, $pdf);

                    $name = $pdf . '.tif';

                    // converter para tiffg4 multi-páginas
                    $cmd = "gs -SDEVICE=tiffg4 -r300x300 -sPAPERSIZE=a4 -sOutputFile=$name -dNOPAUSE -dBATCH  $pdf";
                    $response = shell_exec($cmd);

                    // echo $response;
                    // separar as páginas em tiff separados
                    $new_name = __CAM_UPLOAD__ . '/' . $pasta . '/' . $documento->digital . '/' . $preffix . aleatorio();
                    shell_exec("tiffsplit {$name} {$new_name}");
                    unlink($pdf);
                    unlink($name);
                    $preffix++;
                }
            }
        }

        // Passou da criação das imagens anexas sem problemas
        // Agora resta fazer "upload" das imagens

        $outfiles = Uploader::persisteLote($documento->digital, $ocr)->toArray();

        if ($outfiles['success'] != 'true') {
            // Deu erro na persistencia das imagens na base
            $out = desfazerPassos($documento, 1);

            $outfiles['error'] .= "\n" . $out['error'];

            print(json_encode($outfiles));
            exit;
        }

        // Passar pra criação do prazo
        $array = array('nu_proc_dig_ref' => $documento->digital,
            'id_usuario_origem' => Controlador::getInstance()->usuario->ID,
            'id_usuario_destino' => null,
            'id_unid_origem' => Zend_Auth::getInstance()->getIdentity()->ID_UNIDADE,
            'id_unid_destino' => $_POST['id_unidade_destino'],
            'dt_prazo' => $documento->prazo,
            'tx_solicitacao' => $ocr);
        $prazo = new Prazo($array);
        $out = DaoPrazo::salvarPrazo($prazo)->toArray();

        if ($out['success'] == 'true') {
            // Prazo criado, tramitar documento
            unset($out);
            $tramite = new Tramite();
            $out = $tramite->tramitarDocumento($documento->digital, $prazo->id_unid_destino, "I")->toArray();

            if ($out['success'] == 'true') {
                $saida['success'] = 'true';
                $saida['message'] = "Solicitação de digital {$documento->digital} criada com sucesso.\n" . $saida['message'];
                unset($saida['error']);
                unset($out);
                print(json_encode($saida));
                exit;
            } else {
                $out1 = desfazerPassos($documento, 3);
                $out['error'] .= "\n" . $out1['error'];
                print(json_encode($out));
                exit;
            }
        } else {
            // Erro na criação do prazo, retornar erro
            $out1 = desfazerPassos($documento, 2);
            $out['error'] .= "\n" . $out1['error'];
            print(json_encode($out));
            unset($out);
            exit;
        }
    } else {
        $out['error'] = 'Erro durante cadastro do documento: ' . $out['error'];
        $out1 = desfazerPassos($documento, 3);
        $out['error'] .= "\n" . $out1['error'];
        print(json_encode($out));
        exit;
    }
} catch (Exception $e) {
    $erro = new Output(array('success' => 'false', 'error' => $e->getMessage()));
    print(json_encode($erro->toArray()));
}