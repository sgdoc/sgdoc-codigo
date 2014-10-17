<?php

namespace grid;

use grid\Grideable;

/**
 * @author Michael F. Rodrigues <cerberosnash@gmail.com>
 * @version 0.0.0
 */
abstract class GridAbstract {

    /**
     * @var Grideable
     */
    protected $_adapter = NULL;

    /**
     * @var string
     */
    protected $_primary = '';

    /**
     * @var string
     */
    protected $_table = '';

    /**
     * @var string
     */
    protected $_query = NULL;

    /**
     * @var array
     */
    protected $_params = array();

    /**
     * @var array
     */
    protected $_output = array();

    /**
     * @var array
     */
    protected $_columns = array();

    /**
     * @var array
     */
    protected $_columnsFTS = array();

    /**
     * @var string
     */
    protected $_limit = '';

    /**
     * @var string
     */
    protected $_order = 'order by ';

    /**
     * @var string
     */
    protected $_group = '';

    /**
     * @var string
     */
    protected $_where = '';

    /**
     * @var string
     */
    protected $_extraQuery = '';

    /**
     * @var array
     */
    protected $_result = array();

    /**
     * @var integer
     */
    protected $_total = 0;

    /**
     * @var integer
     */
    protected $_filtered_total = 0;

    /**
     * Constrói adaptador de acordo com o banco adotado
     * 
     * @todo Tornar automática a construção de Adapters
     * 
     * @return void
     * @param string $entry
     */
    protected function __construct($entry = NULL) {

        $driver = 'Pgsql';

        $adapter = "Adapter" . ucfirst($driver);
        $nameSpace = "grid\\adapters\\{$adapter}";

        try {
            $this->_adapter = $nameSpace::factory($entry);
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    /**
     * @return string
     */
    public function getQuery() {
        return $this->_query;
    }

    /**
     * @return string
     * @param string $param
     */
    public function getParams($param) {
        return $this->_params["{$param}"];
    }

    /**
     * @return array
     * @param string $key
     * @param boolean $alias
     */
    public function getColumns($key = NULL, $alias = true) {
        $column = '';
        $columns = array_values($this->_columns);


        if (is_null($key)) {
            return $this->_columns;
        }

        if ($alias) {
            $column = key($columns["{$key}"]);
        } else {
            $column = $columns["{$key}"][key($columns["{$key}"])];
        }

        return $column;
    }

    /**
     * @return string
     */
    public function getLimit() {
        return $this->_limit;
    }

    /**
     * @return string
     */
    public function getOrder() {
        return $this->_order;
    }

    /**
     * @return string
     */
    public function getGroup() {
        return $this->_group;
    }

    /**
     * @return string
     */
    public function getWhere() {
        return $this->_where;
    }

    /**
     * @return GridAbstract
     * @param string $where
     */
    public function setWhere($where) {
        $this->_where = $where;
        return $this;
    }

    /**
     * @return string
     */
    public function getExtraQuery() {
        return $this->_extraQuery;
    }

    /**
     * @return GridAbstract
     * @param string $extQuery
     */
    public function setExtraQuery($extQuery) {
        $this->_extraQuery = $extQuery;
        return $this;
    }
    
    public function setColumnsFTS($columnsFTS = array())
    {
        $this->_columnsFTS = $columnsFTS;
        return $this;
    }

    public function getColumnsFTS()
    {
        return $this->_columnsFTS;
    }

    /**
     * @return GridAbstract
     * @param array $result
     */
    public function setResult($result) {
        $this->_result = $result;
        return $this;
    }

    /**
     * @return array
     */
    public function getResult() {
        return $this->_result;
    }

    /**
     * @return integer
     */
    public function getTotal() {
        return $this->_total;
    }

    /**
     * @return GridAbstract
     * @param integer $total
     */
    public function setTotal($total) {
        $this->_total = $total;
        return $this;
    }

    /**
     * @return GridAbstract
     * @param integer $filtered_total
     */
    public function setFilteredTotal($filtered_total) {
        $this->_filtered_total = $filtered_total;
        return $this;
    }

    /**
     * @return GridAbstract
     * @param string $entry
     */
    public static function factory($entry = NULL) {
        $class = get_called_class();

        return new $class($entry);
    }

    /**
     * @return Grid
     * @param array $columns
     */
    public function columns($columns) {
        $this->_columns = $columns;
        return $this;
    }

    /**
     * @return Grid
     * @param string $query
     */
    public function query($query) {
        $this->_query = $query;
        return $this;
    }

    /**
     * @return Grid
     * @param string $group
     */
    public function group($group = '') {
        if ($group != '') {
            $group = " group by {$group} ";
        }

        $this->_group = $group;
        return $this;
    }

    /**
     * @return Grid
     * @param string $primary
     */
    public function primary($primary) {
        $this->_primary = $primary;
        return $this;
    }

    /**
     * @return Grid
     * @param array $params
     */
    public function params($params) {
        $this->_params = $params;
        return $this;
    }

    /**
     * @return array
     */
    public function output() {
        return $this->_output;
    }

    /**
     * @return void
     */
    protected function _paging() {
        if (isset($this->_params['iDisplayStart']) && $this->_params['iDisplayLength'] != '-1') {
            $this->_limit = sprintf('limit %d offset %d', $this->_params['iDisplayLength'], $this->_params['iDisplayStart']);
        }
    }

    /**
     * @return void
     */
    public function _ordering() {
        if (isset($this->_params['iSortCol_0'])) {
            for ($i = 0; $i < intval($this->_params['iSortingCols']); $i++) {
                if ($this->_params['bSortable_' . intval($this->_params['iSortCol_' . $i])] == "true") {
                    //$columnIndex = $i+1;
                    //$this->_order .= "{$columnIndex} {$this->_params['sSortDir_' . $i]} , ";
					$this->_order .= "{$this->getColumns(intval($this->_params['iSortCol_' . $i]))} {$this->_params['sSortDir_' . $i]} , ";
                }
            }
            //se não houver ordenação, remove a mesma
            if ($this->_order == 'order by ') {
                $this->_order = '';
            }
            //Se não for vazio, remove ultima vírgula
            $this->_order = ($this->_order != '') ? substr_replace($this->_order, '', -2) : '';
        }
    }

    /**
     * @return void
     */
    public function _filtering() {
        $this->_adapter->filtering($this);
    }

    /**
     * @return void
     */
    protected function _result() {
        $this->_adapter->result($this);
    }

    /**
     * @return void
     */
    protected function _totalRecords() {
        $this->_adapter->totalRecords($this);
    }

    /**
     * @return void
     */
    protected function _totalDisplayRecords() {
        $this->_adapter->totalDisplayRecords($this);
    }

    /**
     * @todo Implementar quando necessario...
     */
    protected function _individualFiltering() {
        
    }

    /**
     * return string
     */
    public function mountWhere() {
        $returnWhere = "";
        if ($this->getExtraQuery() && $this->getWhere()) {
            $returnWhere = $this->getExtraQuery() . ' AND ' . $this->getWhere();
        } else if ($this->getExtraQuery()) {
            $returnWhere = $this->getExtraQuery();
        } else if ($this->getWhere()) {
            $returnWhere = $this->getWhere();
        }

        return $returnWhere;
    }

    /**
     * Executa a montagem da grid em todos os passos previstos, como ordenação,
     * filtro, paginação e retorna os dados carregados.
     * 
     * @return Grid
     */
    public function make() {

        # Paging
        $this->_paging();

        # Ordering
        $this->_ordering();

        # Filtering
        $this->_filtering();

        # Result
        $this->_result();

        # Individual column filtering
        $this->_individualFiltering();

        # TotalRecords
        $this->_totalRecords();

        # TotalDisplayRecordsRecords
        $this->_totalDisplayRecords();

        # Output
        $output = array(
            'sEcho' => intval($this->_params['sEcho']),
            'iTotalRecords' => (int) $this->_total, //Nr de registros
            'iTotalDisplayRecords' => (int) $this->_filtered_total, //Nr total de registros por página
            'aaData' => array()
        );

        # Populate aaData
        foreach ($this->_result as $aRow) {
            $row = array();
            for ($i = 0; $i < count($this->getColumns()); $i++) {
                if ($this->getColumns($i, false) == "version") {
                    /* Special output formatting for 'version' column */
                    $row[] = ($aRow->{$this->getColumns($i, false)} == "0") ? '-' : $aRow->{$this->getColumns($i, false)};
                } else if ($this->getColumns($i, false) != ' ') {
                    /* General output */
                    $row[] = $aRow->{$this->getColumns($i, false)};
                }
            }
            $output['aaData'][] = $row;
        }

        $this->_output = $output;

        return $this;
    }

}