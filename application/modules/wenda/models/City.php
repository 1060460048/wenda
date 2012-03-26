<?php
class Wenda_Model_City extends RFLib_Model_Abstract
{
	public function getProvinces()
	{
		return $this->getTable('City')->getProvinces();
	}
}
