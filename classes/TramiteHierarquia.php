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
 * @author Carlos Eduardo <carlos.eduardo-santos@icmbio.gov.br>
 */
class TramiteHierarquia
{

    private $_hierarquia;
    private $_selected = NULL;

    /**
     *
     * @param type $idUnidade
     * @return type
     */
    public function __construct ($idUnidade)
    {
        $this->_selected = $idUnidade;

        $this->_hierarquia = array();
        $this->pai($idUnidade, $idUnidade);

        $base = array_pop($this->_hierarquia);
        $filhos = $this->filho($idUnidade, $idUnidade);
        $base['filhos'] = $filhos;
        $this->_hierarquia[] = $base;

        return $this->_hierarquia;
    }

    /**
     *
     * @param type $idUnidade
     * @return type
     */
    public function pai ($idUnidade, $idUnidadeDefault)
    {
        $unidade = DaoUnidade::getUnidade($idUnidade);
        //checa se já existe tramite.
        $unidade['checked'] = $this->verificaTramite($idUnidadeDefault, $unidade['id']);
        array_unshift($this->_hierarquia, $unidade);

        if ($unidade['superior'] > 0 && $unidade['superior'] != $idUnidade) {
            return $this->pai($unidade['superior'], $idUnidadeDefault);
        }
        return $this->_hierarquia;
    }

    /**
     *
     * @param type $idUnidade
     * @return type
     */
    public function filho ($idUnidade, $idUnidadeDefault)
    {
        $where = array('SUPERIOR' => $idUnidade);
        $unidades = DaoUnidade::listUnidades($where);
        $aux = array();
        if ($unidades->resultado == true) {
            foreach ($unidades->resultado as $dados) {
                $children = array_change_key_case($dados, CASE_LOWER);
                //checa se já existe tramite.
                $children['checked'] = $this->verificaTramite($idUnidadeDefault, $children['id']);
                //add os filhos.
                $children['filhos'] = $this->filho($children['id'], $idUnidadeDefault);
                $aux[] = $children;
            }
        }
        return $aux;
    }

    /**
     * 
     */
    public function make ()
    {

        $html = "<div>";

        if (count($this->_hierarquia) > 0) {
            $ul = '';
            for ($i = 0; $i < count($this->_hierarquia); $i++) {

                $html .= "<ul><li>";
                $html .= $this->inputCheckboxTreeTramite($this->_hierarquia[$i]['id'], $this->_hierarquia[$i]['checked']);
                $html .= "{$this->_hierarquia[$i]['nome']}";

                if (isset($this->_hierarquia[$i]['filhos'])) {
                    $html .= $this->makeFilhos($this->_hierarquia[$i]['filhos']);
                }

                $ul .= '</li></ul>';
            }
            $html .= $ul;
        }
        $html .= "</div>";
        return $html;
    }

    /**
     * 
     */
    public function makeFilhos ($filhos)
    {
        $html = '';
        if (count($filhos) > 0) {
            foreach ($filhos as $filho) {

                $html .= "<ul><li>";

                $html .= $this->inputCheckboxTreeTramite($filho['id'], $filho['checked']);
                $html .= "{$filho['nome']}";

                if (isset($filho['filhos'])) {
                    $html .= $this->makeFilhos($filho['filhos']);
                }
                $html .= '</li></ul>';
            }
        }
        return $html;
    }

    /**
     * 
     */
    public function inputCheckboxTreeTramite ($id, $checked = false)
    {
        if ($checked == true) {
            $checked = 'checked="checked"';
        }

        #BugFix
        if ($this->_selected != $id) {
            $html = "<input class='checkboxTreeTramite' {$checked} type='checkbox' value='{$id}' name='checkboxTreeTramite' />";
        } else {
            $html = "<input type='checkbox' disabled title='Não é possível tramitar para a própria unidade!' />";
        }

        return $html;
    }

    /**
     * 
     */
    public function verificaTramite ($idUnidade, $idReferencia)
    {
        //verifico se está selecionado via OO.
        $where = array();
        $where['ID_REFERENCIA'] = $idReferencia;
        $where['ID_UNIDADE'] = $idUnidade;
        $rsTramite = DaoTramite::getTramite($where);
        $success = false;
        if ($rsTramite->resultado == true) {
            $success = true;
        }
        return $success;
    }

}