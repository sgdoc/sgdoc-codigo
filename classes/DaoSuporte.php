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
 * Description of DaoSuporte
 *
  * @author Michael F. Rodrigues <cerberosnash@gmail.com>
 */

class DaoSuporte
{

    /**
     * 
     */
    public static function getDemanda ($demanda = false, $campo = false)
    {
        try {
            
            

            $campo = $campo ? $campo : '*';
            $condicao = filter_var($demanda, FILTER_VALIDATE_INT) ? 'ID' : 'CD_PROTOCOLO';

            $stmt = Controlador::getInstance()->getConnection()->connection->prepare("SELECT $campo FROM VW_ATENDIMENTO WHERE $condicao = ? LIMIT 1");
            $stmt->bindParam(1, $demanda, PDO::PARAM_STR);
            $stmt->execute();
            $out = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!empty($out)) {
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
}