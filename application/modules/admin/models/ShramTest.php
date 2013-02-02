<?php

class Admin_Model_ShramTest {

    protected $_dbTable;

    public function setDbTable($dbTable) {
        if (is_string($dbTable)) {
            $dbTable = new $dbTable();
        }
        if (!$dbTable instanceof Zend_Db_Table_Abstract) {
            throw new Exception('Invalid table data gateway provided');
        }
        $this->_dbTable = $dbTable;
        return $this;
    }

    public function getDbTable() {
        if (null === $this->_dbTable) {
            $this->setDbTable('Admin_Model_DbTable_ShramTest');
        }
        return $this->_dbTable;
    }

    public function add($formData) {
        $this->getDbTable()->insert($formData);
    }

    public function getAllFail($where) {
        $db = Zend_Db_Table::getDefaultAdapter();
        $select = $db->select();
        $select->from(array("ccan" => "consultancy_shram_test"), array("ccan.*"))
                ->joinLeft(array("dis" => "consultancy_candidate"), "ccan.candidate_id=dis.candidate_id", array("dis.*"))
                ->joinLeft(array("a" => "consultancy_agent"), "a.agent_id=dis.agent_id", array("a.name as agent_name"))
                ->where("ccan.status = '$where'");
        $result = $db->fetchAll($select);
        return $result;
    }

    public function update($data,$id) {
        $this->getDbTable()->update($data, "candidate_id='$id'");
    }

}

?>
