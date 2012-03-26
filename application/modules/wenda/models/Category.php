<?php

class Wenda_Model_Category extends RFLib_Model_Abstract
{

    const ROOT_ID = 0;

    /**
     * Find category by their id
     * 
     * @param int $id            
     * @return Zend_Db_Table_Row null
     */
    public function findById ($id)
    {
        return $this->getTable('Category')->findById($id);
    }
    
    /**
     * Find category by their parent id
     * 
     * @param int $id            
     * @return Zend_Db_Table_Row null
     */
    public function findByParentId ($id)
    {
        return $this->getTable('Category')->findByParentId($id);
    }

    /**
     * Find parent of category by their id
     * 
     * @param int $id            
     * @return Zend_Db_Table_Row null
     */
    public function findParentById ($id)
    {
        return $this->getTable('Category')->findParentById($id);
    }

    /**
     * Get parentId = 0 all categories
     *
     * @return array null
     */
    public function getRoot ()
    {
        return $this->findByParentId(self::ROOT_ID)->toArray();
    }

    /**
     * Get parents of tree
     *
     * @param int $id            
     * @return null array
     */
    public function getParents ($id, $append = true)
    {
        $category = $this->findById($id);
        
        if (null === $category) {
            return null;
        }
        
        $cats = $append ? array($category->toArray()) : array();
        if (self::ROOT_ID == $category->parent_id) {
            $parent = $this->findParentById($id);
            $cats[] = $parent->toArray();
            $cats = array_merge($cats, $this->getParents($parent->id, false));
        }
        
        return $cats;
    }

    /**
     * Get children category by their id
     * 
     * @param int $id            
     * @return array
     */
    public function getChildren ($id, $recursive = false)
    {
        $categories = $this->findByParentId($id);
        
        $cats = array();
        foreach ($categories as $category) {
            $cats[] = $category->toArray();
            if (true === $recursive) {
                $cats = array_merge($cats, 
                        $this->getChildren($category->id, true));
            }
        }
        
        return $cats;
    }

    /**
     * Get all categories
     * 
     * @return array
     */
    public function getAll ()
    {
        $result = $this->getTable('Category')
            ->fetchAll()
            ->toArray();
        
        // set id to key
        $rows = array();
        foreach ($result as $row) {
            $rows[$row['id']] = $row;
        }
        // Merge children to parent
        $t = array();
        foreach ($rows as $id => $item) {
            if (isset($item['parent_id'])) {
                if (self::ROOT_ID == $item['parent_id']) {
                    $rows[$item['parent_id']][$item['id']] = &$rows[$item['id']];
                } else {
                    $rows[$item['parent_id']]['subcategory'][$item['id']] = &$rows[$item['id']];
                }
                // record who need unset
                $t[] = $id;
            }
        }
        foreach ($t as $u) {
            unset($rows[$u]);
        }
        return $rows[0];
    }
}