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

class Dc_FeaturedProduct_Model_Direction extends Mage_Core_Model_Abstract
{

    const ASC = '1';
    const DESC = '2';

    /**
     * @return array
     */
    public function toOptionArray()
    {
        return array(
                    array(
                            'value' => self::ASC,
                            'label' => Mage::helper('featuredproduct')->__('Asc')
                        ),
                    array(
                            'value' => self::DESC,
                            'label' => Mage::helper('featuredproduct')->__('Desc')
                        )
                    );
    } 

}
