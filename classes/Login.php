<?php

/**
 * Copyright 2011 ICMBio
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
 *
 */

/**
 * Classe responsável por validar o usuario para acessar o sistema.
 * Retira usuários inativos por muito tempo
 * Cria log do usuário que se loga no sistema
 * Criar sessão através do Zend_Auth.
 * requer arquivo administrador/conexão.php
 */
class Login {

    private $_db;
    private $_params;
    private $_auth;

    /**
     * Se o usuário tentar apenas uma vez, não terá captcha.
     */

    const SEM_CAPTCHA = 1;
    const CAPTCHA_VALIDO = 2;
    const CAPTCHA_INVALIDO = 3;

    /**
     * Mensagens de retorno.
     */
    const MSG_CODIGO_INVALIDO = 'Código inválido.';
    const MSG_USUARIO_INATIVO = 'Usuário inativo.';
    const MSG_CAMPO_NAO_INFORMADO = 'Informar usuário e senha para acessar o sistema.';
    const MSG_USUARIO_INVALIDO = 'Problemas ao efetuar o login, usuário ou senha inválido.';
    const REGISTRO_NAO_ALTERADO = 'Registro não alterado.';
    const REGISTRO_NAO_INSERIDO = 'Registro não inserido.';

    /**
     * Url que será redirecionada. 
     */
    const URL = '';

    /**
     *
     * Inicializa as variáveis que serão utilizadas no decorrer da classe.
     * @param array $params [captcha_usuario,usuario,senha]
     * @param Zend_Db_Adapter_Pdo_Pgsql $dbTable
     * @return void
     */
    public function __construct(array $params = array(), $dbTable = NULL) {
        $this->_params = $params;
        $this->_db = $dbTable;
        $this->_auth = Zend_Auth::getInstance();
    }

    /**
     * @return Login
     * @param array $params
     * @param Zend_Db_Adapter_Pdo_Pgsql $dbTable
     */
    public static function factory(array $params = array(), $dbTable = NULL) {
        if (is_null($dbTable)) {
            $dbTable = Config::factory()->buildDBConfig()->getZendDbTable();
        }
        return new self($params, $dbTable);
    }

    /**
     * Pega a sessão do captcha para verificação da integridade da captcha.
     *
     * const SEM_CAPTCHA      = 1;
     * const CAPTCHA_VALIDO   = 2;
     * const CAPTCHA_INVALIDO = 3;
     * @return integer
     */
    public function validaCaptcha() {
        $sessionCaptcha = new Zend_Session_Namespace('captcha');

        //contador de tentativas de entrada no sistema.
        $contTentativas = (integer) count($sessionCaptcha->tentativas);
        $sessionCaptcha->tentativas[] = 1;

        //palavra que está na sessão captcha.
        $sessaoCaptcha = $sessionCaptcha->getIterator();

        $resultado = self::SEM_CAPTCHA;
        if (!empty($this->_params['captcha_usuario']) && $this->_params['captcha_usuario'] === $sessaoCaptcha['word'] && ($contTentativas > 0)) {
            $resultado = self::CAPTCHA_VALIDO;
        } elseif (empty($this->_params['captcha_usuario']) && $contTentativas == 0) {
            $resultado = self::SEM_CAPTCHA;
        } else {
            $resultado = self::CAPTCHA_INVALIDO;
        }
        return $resultado;
    }

    /**
     *
     * @return stdClass
     * @throws Exception 
     */
    public function validaLogin() {
        $sucesso = new stdClass();
        $captcha = $this->validaCaptcha();
        try {
            if (!empty($this->_params['usuario']) && !empty($this->_params['senha'])) {
                if ($captcha == self::CAPTCHA_VALIDO || $captcha == self::SEM_CAPTCHA) {
                    $rsCredenciaisLogin = $this->validaCredenciaisLogin();

                    if (isset($rsCredenciaisLogin->success)) {

                        $rsUltimoAcesso = $this->ultimoAcesso();
                        if (isset($rsUltimoAcesso->success)) {

                            $sucesso->success = true;
                            $sucesso->url = self::URL;

                            //limpo a sessão do captcha.
                            Zend_Session::namespaceUnset('captcha');
                        } else {
                            throw new Exception($rsUltimoAcesso->error);
                        }
                    } else {
                        throw new Exception($rsCredenciaisLogin->error);
                    }
                } else {
                    throw new Exception(self::MSG_CODIGO_INVALIDO);
                }
            } else {
                throw new Exception(self::MSG_CAMPO_NAO_INFORMADO);
            }
        } catch (Exception $e) {
            $sucesso->error = $e->getMessage();
        }
        return $sucesso;
    }

    /**
     * Gera Zend_Auth para autenticação de usuário no sistema.
     * Válida os dados através do banco de dados instanciado pelo zend_db...
     */
    public function validaCredenciaisLogin() {
        $sucesso = new stdClass();

        $authAdapter = new Zend_Auth_Adapter_DbTable($this->_db);

        $authAdapter->setTableName('sgdoc.TB_USUARIOS')
                ->setIdentityColumn('usuario')
                ->setCredentialColumn('senha')
                ->setCredentialTreatment('?');

        //pode ter mais que um cadastro.
        //$authAdapter->setAmbiguityIdentity(1);
        $authAdapter->setIdentity($this->_params['usuario']);
        $authAdapter->setCredential($this->_params['senha']);

        $result = $this->_auth->authenticate($authAdapter);

        try {
            if ($result->isValid() == true) {
                $data = $authAdapter->getResultRowObject(null, 'SENHA');

                if ($data->STATUS == 1) {

                    //armazena o usuário na sessão.
                    $data->MANAGER = __CAM_UPLOAD__;
                    $data->ID_UNIDADE_ORIGINAL = $data->ID_UNIDADE;
                    $this->_auth->getStorage()->write($data);
                    $authNamespace = new Zend_Session_Namespace('auth');
                    // timeout deve ser a hora atual + __MAXIMO_MINUTOS_SESSAO__ * 60
                    $authNamespace->timeout = time() + (__MAXIMO_MINUTOS_SESSAO__ * 60);
                } else {
                    throw new Exception(self::MSG_USUARIO_INATIVO);
                }
            } else {
                throw new Exception(self::MSG_USUARIO_INVALIDO);
            }
            $sucesso->success = true;
        } catch (Exception $e) {
            $this->_auth->clearIdentity();
            $sucesso->error = $e->getMessage();
            $this->_db->closeConnection();
        }
        return $sucesso;
    }

    public function clearAuth() {
        return $this->_auth->clearIdentity();
    }

    /**
     * Cria um log de usuário manualmente 
     * para identificar a hora que o usuário acessou o sistema.
     * 
     * Query antiga:
     * $sql = "INSERT INTO TB_CONTROLE_ACESSO (ID_USUARIO, DT_ACESSO, IP_ACESSO
     * ) VALUES (
     * (SELECT ID AS ID_USUARIO FROM TB_USUARIOS WHERE USUARIO='$USUARIO' 
     * LIMIT 1), CURRENT_TIMESTAMP(), '$IP');";
     * 
     * @todos Botar camada de banco de dados no seu devido lugar !!!!
     * 
     * @return type
     * @throws Exception 
     */
    public function logAcesso() {
        $sucesso = new stdClass();
        $ip = self::getRealIpAddr();
        $idUsuario = (integer) $this->_auth->getIdentity()->ID;

        $dados = array();
        $dados['IP_ACESSO'] = $ip;
        $dados['ID_USUARIO'] = $idUsuario;

        try {
            //camada de dados.
            $dados['DT_ACESSO'] = Zend_Date::now()->get('YYYY-MM-dd HH:mm:ss');

            $this->_db->beginTransaction();

            $this->_db->insert('TB_CONTROLE_ACESSO', $dados);

            $rsId = (integer) $this->_db->lastInsertId('TB_CONTROLE_ACESSO', 'id');

            if ($rsId > 0) {
                $this->_db->commit();
            } else {
                throw new Exception(self::REGISTRO_NAO_INSERIDO);
            }
            //fim camada banco de dados.

            $sucesso->success = $rsId;
        } catch (Exception $e) {
            $this->_db->rollBack();
            $sucesso->error = $e->getMessage();
            $this->_db->closeConnection();
        }
        return $sucesso;
    }

    /**
     * @deprecated
     * @todo colocar essa camada de banco de dados no seu devido lugar.
     * 
     * Query antida:
     * SELECT
     * DATEDIFF(CURRENT_DATE(),A.DT_ACESSO) AS DIAS
     * FROM TB_USUARIOS U
     * INNER JOIN TB_CONTROLE_ACESSO A ON U.ID = A.ID_USUARIO
     * WHERE USUARIO = '$usuario'
     * ORDER BY A.ID DESC
     * LIMIT 1;
     * @return stdClass
     */
    public function ultimoAcesso() {
        $idUsuario = (integer) $this->_auth->getIdentity()->ID;
        $result = new stdClass();

        //camada de banco de dados
        $sql = "SELECT A.DT_ACESSO FROM TB_CONTROLE_ACESSO AS A WHERE A.ID_USUARIO = ? ORDER BY A.ID DESC LIMIT 1";

        $stmt = $this->_db->prepare($sql);
        $stmt->bindParam(1, $idUsuario, PDO::PARAM_INT);
        $stmt->execute();
        $rs = $stmt->fetch(PDO::FETCH_ASSOC);

        //fim camada de banco de dados;

        $ultimo_acesso = new Zend_Date($rs['DT_ACESSO'], 'YYYY-MM-dd HH:mm:ss');

        $hoje = new Zend_Date();

        $seconds = $hoje->getTimestamp() - $ultimo_acesso->getTimestamp();

        $dias = floor($seconds / 60 / 60 / 24);

        $this->logAcesso();

        try {
            if ($dias > __MAXIMO_DIAS_NAO_LOGADO__) {
                $rsDesabilitarUsuario = $this->desabilitarUsuario();
                $this->clearAuth();
                if (isset($rsDesabilitarUsuario->error)) {
                    throw new Exception($rsDesabilitarUsuario->error);
                } else {
                    throw new Exception(self::MSG_USUARIO_INATIVO);
                }
            }
            $result->success = $dias;
        } catch (Exception $e) {
            $result->error = $e->getMessage();
            $this->_db->closeConnection();
        }
        return $result;
    }

    /**
     * @deprecated
     * @todo colocar essa função nas classes responsáveis pelo banco de dados.
     * Query Antiga:
     * $sql = "UPDATE TB_USUARIOS SET STATUS = '0' WHERE USUARIO = '$USUARIO' LIMIT 1";
     * 
     */
    public function desabilitarUsuario() {
        $sucesso = new stdClass();
        $idUsuario = (integer) $this->_auth->getIdentity()->ID;
        try {

            $dados = array();
            $dados['STATUS'] = 0;
            $where = "ID = {$idUsuario}";

            //camada banco de dados.
            $this->_db->beginTransaction();
            $rsAlterar = $this->_db->update('TB_USUARIOS', $dados, $where);
            if ($rsAlterar == true) {
                $this->_db->commit();
            } else {
                throw new Exception(self::REGISTRO_NAO_ALTERADO);
            }
            //fim camada banco de dados.

            $sucesso->success = true;
        } catch (Exception $e) {
            $this->_db->rollBack();
            $sucesso->error = $e->getMessage();
            $this->_db->closeConnection();
        }
        return $sucesso;
    }

    /**
     * @deprecated
     * @todo Adicionei essa classe em UTILS, verificar depois.!!!!
     */
    public static function getRealIpAddr() {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {//check ip from share internet
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {   //to check ip is pass from proxy
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }

    /**
     * @deprecated
     */
    public function makeUrl($id_usuario) {
        $hash = md5(rand(0, 999999999));
        $data = date('Y-m-d h:m:s');

        try {

            $stmt = Controlador::getInstance()->getConnection()->connection->prepare("INSERT INTO TB_CONTROLE_SENHA(ID_USUARIO, URL, DT_SOLICITADA)VALUES(?, ?, ?)");
            $stmt->bindParam(1, $id_usuario);
            $stmt->bindParam(2, $hash);
            $stmt->bindParam(3, $data);

            if ($stmt->execute()) {
                return $hash;
            }
        } catch (PDOException $e) {
            throw new Exception($e);
        }
        return null;
    }

    /**
     * @deprecated
     */
    public function sendEmail($email, $nome, $url) {
        set_time_limit(60000);
        date_default_timezone_set('America/Sao_Paulo');

        $link = __URLSERVERAPP__ . "/recuperar_senha_usuario.php?key=" . $url;
        $corpo = "<html>
    <head>
        <title>" . __ETIQUETA__ . "</title>
          <meta http-equiv='Content-Type' content='text/html; charset=iso-8859-1'>
    </head>
    <body>
        <div id='geral' style='
             margin: 0 auto;
             width: 610px;
             height: 200px;
             padding: 2px;'>
            <div id='logo' style='
                 margin: 0 auto;
                 width: 200px;
                 height: 200px;
                 float: left;'><img id='image'
                   style='
                   width: 150px;
                   height: 150px;' src='" . __URLSERVERAPP__ . "/imagens/icone.png'></div>
                <div id='mensagem' style='
                 margin: 0 auto;
                 width: 400px;
                 height: 200px;
                 float: right;
                 font-family: Verdana, Geneva, sans-serif;
                 font-size: 12px;
                 color: #799936;
                 font-weight: bold;
                 '>
                Olá, {$nome}.<br>
                <p>Esqueceu sua senha?
                               <a href='$link'>clique aqui para recupera-lá.</a><br><br>
                    Se não foi você que solicitou a recuperação de senha, por favor, encaminhe este email para <strong>sgdoc@icmbio.gov.br</strong></p></div>
        </div>
    </body>
</html>";

        $mail = new PHPMailer();
        $mail->CharSet = 'UTF-8';
        $mail->IsSendmail();
        $mail->SetFrom('sgdoc@sgdoc.gov.br', 'SGDoc');
        $mail->AddAddress($email);
        $mail->Subject = "SGDoc - Esqueceu sua senha?";
        $mail->MsgHTML($corpo);

        if (!$mail->Send()) {
            return false;
        }
        return true;
    }

    /**
     * @deprecated
     */
    public function validarCPF($cpf) {
        try {

            $stmt = Controlador::getInstance()->getConnection()->connection->prepare('SELECT ID, CPF, NOME, EMAIL FROM TB_USUARIOS WHERE CPF = ?');
            $stmt->bindParam(1, $cpf);
            $stmt->execute();
            $resul = $stmt->fetch(PDO::FETCH_ASSOC);

            if (empty($resul)) {
                return null;
            }
        } catch (PDOException $e) {
            throw new Exception($e);
        }
        return $resul;
    }

    /**
     * @deprecated
     */
    public function validateHash($hash) {
        try {

            $stmt = Controlador::getInstance()->getConnection()->connection->prepare('SELECT * FROM TB_CONTROLE_SENHA WHERE URL = ?');
            $stmt->bindParam(1, $hash);
            $stmt->execute();
            $resul = $stmt->fetch(PDO::FETCH_ASSOC);

            if (empty($resul)) {
                return null;
            } else if ($this->diffDate($resul['DT_SOLICITADA']) >= 3) {
                $stmt = Controlador::getInstance()->getConnection()->connection->prepare('UPDATE TB_CONTROLE_SENHA SET STATUS = 1 WHERE URL = ?');
                $stmt->bindParam(1, $hash);
                $stmt->execute();
                return null;
            }
        } catch (PDOException $e) {
            throw new Exception($e);
        }
        return $resul;
    }

    /**
     * @deprecated
     */
    public function getCpf($id) {
        try {

            $stmt = Controlador::getInstance()->getConnection()->connection->prepare('SELECT CPF FROM TB_USUARIOS WHERE ID = ?');
            $stmt->bindParam(1, $id);
            $stmt->execute();
            $resul = $stmt->fetch(PDO::FETCH_ASSOC);
            return $resul;
        } catch (PDOException $e) {
            throw new Exception($e);
        }
    }

    /**
     * @deprecated
     */
    public function updatePass($password, $id) {
        try {
            $resul = $this->getCpf($id);


            $stmt = Controlador::getInstance()->getConnection()->connection->prepare('UPDATE TB_USUARIOS SET SENHA = ? WHERE CPF = ?');
            $stmt->bindParam(1, md5($password));
            $stmt->bindParam(2, $resul['CPF']);
            return $stmt->execute();
        } catch (PDOException $e) {
            throw new Exception($e);
        }
    }

    /**
     * @deprecated
     */
    public function updateHash($hash) {
        try {

            $stmt = Controlador::getInstance()->getConnection()->connection->prepare('UPDATE TB_CONTROLE_SENHA SET STATUS = 1 WHERE URL = ?');
            $stmt->bindParam(1, $hash);

            return $stmt->execute();
        } catch (PDOException $e) {
            throw new Exception($e);
        }
    }

    /**
     * @deprecated
     */
    public function diffDate($data) {
        $end = strtotime(date('Y-m-d h:m:s'));
        $start = strtotime($data);
        $var = intval(($end - $start) / 86400);

        if ($var >= 3) {
            return true;
        }
        return false;
    }

    /**
     * @deprecated
     */
//    public function logAcesso($id_usuario) {
//        $ip = getIpAddr();
//        try {
//
//            $stmt = Controlador::getInstance()->getConnection()->connection->prepare("INSERT INTO TB_CONTROLE_ACESSO (ID_USUARIO, DT_ACESSO, IP_ACESSO) VALUES (?, CLOCK_TIMESTAMP(), ?)");
//            $stmt->bindParam(1, $id_usuario);
//            $stmt->bindParam(2, $ip);
//
//            $stmt->execute();
//        } catch (PDOException $e) {
//            throw new Exception($e);
//        }
//    }

    /**
     * @deprecated
     */
    public function getIpAddr() {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {//check ip from share internet
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {   //to check ip is pass from proxy
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }

}