<?php
class RFLib_View_Helper_T extends Zend_View_Helper_Translate
{
	public function t()
	{
		if (0 == func_num_args()) {
			return $this->translate();
		} else {
			$params = func_get_args();
			$message = $this->translate($params[0]);
			if (isset($params[1])) {
				$message = vsprintf($message,$params[1]);
			}
			return $message;
		}
	}
}