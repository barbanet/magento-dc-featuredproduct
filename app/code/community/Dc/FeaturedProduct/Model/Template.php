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

class Dc_FeaturedProduct_Model_Template extends Mage_Core_Model_Abstract
{
    
    public function _construct()
    {
        parent::_construct();
        $this->_init('featuredproduct/template');
    }

    /**
     * @return array
     */
    public function toOptionArray()
    {
        $_values = array();
        $_groups = Mage::getModel('featuredproduct/template')->getCollection();
        foreach ($_groups as $_group) {
            $_values[] = array('value' => $_group->getTemplateId(), 'label' => $_group->getName());
        }
        return $_values;
    } 

}
