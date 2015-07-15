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

class Dc_FeaturedProduct_Block_Adminhtml_Group_Js extends Mage_Adminhtml_Block_Template
{

    /**
     * Get products associated with a given group as json string.
     *
     * @return string
     */
    public function getProductsJson()
    {
        $collection = Mage::getModel('featuredproduct/item')->getProductsByGroup($this->getId());
        if (!empty($collection)) {
            $products = array();
            $array = $collection->toArray();
            $items = $array['items'];
            foreach ($items as $item) {
                $products[$item['product_id']] = $item['position'];
            }
            return Mage::helper('core')->jsonEncode($products);
        }
        return '{}';
    }

    /**
     * @return string
     */
    public function getJsObjectName()
    {
        return 'groupProductGridJsObject';
    }

    /**
     * @return mixed
     */
    public function getFeaturedGroup()
    {
        return Mage::registry('featuredproduct_data');
    }

    /**
     * @return mixed
     */
    public function getId() {
        return $this->getFeaturedGroup()->getId();
    }

    /**
     * @return bool|mixed
     */
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
