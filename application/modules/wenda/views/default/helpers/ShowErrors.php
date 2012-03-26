<?php
class Wenda_View_Helper_Showerrors extends Zend_View_Helper_Abstract
{
    public function showErrors($errors,$className='error-msg')
    {
        $class = '';
        if (empty($errors)) {
            $class = ' nodisplay';
        }
        echo '<div class="'. $className . $class.'" id="form_msg"><ul>';
        if (count($errors) > 0)  {
            foreach ($errors as $key => $value) {
                echo '<li>' . $value . '</li>';
            }
        }
        echo '</ul></div>';
    }
}
?>
