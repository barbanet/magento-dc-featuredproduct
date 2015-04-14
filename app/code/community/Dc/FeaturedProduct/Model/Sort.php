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

class Dc_FeaturedProduct_Model_Sort extends Mage_Core_Model_Abstract
{

    const NATURAL = '1';
    const RANDOM  = '2';
    const PRICE   = '3';
    const NAME    = '4';

    public function toOptionArray()
    {
        return array(
                    array(
                            'value' => self::NATURAL,
                            'label' => Mage::helper('featuredproduct')->__('Natural')
                        ),
                    array(
                            'value' => self::NAME,
                            'label' => Mage::helper('featuredproduct')->__('Name')
                        ),
                    array(
                            'value' => self::PRICE,
                            'label' => Mage::helper('featuredproduct')->__('Price')
                        ),
                    array(
                            'value' => self::RANDOM,
                            'label' => Mage::helper('featuredproduct')->__('Random')
                        )
                    );
    } 

}
