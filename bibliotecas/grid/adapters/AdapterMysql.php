<?php

namespace br\com\cf\library\core\grid\adapters;

use br\com\cf\library\core\grid\GridAbstract,
    br\com\cf\library\core\grid\Grideable

;

/**
 * @deprecated 
 */

/**
 *
 * @author Michael F. Rodrigues
 * @version 0.0.0
 */
class AdapterMysql extends \br\com\cf\library\core\model\ModelAbstract implements Grideable
{

    /**
     * @var string
     */
    protected $_primary = '';

    /**
     * @var string
     */
    protected $_table = '';

    /**
     * @return void
     */
    public function filtering (GridAbstract $grid)
    {
        if ($grid->getParams('sSearch') != '') {
            $grid->setWhere('where (');
            for ($i = 0; $i < count($grid->getColumns()); $i++) {
                if ($grid->getParams('bSearchable_' . $i) == 'true') {
                    $where = $grid->getWhere();
                    $where .= sprintf("%s like '%s%s%s' or ", $grid->getColumns($i), '%', $grid->getParams('sSearch'), '%');
                    $grid->setWhere($where);
                }
            }
            $grid->setWhere(substr_replace($grid->getWhere(), '', -3) . ')');
        }
    }

    /**
     * 
     */
    public function result (GridAbstract $grid)
    {
        $implode = '';
        $columns = $grid->getColumns();

        foreach ($columns as $column) {
            foreach ($column as $real => $alias) {
                $implode .= " {$real} as {$alias}, ";
            }
        }

        $sQuery = sprintf('select %s from %s %s %s %s %s', substr($implode, -0, -2), $grid->getQuery(), $grid->getWhere(), $grid->getGroup(), $grid->getOrder(), $grid->getLimit());
        $stmt = $this->_conn->prepare($sQuery);
        $stmt->execute();
        $grid->setResult($stmt->fetchAll(\PDO::FETCH_OBJ));
    }

    /**
     * 
     */
    public function totalRecords (GridAbstract $grid)
    {
        $sQuery = sprintf('select count(*) as total from %s ', $grid->getQuery());
        $stmt = $this->_conn->prepare($sQuery);
        $stmt->execute();
        $grid->setTotal($stmt->fetch(\PDO::FETCH_OBJ)->total);
    }

    /**
     * 
     */
    public function totalDisplayRecords (GridAbstract $grid)
    {
        if ($grid->getWhere() != '') {
            $sQuery = sprintf('select count(*) as total from %s %s %s %s', $grid->getQuery(), $grid->getWhere(), $grid->getOrder(), $grid->getLimit());
            $stmt = $this->_conn->prepare($sQuery);
            $stmt->execute();
            $grid->setFilteredTotal($stmt->fetch(\PDO::FETCH_OBJ)->total);
        } else {
            $grid->setFilteredTotal($grid->getTotal());
        }
    }

}