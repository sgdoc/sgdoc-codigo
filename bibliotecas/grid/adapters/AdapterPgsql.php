<?php

namespace grid\adapters;

use grid\GridAbstract,
    grid\Grideable
;

/**
 * @author Michael F. Rodrigues
 * @version 0.0.0
 */
class AdapterPgsql extends \CFModelAbstract implements Grideable {

    /**
     * @var string
     */
    protected $_primary = '';

    /**
     * @var string
     */
    protected $_table = '';

    /**
     * Monta SQL de consulta por todos os campos envolvidos, utilizando sempre
     * que possível Full Text Search (FTS), senão utiliza ILIKE
     * Adicionado CAST para busca com ILIKE em campos que não são originalmente
     * texto(sequencial, inteiro)
     * ATENÇÃO: Problema de perfomance por considerar busca com máscara em
     * ambos os lados.
     * @return AdapterPgsql
     * @param GridAbstract $grid
     */
    public function filtering(GridAbstract $grid) {
        $strSQLPattern = "SELECT ";
        $arrSQLColumns = array();
        $arrSQLFTSCompare = array();
        $arrSQLFTSPart = array();

        $strSearchWS = \StringUtil::escapeFromGenericSourceCopy( $grid->getParams('sSearch') );
//        die(var_dump($strSearchWS).'aqui');

        //Insere & para consultas FTS
        $strCompare = implode(' & ', explode(' ', $strSearchWS ) );        

        $columnIndex = 0;
        //Estabelece SQL Padrão
        foreach ($grid->getColumns() as $column) {
            foreach ($column as $key => $value) {
                
                $strColumn = sprintf( "%s AS %s ", $key, $value );

                //Se é a primeira coluna, acrescenta DISTINCT
                if(!$columnIndex){
                    $strColumn = ' DISTINCT '.$strColumn;
                }
                $arrSQLColumns[] = $strColumn;

                if(strtoupper($value) != 'ID'){
                    if($strSearchWS != ''){
                        //Se a coluna é listada como "Pesquisável"
                        if ($grid->getParams('bSearchable_' . $columnIndex) == 'true') {
                            $boolWithAlias = false;
                            if ($grid->getColumns($columnIndex, $boolWithAlias) != 'NULL') {                        
                                //Realiza FTS somente em colunas pesquisáveis e listadas para FTS
                                if( in_array( strtoupper($value), $grid->getColumnsFTS() ) ){
                                    $arrSQLFTSCompare[] = sprintf( "to_tsvector_sgdoc( CAST(%s AS TEXT) ) @@ to_tsquery_sgdoc( '\"%s\"' )", $key, $strCompare);
                                }else{//Se não está listada para FTS, pesquisa por ILIKE
                                    $arrSQLFTSCompare[] = sprintf("CAST(%s AS TEXT) ILIKE '%s%s%s'", $key, '%', $strSearchWS, '%');
                                }
                            }// if ($grid->getColumns($columnIndex, $boolWithAlias) != 'NULL')
                        }//if ($grid->getParams('bSearchable_' . $columnIndex) == 'true')
                    }//if($strSearchWS != '')   
                }//if(strtoupper($value) != 'ID')
            }//foreach
            $columnIndex++;
        }

        $strSQLPattern .= implode(', ', $arrSQLColumns); 
        $strSQLPattern .= sprintf("FROM %s WHERE %s ", $grid->getQuery(), ($grid->getExtraQuery()? $grid->getExtraQuery() : '1=1' ) ) ;
        
        if($strSearchWS != ''){            
            foreach ($arrSQLFTSCompare as $columnFTSCompare) {
                $arrSQLFTSPart[] = $strSQLPattern . " AND " . $columnFTSCompare;
            }
        }else{
            $arrSQLFTSPart[] = $strSQLPattern;
        }
        
        $strSQL = sprintf("SELECT *, COUNT(*) OVER() AS QTD FROM ( %s ) q %s %s %s " , 
                implode(" union ", $arrSQLFTSPart), 
                $grid->getGroup(), 
                $grid->getOrder(), 
                $grid->getLimit() );
//        die($strSQL);
        $grid->setWhere( $strSQL );
    }
    


    /**
     * @return AdapterPgsql
     * @param GridAbstract $grid
     */
    public function result(GridAbstract $grid) 
    {
        $stmt = $this->_conn->prepare( $grid->getWhere() );
        $stmt->execute();
        $objFecthed = $stmt->fetchAll(\PDO::FETCH_OBJ);
        $total = is_array($objFecthed)? $objFecthed[0]->QTD : 0;
        $grid->setTotal( $total );
        $grid->setFilteredTotal( $total );
        $grid->setResult( $objFecthed );

        return $this;
    }

    /**
     * @return AdapterPgsql
     * @param GridAbstract $grid
     */
    public function totalRecords(GridAbstract $grid) 
    {
        return $this;
    }

    /**
     * @return AdapterPgsql
     * @param GridAbstract $grid
     */
    public function totalDisplayRecords(GridAbstract $grid) 
    {
        return $this;
    }

}