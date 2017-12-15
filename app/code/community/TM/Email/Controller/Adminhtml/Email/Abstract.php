<?php

abstract class TM_Email_Controller_Adminhtml_Email_Abstract extends Mage_Adminhtml_Controller_Action
{
    protected $breadcrumbLabel = '';
    protected $breadcrumbTitle = '';

    protected $resource = '';

    protected function _initAction()
    {
        $this->loadLayout();
        if (!empty($this->breadcrumbLabel)) {
            $this->_setActiveMenu('templates_master/tm_email')
                ->_addBreadcrumb(
                    $this->__($this->breadcrumbLabel),
                    $this->__($this->breadcrumbTitle)
                );
        }

        return $this;
    }

    public function indexAction()
    {
        $this->_initAction();
        $this->renderLayout();
    }

    public function newAction()
    {
        $this->_redirect('*/*/edit');
    }

    protected function _saveAction(array $data)
    {
    }

    public function saveAction()
    {
        $data = $this->getRequest()->getPost();
        if (!$data) {
            Mage::getSingleton('adminhtml/session')->addError(
                $this->__('Unable to find item to save')
            );
            $this->_redirect('*/*/');
        }

        try {
            $model = $this->_saveAction($data);

            //success
            Mage::getSingleton('adminhtml/session')->addSuccess(
                $this->__('Item was successfully saved')
            );
            Mage::getSingleton('adminhtml/session')->setFormData(false);

            if ($this->getRequest()->getParam('back')) {
                $this->_redirect('*/*/edit', array('id' => $model->getId()));
                return;
            }
            $this->_redirect('*/*/');
            return;
        } catch (Exception $e) {
            Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            Mage::getSingleton('adminhtml/session')->setFormData($data);
            $this->_redirect(
                '*/*/edit',
                array('id' => $this->getRequest()->getParam('id'))
            );
            return;
        }
    }

    protected function _deleteAction($id)
    {
    }

    public function deleteAction()
    {
        $id = (int) $this->getRequest()->getParam('id');
        if ($id > 0) {
            try {
                $redirect = $this->_deleteAction($id);
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('adminhtml')->__('Item was successfully deleted')
                );
                if (is_array($redirect)) {
                    $redirectPath = $redirect[0];
                    $redirectParams = isset($redirect[1]) ? $redirect[1] : array();
                } else {
                    $redirectPath = '*/*/';
                    $redirectParams = array();
                }
                $this->_redirect($redirectPath, $redirectParams);
                return;
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $id));
            }
        }
        $this->_redirect('*/*/');
    }

    /**
     * Check the permission to run it
     *
     * @return boolean
     */
    protected function _isAllowed()
    {
        if (empty($this->resource)) {
            throw new Mage_Exception("Resource canot be empty");
        }
        return Mage::getSingleton('admin/session')->isAllowed($this->resource);
    }
}
