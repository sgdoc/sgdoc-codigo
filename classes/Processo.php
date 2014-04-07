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
 * @author Michael F. Rodrigues <cerberosnash@gmail.com>
 */
class Processo extends Base {

    public $processo;
    protected $out = array();

    /**
     * 
     */
    public function Processo($array = null) {
        if (is_array($array)) {
            foreach ($array as $key => $value) {
                $this->processo->{$key} = $value;
            }
        }
        parent::__construct();
    }

    /**
     * 
     */
    public function __set($var, $value) {
        $this->processo->$var = $value;
    }

    /**
     * 
     */
    public function __get($var) {
        return $this->processo->$var;
    }

    /**
     *  Verificar se o processo informado esta na area de trabalho do usuario logado
     */
    public static function validarProcessoAreaDeTrabalho($processo, $unidade = false) {
        try {

            $condicao = filter_var($processo, FILTER_VALIDATE_INT) ? 'ID' : 'NUMERO_PROCESSO';
            $unidade = $unidade ? $unidade : Controlador::getInstance()->usuario->ID_UNIDADE;

            $stmt = Controlador::getInstance()->getConnection()->connection->prepare("SELECT 1 FROM TB_PROCESSOS_CADASTRO WHERE $condicao = ? AND ID_UNID_AREA_TRABALHO = ? LIMIT 1");
            $stmt->bindParam(1, $processo, PDO::PARAM_STR);
            $stmt->bindParam(2, $unidade, PDO::PARAM_INT);
            $stmt->execute();

            $out = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!empty($out)) {
                return true;
            }

            return false;
        } catch (PDOException $e) {
            throw new BasePDOException($e);
        }
    }

    /**
     * 
     */
    public static function existeProcessoCadastrado($numero_processo, $retorna = false) {
        try {

            $campos = ($retorna) ? '*' : 1;

            $stmt = Controlador::getInstance()->getConnection()->connection->prepare("SELECT {$campos} FROM TB_PROCESSOS_CADASTRO WHERE NUMERO_PROCESSO = ? LIMIT 1");
            $stmt->bindParam(1, $numero_processo, PDO::PARAM_STR);
            $stmt->execute();
            $out = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!empty($out)) {
                $out = array_change_key_case($out, CASE_LOWER);
                if ($retorna) {
                    $out['dt_autuacao'] = Util::formatDate($out['dt_autuacao']);
                    $out = array('success' => 'true', 'existe' => 'true', 'processo' => $out);
                } else {
                    $out = array('success' => 'true', 'existe' => 'true');
                }
            } else {
                $out = array('success' => 'true', 'existe' => 'false');
            }

            return $out;
        } catch (PDOException $e) {
            $out = array('success' => 'false', 'error' => $e->getMessage());
        }
    }

    /**
     * 
     */
    public static function interessadoObrigatorio($assunto, $interessado) {
        try {
            if ((!empty($assunto) && $assunto != 'null') ||
                    (!empty($interessado) && $interessado != 'null')) {
                $out['obrigatorio'] = 'true';
                $out['valido'] = 'true';
            } else {
                $out['obrigatorio'] = 'false';
                $out['valido'] = 'false';
                $out['success'] = 'false';
                return $out;
            }

            if (!empty($assunto) && $assunto != 'null') {
                $stmt = Controlador::getInstance()->getConnection()->connection->prepare("SELECT ID FROM TB_PROCESSOS_ASSUNTO WHERE ID = ? AND INTERESSADO_OBRIGATORIO = 1 LIMIT 1");
                $stmt->bindParam(1, $assunto, PDO::PARAM_STR);
                $stmt->execute();
                $assunto = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($assunto === false) {
                    $out['obrigatorio'] = 'false';
                }
            }

            if (!empty($interessado) && $interessado != 'null') {
                $sttt = Controlador::getInstance()->getConnection()->connection->prepare("SELECT ID FROM TB_PROCESSOS_INTERESSADOS WHERE CNPJ_CPF != '' AND upper(CNPJ_CPF) != 'EM BRANCO' AND ID = ? LIMIT 1");
                $sttt->bindParam(1, $interessado, PDO::PARAM_INT);
                $sttt->execute();
                $interessado = $sttt->fetch(PDO::FETCH_ASSOC);

                if ($interessado === false) {
                    $out['valido'] = 'false';
                }
            }

            $out['success'] = 'true';

            return $out;
        } catch (BasePDOException $e) {
            return array('success' => 'false', 'error' => $e->getMessage());
        }
    }

    /**
     * 
     */
    public static function getInteressado($interessado, $campo = false) {
        try {

            $campo = $campo ? $campo : '*';
            $condicao = filter_var($interessado, FILTER_VALIDATE_INT) ? 'ID' : 'NOME';

            $stmt = Controlador::getInstance()->getConnection()->connection->prepare("SELECT $campo FROM TB_PROCESSOS_INTERESSADOS WHERE $condicao = ? LIMIT 1");
            $stmt->bindParam(1, $interessado, PDO::PARAM_STR);
            $stmt->execute();
            $out = $stmt->fetch(PDO::FETCH_ASSOC);

            if (empty($out)) {
                $out['success'] = 'false';
            } else {
                $out['success'] = 'true';
                /* Padronizar com caixa baixa o os indices dos arrays */
                $out = array_change_key_case($out, CASE_LOWER);
                if ($campo === '*') {
                    return $out;
                }
                return $out[$campo];
            }

            return $out;
        } catch (PDOException $e) {
            return $out = array('success' => 'false', 'error' => $e->getMessage());
        }
    }

    /**
     * 
     */
    public static function novoInteressado($interessado, $cpf = 'Em Branco', $homologado = false) {
        try {
            /* Novo Interessado */

            $cpf = (strlen($cpf) > 0) ? $cpf : 'Em Branco';

            if ($cpf != 'Em Branco') {
                $stmm = Controlador::getInstance()->getConnection()->connection->prepare("SELECT INTERESSADO FROM TB_PROCESSOS_INTERESSADOS WHERE CNPJ_CPF = ? AND CNPJ_CPF != 'Em Branco' LIMIT 1");
                $stmm->bindParam(1, $cpf, PDO::PARAM_STR);
                $stmm->execute();

                $res = $stmm->fetch(PDO::FETCH_ASSOC);

                if (!empty($res)) {
                    $saida = new Output(array('success' => 'false', 'error' => "O interessado {$res['INTERESSADO']} já foi cadastrado com o cnpj/cpf informado!"));
                    return $saida->toArray();
                }
            }

            $stmm = Controlador::getInstance()->getConnection()->connection->prepare("SELECT count(*) as num FROM TB_PROCESSOS_INTERESSADOS WHERE CNPJ_CPF = ? AND INTERESSADO ILIKE ?");
            $stmm->bindParam(1, $cpf, PDO::PARAM_STR);
            $stmm->bindParam(2, $interessado, PDO::PARAM_STR);
            $stmm->execute();

            $res = $stmm->fetch(PDO::FETCH_ASSOC);

            if ($res['num'] > 0) {
                // Já está cadastrado, retornar sem sucesso
                $saida = new Output(array('success' => 'false', 'error' => 'Já existe interessado com este cpf/cnpj e este nome'));
                return $saida->toArray();
            }

            $id_unidade_usuario = Controlador::getInstance()->usuario->ID_UNIDADE;

            $stmt = Controlador::getInstance()->getConnection()->connection->prepare("INSERT INTO TB_PROCESSOS_INTERESSADOS (INTERESSADO,CNPJ_CPF,USUARIO,HOMOLOGADO,ID_UNIDADE_USUARIO) VALUES (?,?,?,?,?)");
            $stmt->bindParam(1, $interessado, PDO::PARAM_STR);
            $stmt->bindParam(2, $cpf, PDO::PARAM_STR);
            $stmt->bindParam(3, Zend_Auth::getInstance()->getIdentity()->ID, PDO::PARAM_INT);
            $stmt->bindParam(4, $homologado, PDO::PARAM_INT);
            $stmt->bindParam(5, $id_unidade_usuario, PDO::PARAM_INT);
            $stmt->execute();
            $out = array('success' => 'true', 'interessado' => $interessado, 'id' => Controlador::getInstance()->getConnection()->connection->lastInsertId('TB_PROCESSOS_INTERESSADOS_ID_SEQ'));

            if (!empty($out)) {
                return $out;
            }

            return null;
        } catch (PDOException $e) {
            throw new Exception($e);
        }
    }

    /**
     * 
     */
    public static function novaOrigemExterna($origem, $homologado = 0) {
        try {
            /* Nova Origem Externa */
            $homologado = (int) $homologado;

            $id_unidade_usuario = Controlador::getInstance()->usuario->ID_UNIDADE;

            $stmt = Controlador::getInstance()->getConnection()->connection->prepare("INSERT INTO TB_PROCESSOS_ORIGEM (ORIGEM,USUARIO,HOMOLOGADO,ID_UNIDADE_USUARIO) VALUES (?,?,?,?)");
            $stmt->bindParam(1, $origem, PDO::PARAM_STR);
            $stmt->bindParam(2, Zend_Auth::getInstance()->getIdentity()->ID, PDO::PARAM_INT);
            $stmt->bindParam(3, $homologado, PDO::PARAM_INT);
            $stmt->bindParam(4, $id_unidade_usuario, PDO::PARAM_INT);
            $stmt->execute();
            $out = array('success' => 'true', 'origem' => $origem, 'id' => Controlador::getInstance()->getConnection()->connection->lastInsertId('TB_PROCESSOS_ORIGEM_ID_SEQ'));

            if (!empty($out)) {
                return $out;
            }

            return null;
        } catch (PDOException $e) {
            throw new Exception($e);
        }
    }

    /**
     * Metodo Temporario
     * @todo Implementar no escopo correto assim que possivel
     */
    public static function getOrigemExterna($id, $campo) {
        try {

            $campo = $campo ? $campo : '*';

            $stmt = Controlador::getInstance()->getConnection()->connection->prepare("SELECT {$campo} FROM TB_PROCESSOS_ORIGEM WHERE ID = ? LIMIT 1");
            $stmt->bindParam(1, $id, PDO::PARAM_INT);
            $stmt->execute();

            return array_change_key_case($stmt->fetch(PDO::FETCH_ASSOC), CASE_LOWER);
        } catch (PDOException $e) {
            throw new Exception($e);
        }
    }

    /**
     * 
     */
    public function prepararAutuacao($digital, $interessado, $assunto, $assunto_complementar, $origem, $dt_prazo) {

        /* Obs: Informacoes importante devem vir antes da validacao das mesmas */
        $this->digital = $digital;
        $this->ano = (integer) date("Y");
        $this->assunto = (integer) $assunto;
        $this->assunto_complementar = ($assunto_complementar) ? trim($assunto_complementar) : 'Em Branco';
        $this->interessado = (integer) $interessado;
        $this->origem = (integer) $origem;

        $this->procedencia = 1;
        $this->id_unidade = Zend_Auth::getInstance()->getIdentity()->ID_UNIDADE;
        $this->area_trabalho = $this->id_unidade;
        $diretoria = DaoUnidade::getUnidade($this->id_unidade, 'sigla');
        $this->ultimo_tramite = "Área de Trabalho - {$diretoria}";
        $this->dt_cadastro = date("d/m/Y " . " - " . "H:i:s"); //somente para historico de tramites
        $this->dt_autuacao = date("Y-m-d");

        if (!$this->iisset($this->processo)) {
            throw new Exception('Informações importantes não estão presentes no ato da autuação deste processo!');
        }

        /* Obs: informacoes opcionais devem vir depois da validacao das mesmas */
        $this->dt_prazo = $dt_prazo ? (Util::formatDate($dt_prazo)) : NULL;

        return true;
    }

    /**
     * 
     */
    private function InserirSequencial() {
        try {

            $sttm = Controlador::getInstance()->getConnection()->connection->prepare("SELECT SEQUENCIAL FROM TB_PROCESSOS_SEQUENCIAL WHERE UNIDADE = ? AND ANO = ? LIMIT 1");
            $sttm->bindParam(1, $this->_usuario->id_unidade, PDO::PARAM_INT);
            $sttm->bindParam(2, $this->ano, PDO::PARAM_STR);
            $sttm->execute();
            $out = $sttm->fetch(PDO::FETCH_ASSOC);

            if (empty($out)) {
                $stmt = Controlador::getInstance()->getConnection()->connection->prepare("INSERT INTO TB_PROCESSOS_SEQUENCIAL (UNIDADE,SEQUENCIAL,ANO) VALUES (?,0,?)");
                $stmt->bindParam(1, $this->_usuario->id_unidade, PDO::PARAM_INT);
                $stmt->bindParam(2, $this->ano, PDO::PARAM_STR);
                $stmt->execute();
            }
        } catch (PDOException $e) {
            throw new Exception($e);
        }
    }

    /**
     * 
     */
    private function gerarNumeroProcesso() {

        try {

            $this->InserirSequencial();

            $stmt = Controlador::getInstance()->getConnection()->connection->prepare("UPDATE TB_PROCESSOS_SEQUENCIAL SET SEQUENCIAL = (SEQUENCIAL + 1) WHERE UNIDADE = ? AND ANO = ?");
            $stmt->bindParam(1, $this->_usuario->id_unidade, PDO::PARAM_INT);
            $stmt->bindParam(2, $this->ano, PDO::PARAM_INT);
            $stmt->execute();

            $sttm = Controlador::getInstance()->getConnection()->connection->prepare("SELECT SEQUENCIAL FROM TB_PROCESSOS_SEQUENCIAL WHERE UNIDADE = ? AND ANO = ? LIMIT 1");
            $sttm->bindParam(1, $this->_usuario->id_unidade, PDO::PARAM_INT);
            $sttm->bindParam(2, $this->ano, PDO::PARAM_STR);
            $sttm->execute();
            $out = $sttm->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception($e);
        }

        /* Variaveis Locais */
        $campo = array();
        $valor = array();
        $soma1 = 0;
        $soma2 = 0;
        $resto1 = 0;
        $resto2 = 0;
        $listar = DaoUnidade::getUnidade(null, 'codigo') . "." . Util::zeroFill($out['SEQUENCIAL'], 6) . "/" . $this->ano;
        $numeros = "";

        /* Variaveis Auxiliares */
        $numeros;
        $campo = array();
        $valor = array();
        /* Pegar Novo Sequencial */


        /* Criar Digito Verificador 1 */
        /* Remover Pontos e Barra */
        for ($i = 0; $i <= 16; $i++) {
            if ($i != 5 && $i != 12) {
                $numeros .= substr($listar, $i, 1);
            }
        }

        for ($i = 0; $i <= 16; $i++) {
            $campo[$i] = substr($numeros, $i, 1);
        }

        $posicao = 2;
        for ($i = 14; $i >= 0; $i--) {
            $valor[$i] = ($campo[$i] * $posicao);
            $posicao++;
        }

        for ($i = 0; $i <= 16; $i++) {
            $soma1 = $valor[$i] + $soma1;
        }

        $resto1 = $soma1 % 11;
        $aux = $dv1 = (11 - $resto1);
        if (strlen($aux) > 1) {
            $dv1 = substr($aux, 1, 1);
        } else {
            $dv1 = $aux;
        }

        /* Criar Digito Verificador 2 */

        $numeros = "";

        for ($i = 16; $i >= 0; $i--) {
            $numeros .= $campo[$i];
        }

        $numeros = $dv1 . $numeros;

        $posicao = 2;
        for ($i = 0; $i <= 15; $i++) {
            $campo[$i] = substr($numeros, $i, 1);
            $valor[$i] = ($campo[$i] * $posicao);
            $posicao++;
        }

        for ($i = 0; $i <= 16; $i++) {
            $soma2 = $valor[$i] + $soma2;
        }

        $resto2 = $soma2 % 11;
        $aux = $dv2 = (11 - $resto2);
        if (strlen($aux) > 1) {
            $dv2 = substr($aux, 1, 1);
        } else {
            $dv2 = $aux;
        }

        /* Setar Novo Numero do Processo */
        return $this->numero_processo = (string) ($listar . '-' . $dv1 . $dv2);
    }

    /**
     * 
     */
    public function salvarAutuacao() {

        $unidade = current(CFModelUnidade::factory()->find($this->_usuario->id_unidade));

        //verificar se a unidade autuadora é uma unidade protocolizadora...
        if (!$unidade->UP || !$unidade->CODIGO) {
            throw new Exception('Este processo não pode ser autuado porque você não está em uma unidade protocolizadora válida!');
        }

        if (!Documento::validarDocumentoPecaProcesso($this->digital)) {
            if (Documento::validarDocumentoAreaDeTrabalho($this->digital)) {
                if (Documento::getQuantidadeImagemDocumento($this->digital) > 0) {
                    try {

                        Controlador::getInstance()->getConnection()->connection->beginTransaction();

                        $id_unidade_usuario = (integer) Controlador::getInstance()->usuario->ID_UNIDADE;
                        $numero_processo = $this->gerarNumeroProcesso();

                        /* Adicionar o novo Processo */
                        $stmt = Controlador::getInstance()->getConnection()->connection->prepare("INSERT INTO TB_PROCESSOS_CADASTRO(
                            NUMERO_PROCESSO,ORIGEM,ASSUNTO,ASSUNTO_COMPLEMENTAR,DT_AUTUACAO,DT_PRAZO,
                            DT_CADASTRO,USUARIO,INTERESSADO,ID_UNID_AREA_TRABALHO,ULTIMO_TRAMITE,PROCEDENCIA,ID_UNIDADE_USUARIO) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)");

                        $stmt->bindParam(1, $numero_processo, PDO::PARAM_STR);
                        $stmt->bindParam(2, $this->origem, PDO::PARAM_INT);
                        $stmt->bindParam(3, $this->assunto, PDO::PARAM_INT);
                        $stmt->bindParam(4, $this->assunto_complementar, PDO::PARAM_STR);
                        $stmt->bindParam(5, $this->dt_autuacao, PDO::PARAM_STR);
                        $stmt->bindParam(6, $this->dt_prazo, PDO::PARAM_STR);
                        $stmt->bindParam(7, $this->dt_autuacao, PDO::PARAM_STR);
                        $stmt->bindParam(8, $this->_usuario->id, PDO::PARAM_INT);
                        $stmt->bindParam(9, $this->interessado, PDO::PARAM_INT);
                        $stmt->bindParam(10, $this->area_trabalho, PDO::PARAM_INT);
                        $stmt->bindParam(11, $this->ultimo_tramite, PDO::PARAM_STR);
                        $stmt->bindParam(12, $this->procedencia, PDO::PARAM_STR);
                        $stmt->bindParam(13, $id_unidade_usuario, PDO::PARAM_STR);
                        $stmt->execute();

                        /* Armazenar o id do processo */
                        $id_processo = Controlador::getInstance()->getConnection()->connection->lastInsertId('TB_PROCESSOS_CADASTRO_ID_SEQ');

                        /* Registrar o primeiro volume do processo */
                        $volume = Controlador::getInstance()->getConnection()->connection->prepare("INSERT INTO TB_PROCESSOS_VOLUME (ID_PROCESSO_CADASTRO, NU_VOLUME, FL_INICIAL, DT_ABERTURA,ID_USUARIO,ID_UNIDADE) VALUES (?, 1, 1,?,?,?)");
                        $volume->bindParam(1, $id_processo, PDO::PARAM_INT); //id do processo
                        $volume->bindParam(2, $this->dt_autuacao, PDO::PARAM_STR); //usar a data da autuacao para informar a data de inicio do primeiro volume.
                        $volume->bindParam(3, $this->_usuario->id, PDO::PARAM_INT);
                        $volume->bindParam(4, $id_unidade_usuario, PDO::PARAM_INT);
                        $volume->execute();

                        /* Adicionar a documento no processo */
                        $peca = Controlador::getInstance()->getConnection()->connection->prepare("INSERT INTO TB_PROCESSOS_DOCUMENTOS(ID_PROCESSOS_CADASTRO,ID_DOCUMENTOS_CADASTRO,ID_USUARIOS,ID_UNIDADE_USUARIO)VALUES(?,?,?,?)");
                        $peca->bindParam(1, $id_processo, PDO::PARAM_INT); //id_processo
                        $peca->bindParam(2, DaoDocumento::getDocumento($this->digital, 'id'), PDO::PARAM_INT); //id_documento
                        $peca->bindParam(3, $this->_usuario->id, PDO::PARAM_INT);
                        $peca->bindParam(4, $id_unidade_usuario, PDO::PARAM_INT);
                        $peca->execute();

                        /* Inserir o historico de tramite do processo */
                        /* Complemento do Historico do Processo */
                        $acao = "Processo Autuado a partir do documento $this->digital";
                        $destino = "XXXXX";

                        $id_usuario = $this->_usuario->id;
                        $nome_usuario = $this->_usuario->nome;
                        $id_unidade = Zend_Auth::getInstance()->getIdentity()->ID_UNIDADE;
                        $objOrigem = DaoUnidade::getUnidade($id_unidade);
                        $diretoria = $objOrigem['nome'];
                        $tx_origem = $objOrigem['nome'] . ' - ' . $objOrigem['sigla'];

                        $historico_processo = Controlador::getInstance()->getConnection()->connection->prepare("INSERT INTO TB_HISTORICO_TRAMITE_PROCESSOS"
                                . " (NUMERO_PROCESSO,ID_USUARIO,USUARIO,ID_UNIDADE,DIRETORIA,ACAO,ORIGEM,DESTINO,DT_TRAMITE)"
                                . " VALUES(?,?,?,?,?,?,?,?,CLOCK_TIMESTAMP())");
                        $historico_processo->bindParam(1, $this->numero_processo, PDO::PARAM_STR);
                        $historico_processo->bindParam(2, $id_usuario, PDO::PARAM_INT);
                        $historico_processo->bindParam(3, $nome_usuario, PDO::PARAM_STR);
                        $historico_processo->bindParam(4, $id_unidade, PDO::PARAM_INT);
                        $historico_processo->bindParam(5, $diretoria, PDO::PARAM_STR);
                        $historico_processo->bindParam(6, $acao, PDO::PARAM_STR);
                        $historico_processo->bindParam(7, $tx_origem, PDO::PARAM_STR);
                        $historico_processo->bindParam(8, $destino, PDO::PARAM_STR);
                        $historico_processo->execute();

                        /* Complemento do Historico do Documento */
                        $acao = "O processo $this->numero_processo foi autuado a partir deste documento.";
                        $destino = "XXXXX";

                        $historico_documento = Controlador::getInstance()->getConnection()->connection->prepare("INSERT INTO TB_HISTORICO_TRAMITE_DOCUMENTOS"
                                . " (DIGITAL,ID_USUARIO,USUARIO,ID_UNIDADE,DIRETORIA,ACAO,ORIGEM,DESTINO,DT_TRAMITE)"
                                . " VALUES (?,?,?,?,?,?,?,?,CLOCK_TIMESTAMP())");
                        $historico_documento->bindParam(1, $this->digital, PDO::PARAM_STR);
                        $historico_documento->bindParam(2, $id_usuario, PDO::PARAM_INT);
                        $historico_documento->bindParam(3, $nome_usuario, PDO::PARAM_STR);
                        $historico_documento->bindParam(4, $id_unidade, PDO::PARAM_INT);
                        $historico_documento->bindParam(5, $diretoria, PDO::PARAM_STR);
                        $historico_documento->bindParam(6, $acao, PDO::PARAM_STR);
                        $historico_documento->bindParam(7, $tx_origem, PDO::PARAM_STR);
                        $historico_documento->bindParam(8, $destino, PDO::PARAM_STR);
                        $historico_documento->execute();

                        DaoDocumento::updateGenerico($this->digital, array('ultimo_tramite' => $acao));

                        Controlador::getInstance()->getConnection()->connection->commit();

                        $this->out = array('success' => 'true', 'numero_processo' => $this->numero_processo);

                        return true;
                    } catch (PDOException $e) {
                        Controlador::getInstance()->getConnection()->connection->rollback();
                        echo $e->getMessage();
                        return false;
                        throw new Exception($e);
                    }
                } else {
                    $this->out = array('success' => 'false', 'error' => 'Este documento não possui imagem!');
                }
            } else {
                $this->out = array('success' => 'false', 'error' => 'Este documento não está na Área de Trabalho!');
            }
        } else {
            $this->out = array('success' => 'false', 'error' => 'Este documento já é peça de outro processo!');
        }
    }

    /**
     * 
     */
    public function prepararCadastro($numero_processo, $interessado, $assunto, $assunto_complementar, $tipo_origem, $origem, $dt_autuacao, $dt_prazo) {

        /* Obs: Informacoes importante devem vir antes da validacao das mesmas */
        $this->numero_processo = $numero_processo;
        $this->assunto = trim($assunto);
        $this->assunto_complementar = ($assunto_complementar) ? ($assunto_complementar) : 'Em Branco';
        $this->interessado = str_replace(' ', '', trim($interessado));
        $this->dt_autuacao = Util::formatDate($dt_autuacao);
        $this->origem = trim($origem);
        $this->ano = date("Y");
        $diretoria = DaoUnidade::getUnidade(Controlador::getInstance()->usuario->ID_UNIDADE, 'sigla');
        $this->procedencia = $tipo_origem;
        $this->ultimo_tramite = "Área de Trabalho - {$diretoria}";
        $this->dt_cadastro = date("Y-m-d");
        $this->area_trabalho = $diretoria;
        $this->id_unidade = Controlador::getInstance()->usuario->ID_UNIDADE;

        if (!$this->iisset($this->processo)) {
            throw new Exception('Informações importantes não estão presentes no ato do cadastro deste processo!');
        }

        /* Obs: informacoes opcionais devem vir depois da validacao das mesmas */
        $this->dt_prazo = $dt_prazo ? (Util::formatDate($dt_prazo)) : NULL;

        return true;
    }

    /**
     * @return string
     */
    public function salvarCadastro() {

        try {


            Controlador::getInstance()->getConnection()->connection->beginTransaction();


            /* Cadastrar Processo */
            $stmt = Controlador::getInstance()->getConnection()->connection->prepare("INSERT INTO TB_PROCESSOS_CADASTRO(NUMERO_PROCESSO,ORIGEM,ASSUNTO,
                    ASSUNTO_COMPLEMENTAR,DT_AUTUACAO,DT_PRAZO,DT_CADASTRO,USUARIO,INTERESSADO,ID_UNID_AREA_TRABALHO,ULTIMO_TRAMITE,
                    PROCEDENCIA,ID_UNIDADE_USUARIO) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)");

            /**
             * BugFix Notice
             */
            $numero_processo = $this->numero_processo;
            $origem = $this->origem;
            $assunto = $this->assunto;
            $assunto_complementar = $this->assunto_complementar;
            $dt_autuacao = $this->dt_autuacao;
            $dt_prazo = $this->dt_prazo;
            $dt_cadastro = $this->dt_cadastro;
            $interessado = $this->interessado;
            $ultimo_tramite = $this->ultimo_tramite;
            $procedencia = $this->procedencia;
            $id_usuario = $this->_usuario->id;
            $nome_usuario = $this->_usuario->nome;
            $id_unidade = Zend_Auth::getInstance()->getIdentity()->ID_UNIDADE;
            $id_unidade_historico = Zend_Auth::getInstance()->getIdentity()->ID_UNIDADE;
            $diretoria = DaoUnidade::getUnidade($id_unidade_historico, 'nome');
            $objOrigem = DaoUnidade::getUnidade($id_unidade);
            $tx_origem = $objOrigem['nome'] . ' - ' . $objOrigem['sigla'];

            $id_unidade_usuario = Controlador::getInstance()->usuario->ID_UNIDADE;

            $stmt->bindParam(1, $numero_processo, PDO::PARAM_STR); //numero_processo
            $stmt->bindParam(2, $origem, PDO::PARAM_INT); //origem
            $stmt->bindParam(3, $assunto, PDO::PARAM_INT); //assunto
            $stmt->bindParam(4, $assunto_complementar, PDO::PARAM_STR); //assunto_complementar
            $stmt->bindParam(5, $dt_autuacao, PDO::PARAM_STR); //autuacao
            $stmt->bindParam(6, $dt_prazo, PDO::PARAM_STR); //data_prazo
            $stmt->bindParam(7, $dt_cadastro, PDO::PARAM_STR); //dt_cadastro
            $stmt->bindParam(8, $id_usuario, PDO::PARAM_INT); //usuario
            $stmt->bindParam(9, $interessado, PDO::PARAM_INT); //interessado
            $stmt->bindParam(10, $id_unidade, PDO::PARAM_INT); //id_unid_area_trabalho
            $stmt->bindParam(11, $ultimo_tramite, PDO::PARAM_STR); //ultimo_tramite
            $stmt->bindParam(12, $procedencia, PDO::PARAM_INT); //procedencia
            $stmt->bindParam(13, $id_unidade_usuario, PDO::PARAM_INT); //procedencia
            $stmt->execute();

            /* Registrar o primeiro volume do processo */
            $volume = Controlador::getInstance()->getConnection()->connection->prepare("INSERT INTO TB_PROCESSOS_VOLUME (ID_PROCESSO_CADASTRO, NU_VOLUME, FL_INICIAL, DT_ABERTURA,ID_USUARIO,ID_UNIDADE) VALUES (?, 1, 1,?,?,?)");
            $volume->bindParam(1, Controlador::getInstance()->getConnection()->connection->lastInsertId('TB_PROCESSOS_CADASTRO_ID_SEQ'), PDO::PARAM_INT); //id do processo
            $volume->bindParam(2, $dt_autuacao, PDO::PARAM_STR); //usar a data da autuacao para informar a data de inicio do primeiro volume.
            $volume->bindParam(3, $id_usuario, PDO::PARAM_INT);
            $volume->bindParam(4, $id_unidade_usuario, PDO::PARAM_INT);
            $volume->execute();

            /* Inserir o historico de tramite do processo */
            /* Complemento do Historico do Processo */
            $acao = "Processo Cadastrado.";
            $destino = "XXXXX";

            $historico_processo = Controlador::getInstance()->getConnection()->connection->prepare("INSERT INTO TB_HISTORICO_TRAMITE_PROCESSOS"
                    . " (NUMERO_PROCESSO,ID_USUARIO,USUARIO,ID_UNIDADE,DIRETORIA,ACAO,ORIGEM,DESTINO,DT_TRAMITE)"
                    . " VALUES(?,?,?,?,?,?,?,?,CLOCK_TIMESTAMP())");

            $historico_processo->bindParam(1, $numero_processo, PDO::PARAM_STR);
            $historico_processo->bindParam(2, $id_usuario, PDO::PARAM_INT);
            $historico_processo->bindParam(3, $nome_usuario, PDO::PARAM_STR);
            $historico_processo->bindParam(4, $id_unidade_historico, PDO::PARAM_INT);
            $historico_processo->bindParam(5, $diretoria, PDO::PARAM_STR);
            $historico_processo->bindParam(6, $acao, PDO::PARAM_STR);
            $historico_processo->bindParam(7, $tx_origem, PDO::PARAM_STR);
            $historico_processo->bindParam(8, $destino, PDO::PARAM_STR);
            $historico_processo->execute();

            Controlador::getInstance()->getConnection()->connection->commit();

            $this->out = array('success' => 'true', 'mensagem' => "Processo $numero_processo cadastrado com sucesso!");
        } catch (PDOException $e) {
            Controlador::getInstance()->getConnection()->connection->rollback();
            throw new BasePDOException($e);
        }
    }

    /**
     * 
     */
    public static function getAssunto($assunto = false, $campo = false) {
        try {

            $campo = $campo ? $campo : '*';
            $condicao = filter_var($assunto, FILTER_VALIDATE_INT) ? 'ID' : 'ASSUNTO';

            $stmt = Controlador::getInstance()->getConnection()->connection->prepare("SELECT $campo FROM TB_PROCESSOS_ASSUNTO WHERE $condicao = ? LIMIT 1");
            $stmt->bindParam(1, $assunto, PDO::PARAM_STR);
            $stmt->execute();
            $out = $stmt->fetch(PDO::FETCH_ASSOC);

            if (empty($out)) {
                $out['success'] = 'false';
            } else {
                $out['success'] = 'true';
                /* Padronizar com caixa baixa o os indices dos arrays */
                $out = array_change_key_case($out, CASE_LOWER);
                if ($campo === '*') {
                    return $out;
                }
                return $out[$campo];
            }

            return false;
        } catch (PDOException $e) {
            throw new BasePDOException($e);
        }
    }

    /**
     * 
     */
    public static function gerarEtiquetaProcessoByNumero($numero_processo) {

        $sql = "SELECT    
                    PC.NUMERO_PROCESSO,
                    to_char(PC.DT_AUTUACAO, 'dd/mm/yyyy') AS AUTUACAO,
                    IP.INTERESSADO AS INTERESSADO,
                    PA.ASSUNTO AS ASSUNTO,
                    PC.ASSUNTO_COMPLEMENTAR,
                    PC.PROCEDENCIA,
                    DC.DIGITAL,
                    DC.TIPO,
                    DC.NUMERO
                FROM TB_PROCESSOS_CADASTRO PC
                    LEFT JOIN TB_PROCESSOS_ASSUNTO PA ON PA.ID = PC.ASSUNTO
                    LEFT JOIN TB_PROCESSOS_INTERESSADOS IP ON IP.ID = PC.INTERESSADO
                    LEFT JOIN TB_PROCESSOS_DOCUMENTOS PXD ON PXD.ID_PROCESSOS_CADASTRO = PC.ID
                    LEFT JOIN TB_DOCUMENTOS_CADASTRO DC ON DC.ID = PXD.ID_DOCUMENTOS_CADASTRO
                WHERE PC.NUMERO_PROCESSO = ? ORDER BY PXD.ID ASC LIMIT 1"
        ;

        try {

            $stmt = Controlador::getInstance()->getConnection()->connection->prepare($sql);
            $stmt->bindParam(1, $numero_processo, PDO::PARAM_STR);
            $stmt->execute();
            $TMPL = $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new BasePDOException($e);
        }

        require_once ('tpl/template_etiqueta_processo.php');
    }

}