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
 * @copyright  Copyright (c) 2015 Damián Culotta. (http://www.damianculotta.com.ar/)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

class Dc_FeaturedProduct_Block_Adminhtml_Group_Js extends Mage_Adminhtml_Block_Template
{

    public function getProductsJson()
    {
        $_collection = Mage::getModel('featuredproduct/item')->getProductsByGroup($this->getId());
        if (!empty($_collection)) {
            $_products = array();
            $_array = $_collection->toArray();
            $_items = $_array['items'];
            foreach ($_items as $_item) {
                $_products[$_item['product_id']] = $_item['position'];
            }
            return Mage::helper('core')->jsonEncode($_products);
        }
        return '{}';
    }
    
    public function getJsObjectName()
    {
        return 'groupProductGridJsObject';
    }
    
    public function getFeaturedGroup()
    {
        return Mage::registry('featuredproduct_data');
    }
    
    public function getId() {
        return $this->getFeaturedGroup()->getId();
    }
    
    public function getProductsPosition()
    {
        if (!$this->getId()) {
            return false;
        }
        $array = $this->getData('products_position');
        if (is_null($array)) {
            $array = $this->getResource()->getProductsPosition($this);
            $this->setData('products_position', $array);
        }
        return $array;
    }
    
}
