<?php

/*
 * Copyright 2008 ICMBio
 * Este arquivo é parte do programa SISICMBio
 * O SISICMBio é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos
 * da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão
 * 2 da Licença.
 *
 * Este programa é distribuído na esperança que possa ser útil, mas SEM NENHUMA GARANTIA; sem
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
class Arvore extends Base
{

    private $root;

    /**
     * 
     */
    public function checkVariable ($string)
    {
        return str_replace(array('&', '"', "'", '<', '>'), array('&amp;', '&quot;', '&apos;', '&lt;', '&gt;'), $string);
    }

    /**
     * 
     */
    public function getVinculosDocumento ($digital, $pageName)
    {
        try {
            $sttm = Controlador::getInstance()->getConnection()->connection->prepare("SELECT DCP.DIGITAL AS PAI, DCF.DIGITAL AS FILHO FROM TB_DOCUMENTOS_VINCULACAO DV
INNER JOIN TB_DOCUMENTOS_CADASTRO DCP ON DCP.ID = DV.ID_DOCUMENTO_PAI
INNER JOIN TB_DOCUMENTOS_CADASTRO DCF ON DCF.ID = DV.ID_DOCUMENTO_FILHO
WHERE DCP.DIGITAL = ? AND DV.FG_ATIVO = 1 AND ST_ATIVO = 1");
            $sttm->bindParam(1, $digital, PDO::PARAM_STR);
            $sttm->execute();
            $out = $sttm->fetchAll(PDO::FETCH_ASSOC);
            $str = null;

            if (!empty($out)) {
                foreach ($out as $key => $value) {
                    $supp = null;

                    $ausente = (!Documento::validarDocumentoAreaDeTrabalho($value['FILHO'])) ? array('classe' => 'ausente', 'title' => 'Este documento nao esta na sua area de trabalho.', ausente => 'true') : array(classe => '', title => '', ausente => 'false');
                    if (true) {
                        $supp = "<ul class='ajax'>"
                                . "<li id='{$value['FILHO']}'>{url:'{$pageName}?action=getElementList&ownerEl={$value['FILHO']}',idElemento:'{$value['FILHO']}',stAusente:{$ausente['ausente']}}</li>"
                                . "</ul>";
                    }
                    $str .= "<li class='text' title='{$ausente['title']}' id='{$value['FILHO']}'><span class='{$ausente['classe']}'>{$value['FILHO']}</span>{$supp}</li>";
                }
            }
        } catch (PDOException $e) {
            $str = FAILED;
        }
        return $str;
    }

    /**
     * 
     */
    public function getVinculacaoDocumento ($digital, $pageName, $vinculacao/* Anexacao=1 Apensacao=2 */)
    {

        try {
            $sttm = Controlador::getInstance()->getConnection()->connection->prepare("SELECT DCP.DIGITAL AS PAI, DCF.DIGITAL AS FILHO FROM TB_DOCUMENTOS_VINCULACAO DV
INNER JOIN TB_DOCUMENTOS_CADASTRO DCP ON DCP.ID = DV.ID_DOCUMENTO_PAI
INNER JOIN TB_DOCUMENTOS_CADASTRO DCF ON DCF.ID = DV.ID_DOCUMENTO_FILHO
WHERE DCP.DIGITAL = ? AND DV.FG_ATIVO = 1 AND ST_ATIVO = 1 AND ID_VINCULACAO = ?");
            $sttm->bindParam(1, $digital, PDO::PARAM_STR);
            $sttm->bindParam(2, $vinculacao, PDO::PARAM_INT);
            $sttm->execute();
            $out = $sttm->fetchAll(PDO::FETCH_ASSOC);
            $str = null;

            if (!empty($out)) {
                foreach ($out as $key => $value) {
                    $supp = null;

                    $ausente = (!Documento::validarDocumentoAreaDeTrabalho($value['FILHO'])) ? array('classe' => 'ausente', 'title' => 'Este documento nao esta na sua area de trabalho.', 'ausente' => 'true') : array('classe' => '', 'title' => '', 'ausente' => 'false');
                    if (true) {
                        $supp = "
                        		<ul class='ajax'>
                        			<li id='{$value['FILHO']}'><span class='{$ausente['classe']}'>
                        				{url:'{$pageName}?action=getElementList&ownerEl={$value['FILHO']}',idElemento:'{$value['FILHO']}',stAusente:{$ausente['ausente']}}</span>
                        			</li>
                        		</ul>
                        		";
                    }				
                    $documento = current(CFModelDocumento::factory()->findByParam(array('DIGITAL' => $value['FILHO'])));
                    $data = Util::formatDate($documento->DT_DOCUMENTO) ? Util::formatDate($documento->DT_DOCUMENTO) : "Data Não informada";
                    $str .= "
                    		<li class='text' title='{$ausente['title']}' id='{$value['FILHO']}'>
                    			<span class='{$ausente['classe']}'>{$value['FILHO']}</span>
                    			[ {$documento->ASSUNTO} - {$data} ]                
                    			{$supp}
                    		</li>
                    		";
                }
            }
        } catch (PDOException $e) {
            $str = FAILED;
        }

        return $str;
    }

    /**
     * 
     */
    public function getPecasProcesso ($numero_processo, $pageName)
    {

        try {
            $sttm = Controlador::getInstance()->getConnection()->connection->prepare("SELECT DC.DIGITAL AS FILHO 
                FROM TB_PROCESSOS_DOCUMENTOS PXD
                    INNER JOIN TB_DOCUMENTOS_CADASTRO DC ON DC.ID = PXD.ID_DOCUMENTOS_CADASTRO
                    INNER JOIN TB_PROCESSOS_CADASTRO PC ON PC.ID = PXD.ID_PROCESSOS_CADASTRO
                WHERE PC.NUMERO_PROCESSO = ? ORDER BY PXD.ID");
            
            $sttm->bindParam(1, $numero_processo, PDO::PARAM_STR);
            $sttm->execute();
            $out = $sttm->fetchAll(PDO::FETCH_ASSOC);
            $str = null;

            if (!empty($out)) {
                foreach ($out as $key => $value) {
                    $supp = null;

                    $ausente = (!Documento::validarDocumentoAreaDeTrabalho($value['FILHO'])) ? array('classe' => 'ausente', 'title' => 'Este processo nao esta na sua area de trabalho.', 'ausente' => 'true') : array('classe' => '', 'title' => '', 'ausente' => 'false');
                    $idElemento = str_replace(array('.', '/', '-'), array('', '', ''), $value['FILHO']);
                    if (true) {
                        $supp = "<ul class='ajax'>"
                                . "<li id='$idElemento'>{url:'{$pageName}?action=getElementList&ownerEl={$value['FILHO']}',idElemento:'{$idElemento}',stAusente:{$ausente['ausente']}}</li>"
                                . "</ul>";
                    }
                    $str .= "<li class='text' title='{$ausente['title']}' id='{$idElemento}'>A<span class='{$ausente['classe']}'>{$value['FILHO']}</span>{$supp}</li>";
                }
            }
        } catch (PDOException $e) {
            $str = FAILED;
        }

        return $str;
    }

    /**
     * 
     */
    public function getVinculacaoProcesso ($numero_processo, $pageName, $vinculacao/* Anexacao=1 Apensacao=2 */)
    {

        try {
            $sttm = Controlador::getInstance()->getConnection()->connection->prepare("SELECT PCP.NUMERO_PROCESSO AS PAI, PCF.NUMERO_PROCESSO AS FILHO
                    FROM TB_PROCESSOS_VINCULACAO PV
                        INNER JOIN TB_PROCESSOS_CADASTRO PCP ON PCP.ID = PV.ID_PROCESSO_PAI
                        INNER JOIN TB_PROCESSOS_CADASTRO PCF ON PCF.ID = PV.ID_PROCESSO_FILHO
                    WHERE PCP.NUMERO_PROCESSO = ? AND PV.FG_ATIVO = 1 AND PV.ST_ATIVO = 1 AND ID_VINCULACAO = ?");
            $sttm->bindParam(1, $numero_processo, PDO::PARAM_STR);
            $sttm->bindParam(2, $vinculacao, PDO::PARAM_INT);
            $sttm->execute();
            $out = $sttm->fetchAll(PDO::FETCH_ASSOC);
            $str = null;

            if (!empty($out)) {
                foreach ($out as $key => $value) {
                    $supp = null;

                    $ausente = (!Processo::validarProcessoAreaDeTrabalho($value['FILHO'])) ? array('classe' => 'ausente', 'title' => 'Este processo nao esta na sua area de trabalho.', 'ausente' => 'true') : array('classe' => '', 'title' => '', 'ausente' => 'false');
                    $idElemento = str_replace(array('.', '/', '-'), array('', '', ''), $value['FILHO']);
                    if (true) {
                        $supp = "<ul class='ajax'>"
                                . "<li id='$idElemento'>{url:'{$pageName}?action=getElementList&ownerEl={$value['FILHO']}',idElemento:'{$idElemento}',stAusente:{$ausente['ausente']}}</li>"
                                . "</ul>";
                    }
                    $str .= "<li class='text' title='{$ausente['title']}' id='{$idElemento}'><span class='{$ausente['classe']}'>{$value['FILHO']}</span>{$supp}</li>";
                }
            }
        } catch (PDOException $e) {
            $str = FAILED;
        }

        return $str;
    }

    /**
     * 
     */
    public function setRootId ($root)
    {
        $this->root = $root;
    }

    /**
     * 
     */
    public function getRootId ()
    {
        if ($this->root) {
            return $this->root;
        }
        return 0;
    }

}