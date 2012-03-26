<?php

class Wenda_Model_Keyword extends RFLib_Model_Abstract
{

    /**
     * 取热门标签
     *
     * @param <type> $categoryId
     * @param <type> $keywords
     */
    public function getHots($categoryId,$limit=50)
    {
        return $this->getTable('Keyword')->getHots($categoryId,$limit);
    }

    public function create($categoryId, $keywords)
    {
        foreach ($keywords as $keyword) {
            $keyword = trim($keyword);
            $data = array('category_id' => $categoryId,'value'=>strtolower($keyword), 'scores'=>1);
            $row = $this->getTable('Keyword')->findByCateAndValue($categoryId, $data['value']);
            if ($row) {
                $data['scores'] = $row['scores'] + 1;
            } else {
                $data['word'] = $keyword;
            }
            $this->_save($data, $row);
        }
    }

    public function optimize($words)
    {
        $arr = array_map('strtolower',$words);
        $arr = array_unique($arr);
        $keywords = array();
        foreach($arr as $key=>$value) {
            $keywords[]= $this->getTable('Keyword')->getWordByValue($value);
        }
        return $keywords;
    }

    protected function _save($data, $row)
    {
        $this->getTable('Keyword')->saveRow($data,$row);
    }


}