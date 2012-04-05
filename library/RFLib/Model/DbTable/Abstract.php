<?php

abstract class RFLib_Model_DbTable_Abstract extends Zend_Db_Table_Abstract
{

    // public function init()
    // {
        // // fix some database can't show Chinese char
        // $dbConfig = $this->_db->getConfig();
        // $this->_db->query("SET NAMES '" . $dbConfig['charset'] . "'");
    // }

    /**
     * Save a row to the database
     *
     * @param array             $info The data to insert/update
     * @param Zend_DB_Table_Row $row Optional The row to use
     * @return mixed The primary key
     */
    public function saveRow($info, $row = null)
    {
        if (null === $row) {
            $row = $this->createRow();
        }

        $columns = $this->info('cols');
        foreach ($columns as $column) {
            if (array_key_exists($column, $info)) {
                $row->$column = $info[$column];
            }
        }

        return $row->save();
    }

}
