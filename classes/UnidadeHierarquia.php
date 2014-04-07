<?php

/**
 * Description of UnidadeHierarquia
 *
 * @author 97714267100
 */
class UnidadeHierarquia
{
    private $_hierarquia = array();

    public function __construct($id = null)
    {
        if(isset($id)) {
            $this->_hierarquia[] = $this->getHierarquiaUnidade($id);
        }
    }
    /**
     *Começa aqui a função recursiva
     * @param type $id
     * @return type 
     */
    public function getHierarquiaUnidade($id)
    {
        $array = array();
        $rs = DaoUnidade::getUnidade($id);

        $array['id'] = $id;
        $array['nome'] = $rs['nome'];
        $array['sigla'] = $rs['sigla'];
        $array['uf'] = $rs['uf'];

        if($rs['id'] > 0) {
            $superior = (integer) $rs['superior'];
            if($superior > 0) {
                $this->_hierarquia[] = $this->getHierarquiaUnidade($superior);
            }
        }
        return $array;
    }
    public function getHierarquiaDecrescente()
    {
        $string = '';
        ksort($this->_hierarquia);
        foreach($this->_hierarquia as $value) {
            $string .= $value['nome'] . "<br />";
        }
        return $string;
    }
    public function getHierarquiaCrescente()
    {
        $string = '';
        $i = 1;
        krsort($this->_hierarquia);
        foreach($this->_hierarquia as $value) {
            $string .= $value['sigla'] . ((count($this->_hierarquia) == $i)?'':'/');
            $i++;
        }
        return $string;
    }
}