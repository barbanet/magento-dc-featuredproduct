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

class Dc_FeaturedProduct_Block_Adminhtml_Group extends Mage_Adminhtml_Block_Widget_Grid_Container
{

    public function __construct()
    {
        $this->_controller = 'adminhtml_group';
        $this->_blockGroup = 'featuredproduct';
        $this->_headerText = Mage::helper('featuredproduct')->__('Featured Product Groups');
        $this->_addButtonLabel = Mage::helper('featuredproduct')->__('Add Group');
        parent::__construct();
    }

}
