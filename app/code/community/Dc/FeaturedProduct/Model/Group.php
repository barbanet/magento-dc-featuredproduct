<?php
/**
 * Dc_FeaturedProduct
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 *
 * @category   Dc
 * @package    Dc_FeaturedProduct
 * @copyright  Copyright (c) 2013-2015 Damián Culotta. (http://www.damianculotta.com.ar/)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

class Dc_FeaturedProduct_Model_Group extends Mage_Core_Model_Abstract
{
    
    public function _construct()
    {
        parent::_construct();
        $this->_init('featuredproduct/group');
    }

    /**
     * Assign products into group.
     *
     * @param array $items
     */
    public function setGroupItems(array $items)
    {
        $this->deleteByGroup();
        if ($this->getGroupId()) {
            foreach ($items as $item_product => $item_position) {
                $data = array(
                                'group_id' => $this->getGroupId(),
                                'product_id' => $item_product,
                                'position'  => $item_position
                                ); 
                Mage::getModel('featuredproduct/item')->setData($data)->save();
            }
        }
    }

    /**
     * Delete all products from a given group.
     *
     * @return bool
     */
    protected function deleteByGroup()
    {
        if ($this->getGroupId()) {
            $connection = Mage::getSingleton('core/resource')->getConnection('read');
            $table = Mage::getSingleton('core/resource')->getTableName('featuredproduct/item');
            $connection->query("DELETE FROM {$table} WHERE group_id = {$this->getGroupId()};");
        }
        return true;
    }

    /**
     * @return array
     */
    public function toOptionArray()
    {
        $_values = array();
        $_groups = Mage::getModel('featuredproduct/group')->getCollection();
        foreach ($_groups as $_group) {
            $_values[] = array('value' => $_group->getGroupId(), 'label' => $_group->getName());
        }
        return $_values;
    }    

}
