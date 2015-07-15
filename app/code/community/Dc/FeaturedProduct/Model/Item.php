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

class Dc_FeaturedProduct_Model_Item extends Mage_Core_Model_Abstract
{
    
    public function _construct()
    {
        parent::_construct();
        $this->_init('featuredproduct/item');
    }

    /**
     * @param $group_id
     * @return bool
     */
    public function getProductsByGroup($group_id)
    {
        $_products = Mage::getModel('featuredproduct/item')->getCollection()
                            ->removeAllFieldsFromSelect()
                            ->addFieldToSelect(array('product_id', 'position'))
                            ->addFieldToFilter('group_id', array('eq' => $group_id));
        if ($_products->getSize() > 0) {
            return $_products;
        }
        return false;
    }
    
}
