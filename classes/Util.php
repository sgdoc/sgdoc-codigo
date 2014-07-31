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
class Util {

    /**
     * Constrói os menus de uma página com base no recurso ativo e no acl
     * @param StdClass $usuario
     * @param Recurso  $recurso
     * @param Core_Acl $acl
     * 
     * return array
     */
    public static function getMenus($usuario, Recurso $recurso, Core_Acl $acl) {

        $retorno = array();
        // Verificar permissao de cada botao em $recurso->filhos
        foreach ($recurso->filhos as $botao) {
            $hasBotao = AclFactory::checaPermissao($acl, $usuario, $botao);

            // verifica se no fim das contas o botão foi inserido
            if ($hasBotao) {
                // botão deve ser inserido
                // verifica se o botao em questao tem um dialog
                if ($botao->hasDialog()) {
                    $botao = $botao->dialog;
                }

                // verifica se o botao em questao é de um dialog
                // caso positivo, adicioná-lo às dependencias
                if ($botao->id_recurso_tipo == Recurso::TIPO_DIALOG && $botao->hasUrl()) {
                    $recurso->addDependencia($botao);
                }

                // verifica se o recurso em questão é de uma aba
                // caso positivo, adicioná-la à lista de abas
                if ($botao->id_recurso_tipo == Recurso::TIPO_ABA) {
                    $recurso->addAba($botao);

                    // verifica se a aba possui dependencias
                    if (is_array($botao->filhos)) {
                        foreach ($botao->filhos as $dependencia) {
                            if ($dependencia->hasUrl() &&
                                    ($dependencia->id_recurso_tipo != Recurso::TIPO_PAGINA &&
                                    $dependencia->id_recurso_tipo != Recurso::TIPO_POPUP) &&
                                    AclFactory::checaPermissao($acl, $usuario, $dependencia)) {
                                $recurso->addDependencia($dependencia);
                            }
                        }
                    }
                }

                if ($botao->hasImage() && ($botao->hasUrl() || $botao->hasDomId())) {
                    $retorno[] = $botao;
                }
            }
        }

        // ao final de todas as verificacoes, retorna a array de recursos
        return $retorno;
    }

    /**
     * Pega a array de botoes, e monta os menus da pagina de acordo com os parametros
     * extras passados à função
     * 
     * @param array $itens
     * @param array $extras 
     */
    public static function montaMenus($itens, $extras = array()) {
        $class = 'botao48';
        $content = '';
        $tmpl = '<a href={url} id="{id}"{extra}><img class="{class}" title="{title}" src="{img}" /></a>';

        // Percorre a array de extras definindo algumas variaveis
        if (is_array($extras)) {
            $class = $extras['class'] ? $extras['class'] : 'botao48';
            $header = $extras['header'] ? $extras['header'] : '';
            $footer = $extras['footer'] ? $extras['footer'] : '';
            $tmpl = $extras['tmpl'] ? $extras['tmpl'] : $tmpl;
        }

        $content = $header;

        // Percorre a array de botões
        foreach ($itens as $item) {
            if ($item->id_recurso_tipo == Recurso::TIPO_POPUP) {
                $content .= str_replace(array('{title}', '{url}', '{img}', '{id}', '{class}', '{extra}'), array($item->nome,
                    '#',
                    $item->img,
                    $item->getDomId(),
                    $class,
                    " onclick='popUp_anexo({$item->getLink()});'"), $tmpl);
            } else if ($item->id_recurso_tipo == Recurso::TIPO_PRINT) {
                $content .= str_replace(array('{title}', '{url}', '{img}', '{id}', '{class}', '{extra}'), array($item->nome,
                    '#',
                    $item->img,
                    $item->getDomId(),
                    $class,
                    " onclick='print_page();'"), $tmpl);
            } else {
                $content .= str_replace(array('{title}', '{url}', '{img}', '{id}', '{class}', '{extra}'), array($item->nome,
                    $item->getLink(),
                    $item->img,
                    $item->getDomId(),
                    $class,
                    ''), $tmpl);
            }
        }

        $content .= $footer;

        print $content;
        return true;
    }

    public static function mostraAbas($abas) {
        $template_handlers = <<<EOH
            <li><a id="<%DOM_ID%>" title="" href="#<%ID%>"><%NOME%></a></li>
EOH;

        $template_tabs = <<<EOT
        <div id="<%ID%>">
            <%INCLUDE%>
        </div>
EOT;

        $handlers = "";
        $tabs = "";

        if (is_array($abas) && count($abas) > 0) {
            foreach ($abas as $aba) {
                $handlers .= str_replace(array("<%DOM_ID%>", "<%ID%>", "<%NOME%>"), array($aba->dom_id, $aba->id, $aba->nome), $template_handlers);

                ob_start();
                $controlador = Controlador::getInstance();
                $controlador->includeResource( $aba->url );
//                include __BASE_PATH__ . '/interfaces/' . $aba->url;
                $tpl_include = ob_get_contents();
                ob_end_clean();

                $tabs .= str_replace(array("<%ID%>", "<%INCLUDE%>"), array($aba->id,
                    $tpl_include), $template_tabs);
            }
        }

        print <<<EOD
        <ul>
            $handlers
        </ul>
        $tabs
EOD;
        return true;
    }

    /**
     * Corrige string utilizado quando a saida tiver que se to tipo utf8
     */
    public static function fixErrorString($string) {
        return trim($string);
    }

    /**
     *  Adicina zeros a esquerda
     */
    public static function zeroFill($number, $size) {
        return (string) str_pad($number, $size, "0", STR_PAD_LEFT);
    }

    /**
     * Converte data no padra dd/mm/aaaa em aaaa-mm-dd ou vice-versa
     */
    public static function formatDate($Data/* dd/mm/aaaa */) {
        if (strstr($Data, "/")) {//verifica se tem a barra /
            $d = explode("/", $Data); //tira a barra
            $rstData = "$d[2]-$d[1]-$d[0]"; //separa as datas $d[2] = ano $d[1] = mes etc...
            return $rstData;
        } else if (strstr($Data, "-")) {
            $d = explode("-", $Data);
            $rstData = $d[2] . "/" . $d[1] . "/" . $d[0];
            return $rstData;
        } else {
            return NULL; //Para Debugger usar 2009-01-01
        }
    }

    /**
     * 
     */
    public static function convertArrayToComboSplitByCylinder($array) {
        $string = '';
        if (count($array) > 0) {
            foreach ($array as $key => $value) {
                $string .= "{$value['value']}|{$value['id']}\n";
            }
        }
        return $string;
    }

    /**
     * 
     */
    public static function autoLoadJavascripts($paths) {
        $scripts = '';
        foreach ($paths as $path) {
            $path = $path . '?' . filemtime($path);
            $scripts .= "<script type='text/javascript' src='/{$path}'></script>\n";
        }
        return $scripts;
    }

    /**
     * 
     */
    public static function generateMD5ContentsStatics($files) {
        $ret = '';
        foreach ($files as $filename) {
            if ($filename != '.' && $filename != '..') {
                if (filemtime($folder . $filename) === false)
                    return false;
                $ret.=date("YmdHis", filemtime($folder . $filename)) . $filename;
            }
        }
        return md5($ret);
    }

    /**
     * 
     */
    public static function generateCacheContentsJsStatics($files, $cache) {
        /**
         * JSMin
         */
        require_once 'bibliotecas/jsminphp/JSMin.php';

        $content = '';

        foreach ($files as $path) {
            $content .= file_get_contents($path);
        }

        file_put_contents($cache, JSMin::minify($content));
    }

    /**
     * 
     */
    public static function generateCacheContentsCssStatics($files, $cache) {
        /**
         * CssMin
         */
        require_once 'bibliotecas/cssminphp/CSSMin.php';

        $content = '';

        foreach ($files as $path) {
            $content .= file_get_contents($path);
        }

        file_put_contents($cache, CssMin::minify($content));
    }

    /**
     * 
     */
    public static function renovateCacheContentStatics($files, $cache) {
        foreach ($files as $filename) {
            if (filemtime($filename) > @filemtime($cache)) {
                return true;
            }
        }
        return false;
    }

    /**
     * 
     */
    public static function autoLoadCss($paths) {
        $scripts = '';
        foreach ($paths as $path) {
            $path = $path . '?' . filemtime($path);
            $scripts .= "<link href='/{$path}' rel='stylesheet' type='text/css'>\n";
        }
        return $scripts;
    }

    /**
     * 
     */
    public static function headerVerificadorCaixas() {

        $out = array();
        $out['documentos']['caixa_entrada'] = 0;
        $out['processos']['caixa_entrada'] = 0;
        $out['documentos']['area_trabalho'] = 0;
        $out['processos']['area_trabalho'] = 0;
        $out['documentos']['caixa_saida'] = 0;
        $out['processos']['caixa_saida'] = 0;
        $out['documentos']['caixa_externos'] = 0;
        $out['processos']['caixa_externos'] = 0;

        try {

            $user = Controlador::getInstance()->usuario;

            

            /**
             * ID Unidade Usuario Logado...
             */
            $diretoria = $user->ID_UNIDADE;

            /**
             * ID Usuario Logado...
             */
            $usuario = $user->ID;

            /**
             * Documentos Externos
             */
            $stmt = Controlador::getInstance()->getConnection()->connection->prepare("SELECT COUNT(DC.ID) AS TOTAL FROM TB_DOCUMENTOS_CADASTRO DC
                                        LEFT JOIN TB_PROCESSOS_DOCUMENTOS PXD ON PXD.ID_DOCUMENTOS_CADASTRO = DC.ID
                                        LEFT JOIN TB_DOCUMENTOS_VINCULACAO V ON (V.ID_DOCUMENTO_FILHO = DC.ID AND V.FG_ATIVO = 1 AND V.ST_ATIVO = 1)
                                    WHERE 
                                        PXD.ID_DOCUMENTOS_CADASTRO IS NULL AND V.ID_DOCUMENTO_FILHO IS NULL AND EXTERNO IS NOT NULL");
            $stmt->execute();
            $tmp = $stmt->fetch(PDO::FETCH_ASSOC);
            $out['documentos']['caixa_externos'] = $tmp['TOTAL'];

            /**
             * Processos Externos
             */
            $stmt = Controlador::getInstance()->getConnection()->connection->prepare("SELECT COUNT(PC.ID) AS TOTAL FROM TB_PROCESSOS_CADASTRO PC
                                        LEFT JOIN TB_PROCESSOS_VINCULACAO V ON (V.ID_PROCESSO_FILHO = PC.ID AND V.FG_ATIVO = 1)
                                    WHERE 
                                        V.ID_PROCESSO_FILHO IS NULL AND EXTERNO IS NOT NULL");
            $stmt->execute();
            $tmp = $stmt->fetch(PDO::FETCH_ASSOC);
            $out['processos']['caixa_externos'] = $tmp['TOTAL'];

            /**
             * Documentos Caixa Entrada
             */
            $stmt = Controlador::getInstance()->getConnection()->connection->prepare("SELECT COUNT(DC.ID) AS TOTAL FROM TB_DOCUMENTOS_CADASTRO DC
                                        LEFT JOIN TB_PROCESSOS_DOCUMENTOS PXD ON PXD.ID_DOCUMENTOS_CADASTRO = DC.ID
                                        LEFT JOIN TB_DOCUMENTOS_VINCULACAO V ON (V.ID_DOCUMENTO_FILHO = DC.ID AND V.FG_ATIVO = 1 AND V.ST_ATIVO = 1)
                                    WHERE 
                                        PXD.ID_DOCUMENTOS_CADASTRO IS NULL AND V.ID_DOCUMENTO_FILHO IS NULL AND ID_UNID_CAIXA_ENTRADA = ?");
            $stmt->bindParam(1, $diretoria, PDO::PARAM_INT);
            $stmt->execute();
            $tmp = $stmt->fetch(PDO::FETCH_ASSOC);
            $out['documentos']['caixa_entrada'] = $tmp['TOTAL'];

            /**
             * Processos Caixa Entrada
             */
            $stmt = Controlador::getInstance()->getConnection()->connection->prepare("SELECT COUNT(PC.ID) AS TOTAL FROM TB_PROCESSOS_CADASTRO PC
                                        LEFT JOIN TB_PROCESSOS_VINCULACAO V ON (V.ID_PROCESSO_FILHO = PC.ID AND V.FG_ATIVO = 1)
                                    WHERE 
                                        V.ID_PROCESSO_FILHO IS NULL AND ID_UNID_CAIXA_ENTRADA = ?");
            $stmt->bindParam(1, $diretoria, PDO::PARAM_INT);
            $stmt->execute();
            $tmp = $stmt->fetch(PDO::FETCH_ASSOC);
            $out['processos']['caixa_entrada'] = $tmp['TOTAL'];

            /**
             * Documentos Area Trabalho
             */
            $stmt = Controlador::getInstance()->getConnection()->connection->prepare("SELECT COUNT(DC.ID) AS TOTAL FROM TB_DOCUMENTOS_CADASTRO DC
                                        LEFT JOIN TB_PROCESSOS_DOCUMENTOS PXD ON PXD.ID_DOCUMENTOS_CADASTRO = DC.ID
                                        LEFT JOIN TB_DOCUMENTOS_VINCULACAO V ON (V.ID_DOCUMENTO_FILHO = DC.ID AND V.FG_ATIVO = 1 AND V.ST_ATIVO = 1)
                                    WHERE 
                                        PXD.ID_DOCUMENTOS_CADASTRO IS NULL AND V.ID_DOCUMENTO_FILHO IS NULL AND ID_UNID_AREA_TRABALHO = ?");
            $stmt->bindParam(1, $diretoria, PDO::PARAM_INT);
            $stmt->execute();
            $tmp = $stmt->fetch(PDO::FETCH_ASSOC);
            $out['documentos']['area_trabalho'] = $tmp['TOTAL'];

            /**
             * Processos Area Trabalho
             */
            $stmt = Controlador::getInstance()->getConnection()->connection->prepare("SELECT COUNT(PC.ID) AS TOTAL FROM TB_PROCESSOS_CADASTRO PC
                                        LEFT JOIN TB_PROCESSOS_VINCULACAO V ON (V.ID_PROCESSO_FILHO = PC.ID AND V.FG_ATIVO = 1)
                                    WHERE 
                                        V.ID_PROCESSO_FILHO IS NULL AND ID_UNID_AREA_TRABALHO = ?");
            $stmt->bindParam(1, $diretoria, PDO::PARAM_INT);
            $stmt->execute();
            $tmp = $stmt->fetch(PDO::FETCH_ASSOC);
            $out['processos']['area_trabalho'] = $tmp['TOTAL'];

            /**
             * Documentos Caixa Saida
             */
            $stmt = Controlador::getInstance()->getConnection()->connection->prepare("SELECT COUNT(DC.ID) AS TOTAL FROM TB_DOCUMENTOS_CADASTRO DC
                                        LEFT JOIN TB_PROCESSOS_DOCUMENTOS PXD ON PXD.ID_DOCUMENTOS_CADASTRO = DC.ID
                                        LEFT JOIN TB_DOCUMENTOS_VINCULACAO V ON (V.ID_DOCUMENTO_FILHO = DC.ID AND V.FG_ATIVO = 1 AND V.ST_ATIVO = 1)
                                    WHERE 
                                        PXD.ID_DOCUMENTOS_CADASTRO IS NULL AND V.ID_DOCUMENTO_FILHO IS NULL AND ID_UNID_CAIXA_SAIDA = ?");
            $stmt->bindParam(1, $diretoria, PDO::PARAM_INT);
            $stmt->execute();
            $tmp = $stmt->fetch(PDO::FETCH_ASSOC);
            $out['documentos']['caixa_saida'] = $tmp['TOTAL'];

            /**
             * Processos Caixa Saida
             */
            $stmt = Controlador::getInstance()->getConnection()->connection->prepare("SELECT COUNT(PC.ID) AS TOTAL FROM TB_PROCESSOS_CADASTRO PC
                                        LEFT JOIN TB_PROCESSOS_VINCULACAO V ON (V.ID_PROCESSO_FILHO = PC.ID AND V.FG_ATIVO = 1)
                                    WHERE 
                                        V.ID_PROCESSO_FILHO IS NULL AND ID_UNID_CAIXA_SAIDA = ?");
            $stmt->bindParam(1, $diretoria, PDO::PARAM_INT);
            $stmt->execute();
            $tmp = $stmt->fetch(PDO::FETCH_ASSOC);
            $out['processos']['caixa_saida'] = $tmp['TOTAL'];

            /**
             * Prazos
             */
            $stmt = Controlador::getInstance()->getConnection()->connection->prepare("SELECT COUNT(SQ_PRAZO) AS TOTAL FROM TB_CONTROLE_PRAZOS WHERE ((ID_UNID_DESTINO = ? AND ID_USUARIO_DESTINO IS NULL) OR (ID_USUARIO_DESTINO = ?)) AND FG_STATUS = 'AR'");
            $stmt->bindParam(1, $diretoria, PDO::PARAM_INT);
            $stmt->bindParam(2, $usuario, PDO::PARAM_INT);
            $stmt->execute();
            $tmp = $stmt->fetch(PDO::FETCH_ASSOC);
            $out['prazos'] = $tmp['TOTAL'];
        } catch (PDOException $e) {
            new BasePDOException($e);
        }

        /**
         * Somar o total de documentos e processos de cada caixa...
         */
        $out['caixa_entrada'] = ($out['documentos']['caixa_entrada'] + $out['processos']['caixa_entrada']);
        $out['area_trabalho'] = ($out['documentos']['area_trabalho'] + $out['processos']['area_trabalho']);
        $out['caixa_saida'] = ($out['documentos']['caixa_saida'] + $out['processos']['caixa_saida']);
        $out['caixa_externos'] = ($out['documentos']['caixa_externos'] + $out['processos']['caixa_externos']);
        return $out;
    }

    /**
     * Converter array Latin para UTF8
     */
    public static function convertArrayToUTF8($array) {
        return $array;
    }

    /**
     * Salva arquivo para apresentar o tempo de processamento das imagens na tela.
     * "../public/contador/contador_".Zend_Auth::getInstance()->getIdentity()->ID.".log";
     * 
     * @param string $value
     * @param string $arquivo;
     * @throws Exception 
     */
    public static function contador($value, $arquivo) {
        try {

            $dir = substr($arquivo, 0, strrpos($arquivo, '/') + 1);
            if (!is_dir($dir)) {
                mkdir($dir, 0777);
            }

            $f = fopen($arquivo, "w+");
            chmod($arquivo, 0755);
            fwrite($f, $value);
            fclose($f);
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode(), $e->getPrevious());
        }
    }

    /**
     * @deprecated
     * Essa função é usada no login, mas pode ficar aqui.
     * @todo verificar isso quando o autoload funcionar para todos.
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
     * @return string
     */
    public static function gerarRaiz($digital, $diretorio) {

        $raiz = 'LOTE' . floor($digital / 10000);

        if (is_dir($diretorio . "/" . $raiz)) {
            return $raiz;
        } else {
            mkdir($diretorio . "/" . $raiz, 0777);
            return $raiz;
        }
    }

    /**
     * @return string
     */
    public static function limparString($string) {
        return $string;
    }

    public static function converteMesBr($mes) {
        $mes = (integer) $mes;
        $meses = array();
        $meses[1] = 'Janeiro';
        $meses[2] = 'Fevereiro';
        $meses[3] = 'Mar&ccedil;o';
        $meses[4] = 'Abril';
        $meses[5] = 'Maio';
        $meses[6] = 'Junho';
        $meses[7] = 'Julho';
        $meses[8] = 'Agosto';
        $meses[9] = 'Setembro';
        $meses[10] = 'Outubro';
        $meses[11] = 'Novembro';
        $meses[12] = 'Dezembro';

        return $meses[$mes];
    }

    /**
     * Recupera id do processo
     * @param <string> $processo
     * @return <int> retorna id do processo
     */
    public static function RecuperaIdProcesso($processo) {
        
        
        $stmt = Controlador::getInstance()->getConnection()->connection->prepare("SELECT ID FROM TB_PROCESSOS_CADASTRO WHERE NUMERO_PROCESSO = ? LIMIT 1");
        $stmt->bindParam(1, $processo, PDO::PARAM_STR);
        $stmt->execute();
        $resul = $stmt->fetch(PDO::FETCH_ASSOC);
        return $resul['ID'];
    }

}