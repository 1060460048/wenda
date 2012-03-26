<?php

class Wenda_Model_DbTable_City extends RFLib_Model_DbTable_Abstract
{

    protected $_name = 'City';

    public function getProvinces()
    {
        $sql = 'SELECT distinct province FROM city';
        return $this->_db->fetchCol($sql);
    }

}
