<?php

class TM_Email_Adminhtml_Gateway_TransportController extends TM_Email_Controller_Adminhtml_Email_Abstract
{

    protected $breadcrumbLabel = 'Gateway Manager';
    protected $breadcrumbTitle = 'Transport';

    protected $resource = 'templates_master/tm_email/gateway';

    public function editAction()
    {

        $_id = $this->getRequest()->getParam('id', 0);

        $transport = Mage::getModel('tm_email/gateway_transport')->load($_id);
        $id = $transport->getId();

        if (!$id && 0 !== $_id) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('tm_email')->__('Item does not exist')
            );
            $this->_redirect('*/*/');
        }

        $data = Mage::getSingleton('adminhtml/session')->getFormData(true);
        if (!empty($data)) {
            $transport->setData($data);
        }

        Mage::register('tm_email_gateway_transport_data', $transport);

        $this->loadLayout();
        $this->_setActiveMenu('tm_email/gateway_transport');
        $this->renderLayout();
    }

    public function testAction()
    {
        $data = $this->getRequest()->getPost();

        $model = Mage::getModel('tm_email/gateway_transport');

        if (empty($data['id'])) {
            unset($data['id']);
        }
        $model->setData($data);

        try {
            $transport  = $model->getTransport();
            $email = $model->getEmail();

            $mail = new Zend_Mail('utf-8');
            $mail->setBodyText('This is test transport mail.');
            $mail->setFrom($email, 'test');
            $mail->addTo($email, 'test');
            $mail->setSubject('Test Email Transport (' . now() . ')');
            $mail->send($transport);

//            $transport->countMessages();
            Mage::getSingleton('adminhtml/session')->addSuccess(
                Mage::helper('tm_email')->__(
                    'Connection with mail server was succesfully established. Please check your inbox to verify this final.'
                )
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
        $model = Mage::getModel('tm_email/gateway_transport');
        if (empty($data['id'])) {
            unset($data['id']);
        }
        $model->setData($data);

        $model->save();
        return $model;
    }

    protected function _deleteAction($id)
    {
        return Mage::getModel('tm_email/gateway_transport')->setId($id)->delete();
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
                    $model = Mage::getModel('tm_email/gateway_transport')->load($_id);
                    $model->delete();
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
        $fileName   = 'tm_email_gateway_transport.csv';
        $content    = $this->getLayout()
            ->createBlock('tm_email/adminhtml_gateway_transport_grid')
            ->getCsv();

        $this->_prepareDownloadResponse($fileName, $content);
    }

    public function exportXmlAction()
    {
        $fileName   = 'tm_email_gateway_transport.xml';
        $content    = $this->getLayout()
            ->createBlock('tm_email/adminhtml_gateway_transport_grid')
            ->getXml();

        $this->_prepareDownloadResponse($fileName, $content);
    }
}
