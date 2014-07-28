<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of DaoDocumentoSerpro
 *
 * @author 91011876191
 */
class DaoDocumentoSerpro extends DaoDocumento
{
    public static function getDocumento($documento = false, $field = false) {
        $doc = parent::getDocumento($documento, $field);
        
        if($doc){
            $arrSerpro = CFModelDocumentoSerpro::factory()->findByParam(array('DIGITAL' => $doc['digital']));
            /* Converter datas */
            $doc['dt_entrada']    = Util::formatDate($doc['dt_entrada']);
            $doc['dt_documento']  = Util::formatDate($doc['dt_documento']);
            $doc['dt_cadastro']   = Util::formatDate($doc['dt_cadastro']);
            $doc['dt_prazo']      = Util::formatDate($doc['dt_prazo']);
            $doc['fg_prazo']      = ($doc['fg_prazo'] > 0) ? true : false;
            $doc['assunto']       = DaoAssuntoDocumento::getAssunto($doc['id_assunto'], 'assunto');
            $doc['serpro']        = $arrSerpro[0]->SERPRO;
        }
    
    return $doc;
     
    }
    
}
                
                