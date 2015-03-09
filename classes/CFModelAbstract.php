<?php

/**
 * @author Michael F. Rodrigues <cerberosnash@gmail.com>
 */
abstract class CFModelAbstract {

    /**
     * @var string 
     */
    protected $_entry = 'default';

    /**
     * @var string 
     */
    protected $_sequence = NULL;

    /**
     * @var string
     */
    protected $_schema = NULL;

    /**
     * @var string
     */
    protected $_table = NULL;

    /**
     * @var string
     */
    protected $_primary = NULL;

    /**
     * @var array
     */
    protected $_fields = array();

    /**
     * @var array
     */
    protected $_types = array('null' => 0, 'integer' => 1, 'float' => 2, 'date' => 2, 'string' => 2, 'boolean' => 5);

    /**
     * @var \PDO
     */
    protected $_conn = NULL;

    /**
     * @var string
     */
    protected $_now = NULL;

    /**
     * @var string
     */
    protected $_entity = NULL;

    /**
     * @return void
     */
    protected function __construct($entry = NULL) {

        $entry = (is_null($entry)) ? $this->_entry : $entry;

        $this->_conn = CFConnection::factory($entry);
        $this->_check();
        $this->_entity = (is_null($this->_schema)) ? $this->_table : "{$this->_schema}.{$this->_table}";
        $this->_now = date('Y-m-d H:i:s');
    }

    /**
     * @return ModelAbstract
     */
    public static function factory($entry = NULL) {
        $class = get_called_class();

        return new $class($entry);
    }

    /**
     * @return integer
     * @param array $object
     * @param boolean $incremental
     */
    public function insert($object, $incremental = true) {

        $count = 1;

        $jokers = $fields = array();

        if (!$incremental) {
            $this->_fields[$this->_primary] = 'integer';
        }

        foreach ($this->_fields as $field => $type) {
            if (!array_key_exists($field, $object)) {
                continue;
            }
            if (array_key_exists($field, $object)) {
                $fields[] = $field;
                $jokers[] = '?';
            }
        }


        try {

            $stmt = $this->_conn->prepare(sprintf("insert into %s (%s) values (%s) RETURNING %s", $this->_entity, implode(',', $fields), implode(',', $jokers), $this->_primary));

            foreach ($this->_fields as $field => $type) {
                if (array_key_exists($field, $object)) {
                    $stmt->bindParam($count, $object["{$field}"], $this->_types["{$type}"]);
                    $count++;
                }
            }

            $stmt->execute();

            return $stmt->fetch(PDO::FETCH_OBJ)->{$this->_primary};
        } catch (\PDOException $e) {
            throw new \Exception($e->getMessage());
        }
    }

    /**
     * @return integer
     * @param array $object
     */
    public function update($object) {

        if (!array_key_exists($this->_primary, $object)) {
            throw new \Exception("A chave primária ($this->_primary) do registro não foi informada!");
        }

        $count = 1;

        $jokers = $fields = array();

        foreach ($this->_fields as $field => $type) {
            if (array_key_exists($field, $object)) {
                $jokers[] = " {$field} = ?";
            }
        }

        try {

            $stmt = $this->_conn->prepare(sprintf('update %s set %s where %s = ?', $this->_entity, implode(',', $jokers), $this->_primary));

            foreach ($this->_fields as $field => $type) {
                if (array_key_exists($field, $object)) {
                    $stmt->bindParam($count, $object["{$field}"], (!is_null($object["{$field}"]) ? $this->_types["{$type}"] : 0));
                    $count++;
                }
            }

            $stmt->bindParam($count, $object["{$this->_primary}"], 1);

            $stmt->execute();

            $rowAffected = $stmt->rowCount();

            return $rowAffected;
        } catch (\PDOException $e) {
            throw new \Exception($e->getMessage());
        }
    }

    /**
     * @return integer
     * @param integer $id
     */
    public function delete($id) {

        try {

            $stmt = $this->_conn->prepare(sprintf('delete from %s where %s = ?', $this->_entity, $this->_primary));

            $stmt->bindParam(1, $id, 1);

            $stmt->execute();

            $rowAffected = $stmt->rowCount();

            return $rowAffected;
        } catch (\PDOException $e) {
            throw new \Exception($e->getMessage());
        }
    }

    /**
     * @return \stdClass
     * @param integer $id
     */
    public function find($id) {
        try {

            $fields = $this->_fields;
            $fields["{$this->_primary}"] = 'integer';

            foreach ($fields as $field => $type) {
                $columns[] = $field;
            }

            $stmt = $this->_conn->prepare(sprintf('select %s from %s where %s = ? limit 1', implode(',', $columns), $this->_entity, $this->_primary));

            $stmt->bindParam(1, $id, 1);

            $stmt->execute();

            return $stmt->fetchAll(\PDO::FETCH_OBJ);
        } catch (\PDOException $e) {
            throw new \Exception($e->getMessage());
        }
    }

    /**
     * @return array
     * @param integer $offset
     * @param integer $limit
     * @param string $order
     * @param string $asc
     */
    public function findAll($offset = 0, $limit = 10, $order = null, $asc = 'asc') {
        if (is_null($order)) {
            $order = $this->_primary;
        }

        $columns = array();

        $this->_fields["{$this->_primary}"] = 'integer';

        foreach ($this->_fields as $field => $type) {
            $columns[] = $field;
        }

        try {

            $stmt = $this->_conn->prepare(sprintf('select %s from %s order by %s %s limit %d offset %d', implode(',', $columns), $this->_entity, $order, $asc, $limit, $offset));

            $stmt->execute();

            return $stmt->fetchAll(\PDO::FETCH_OBJ);
        } catch (\PDOException $e) {
            throw new \Exception($e->getMessage());
        }
    }

    /**
     * @return array
     */
    public function findByParam($object, $or = false) {

        $count = 1;

        $fields = $this->_fields;
        $fields["{$this->_primary}"] = 'integer';

        foreach ($fields as $field => $type) {
            if (array_key_exists($field, $object)) {
                $where[] = " {$field} = ? ";
            }
            $columns[] = $field;
        }

        try {

            $stmt = $this->_conn->prepare(sprintf('select %s from %s where %s', implode(',', $columns), $this->_entity, implode((($or) ? 'or' : 'and'), $where)));

            foreach ($fields as $field => $type) {
                if (array_key_exists($field, $object)) {
                    $stmt->bindParam($count, $object["{$field}"], $this->_types["{$type}"]);
                    $count++;
                }
            }

            $stmt->execute();

            return $stmt->fetchAll(\PDO::FETCH_OBJ);
        } catch (\PDOException $e) {
            throw new \Exception($e->getMessage());
        }
    }

    /**
     * @return ModelAbstract
     */
    public function beginTransaction() {
        $this->_conn->beginTransaction();
        return $this;
    }

    /**
     * @return ModelAbstract
     */
    public function commit() {
        $this->_conn->commit();
        return $this;
    }

    /**
     * @return ModelAbstract
     */
    public function rollback() {
        $this->_conn->rollBack();
        return $this;
    }

    /**
     * @return void
     */
    private function _check() {
        if (is_null($this->_fields)) {
            throw new \Exception('Os campos da tabela não foram definidos!');
        }

        if (is_null($this->_primary)) {
            throw new \Exception('A chave primária na tabela não foi definida!');
        }

        if (is_null($this->_table)) {
            throw new \Exception('O nome da tabela não foi definido!');
        }
    }

}
