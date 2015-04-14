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

class Dc_FeaturedProduct_Model_Fpc_Container_Widget extends Enterprise_PageCache_Model_Container_Abstract
{
    
    const CACHE_PREFIX = 'FEATUREDPRODUCT_WIDGET_';

    protected function _getCacheId()
    {
        return self::CACHE_PREFIX . $this->_placeholder->getAttribute('unique_id');
    }
    
    protected function _saveCache($data, $id, $tags = array(), $lifetime = null)
    {
        return false;
    }

    protected function _renderBlock()
    {
        $block = $this->_getPlaceHolderBlock();
        $id = $this->_getCacheId() . '_params';
        if ($parameters = Enterprise_PageCache_Model_Cache::getCacheInstance()->load($id)) {
            $block->addData(unserialize($parameters));
            $block->setFullPageCacheEnvironment(true);
        }
        Mage::dispatchEvent('render_block', array('block' => $block, 'placeholder' => $this->_placeholder));
        return $block->toHtml();
    }
       
}
