<?php

class TM_Email_Adminhtml_Gateway_StorageController extends TM_Email_Controller_Adminhtml_Email_Abstract
{
    protected $breadcrumbLabel = 'Gateway Manager';
    protected $breadcrumbTitle = 'Gateway';

    protected $resource = 'templates_master/tm_email/gateway';

    public function editAction()
    {
        $id = $this->getRequest()->getParam('id', 0);

        $gateway = Mage::getModel('tm_email/gateway_storage')->load($id);
        $gatewayId = $gateway->getId();

        if (!$gatewayId && 0 !== $id) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('tm_email')->__('Item does not exist')
            );
            $this->_redirect('*/*/');
        }

        $data = Mage::getSingleton('adminhtml/session')->getFormData(true);
        if (!empty($data)) {
            $gateway->setData($data);
        }

        Mage::register('tm_email_gateway_storage_data', $gateway);

        $this->loadLayout();
        $this->_setActiveMenu('tm_email/gateway_storage');
        $this->renderLayout();
    }

    public function testAction()
    {
        $data = $this->getRequest()->getPost();

        $modelStorage = Mage::getModel('tm_email/gateway_storage');

        if (empty($data['id'])) {
            unset($data['id']);
        }
        $modelStorage->setData($data);

        try {
            $storage  = $modelStorage->getStorage();
            $storage->countMessages();
            Mage::getSingleton('adminhtml/session')->addSuccess(
                Mage::helper('tm_email')->__('Connection with mail server was succesfully established')
            );
        } catch (Zend_Exception $e) {
            Mage::getSingleton('adminhtml/session')->addError(
                $e->getMessage()
            );
        }
        Mage::getSingleton('adminhtml/session')->setFormData($data);

        $this->_redirectReferer();
    }

    protected function _saveAction(array $data)
    {
        $model = Mage::getModel('tm_email/gateway_storage');

        if (empty($data['id'])) {
            unset($data['id']);
        }
        $model->addData($data);

        $model->save();
        return $model;
    }

    protected function _deleteAction($id)
    {
        return Mage::getModel('tm_email/gateway_storage')->setId($id)->delete();
    }

    public function massDeleteAction()
    {
        $_ids = $this->getRequest()->getParam('gateway');
        if (!is_array($_ids)) {
            Mage::getSingleton('adminhtml/session')
                ->addError(Mage::helper('adminhtml')->__('Please select item(s)'));
        } else {
            try {
                foreach ($_ids as $_id) {
                    $storage = Mage::getModel('tm_email/gateway_storage')->load($_id);
                    $storage->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('adminhtml')->__(
                        'Total of %d record(s) were successfully deleted',
                        count($_ids)
                    )
                );
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }

    public function exportCsvAction()
    {
        $fileName   = 'tm_email_gateway_storage.csv';
        $content    = $this->getLayout()->createBlock('tm_email/adminhtml_gateway_storage_grid')
            ->getCsv();

        $this->_prepareDownloadResponse($fileName, $content);
    }

    public function exportXmlAction()
    {
        $fileName   = 'tm_email_gateway_storage.xml';
        $content    = $this->getLayout()->createBlock('tm_email/adminhtml_gateway_storage_grid')
            ->getXml();

        $this->_prepareDownloadResponse($fileName, $content);
    }
}
