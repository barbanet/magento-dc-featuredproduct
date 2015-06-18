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

class Dc_FeaturedProduct_Adminhtml_FeaturedProduct_GroupController extends Mage_Adminhtml_Controller_Action
{

    protected function _initAction()
    {
        $this->loadLayout()->_setActiveMenu('cms/featuredproduct');
        return $this;
    }   
 
    public function indexAction()
    {
        $this->_initAction()
            ->renderLayout();
    }
    
    public function newAction()
    {
        $this->_forward('edit');
    }
    
    public function gridAction()
    {
        $this->getResponse()->setBody(
            $this->getLayout()->createBlock('featuredproduct/adminhtml_group_grid')->toHtml()
        );
    }
    
    public function gridProductAction()
    {
        $this->getResponse()->setBody(
            $this->getLayout()->createBlock('featuredproduct/adminhtml_group_edit_tab_product')->toHtml()
        );
    }
    
    public function editAction()
    {
        $id = $this->getRequest()->getParam('id');
        $model = Mage::getModel('featuredproduct/group')->load($id);
        if ($model->getId() || $id == 0) {
            $data = Mage::getSingleton('adminhtml/session')->getFormData(true);
            if (!empty($data)) {
                $model->setData($data);
            }
            Mage::register('featuredproduct_data', $model);
            $this->loadLayout();
            $this->_setActiveMenu('catalog/featuredproduct');
            $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);
            $this->_addContent($this->getLayout()->createBlock('featuredproduct/adminhtml_group_edit'))
                ->_addLeft($this->getLayout()->createBlock('featuredproduct/adminhtml_group_edit_tabs'));
            $this->renderLayout();
        } else {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('featuredproduct')->__('Featured Group does not exist'));
            $this->_redirect('*/*/');
        }
    }
    
    public function saveAction()
    {
        if ($data = $this->getRequest()->getPost()) {
            $model = Mage::getModel('featuredproduct/group');
            try {
                $model->setData($data)
                   ->setId($this->getRequest()->getParam('id'));
                $model->save();
                if (isset($data['group_products'])) {
                    $products = array();
                    parse_str($data['group_products'], $products);
                    $model->setGroupItems($products);
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('featuredproduct')->__('Featured Group was successfully saved'));
                Mage::getSingleton('adminhtml/session')->setFormData(false);
                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('id' => $model->getId()));
                    return;
                }
                $this->_redirect('*/*/');
                return;
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::logException($e);
                Mage::getSingleton('adminhtml/session')->setFormData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('featuredproduct')->__('Unable to find Featured Group to save'));
        $this->_redirect('*/*/');
    }
    
    public function deleteAction()
    {
        if( $this->getRequest()->getParam('id') > 0 ) {
            try {
                $model = Mage::getModel('featuredproduct/group');
                $model->setId($this->getRequest()->getParam('id'))
                    ->delete();
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('featuredproduct')->__('Featured Group was successfully deleted'));
                $this->_redirect('*/*/');
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
            }
        }
        $this->_redirect('*/*/');
    }
    
    public function massDeleteAction()
    {
        $groupIds = $this->getRequest()->getParam('groups');
        if(!is_array($groupIds)) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select item(s)'));
        } else {
            try {
                foreach ($groupIds as $groupId) {
                    $group = Mage::getModel('featuredproduct/group')->load($groupId);
                    $group->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('adminhtml')->__(
                        'Total of %d record(s) were successfully deleted', count($groupIds)
                    )
                );
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }

}
