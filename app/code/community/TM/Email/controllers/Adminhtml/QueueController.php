<?php

class TM_Email_Adminhtml_QueueController extends Mage_Adminhtml_Controller_Action
{

//    protected function _initAction() {
//        $this->loadLayout();
//        $this->_setActiveMenu('tm_email/tm_email')
//            ->_addBreadcrumb(
//                Mage::helper('tm_email')->__('Gateway Manager'),
//                Mage::helper('tm_email')->__('Gateway')
//            );
//
//        return $this;
//    }

    public function indexAction()
    {
//        $this->_initAction();
        $this->loadLayout();
        $this->renderLayout();
        Zend_Debug::dump($this->getFullActionName());

        $collection = Mage::getModel('tm_email/queue_queue')->getCollection();
        foreach ($collection as $_queue) {
//            Zend_Debug::dump($_queue->getData());
            $queue = Mage::getModel('tm_email/queue')->getQueue(
                $_queue->getQueueName()
            );
            /* @var $message Zend_Queue_Message */
            foreach ($queue->receive() as $i => $message) {
                //send the email here
                /* @var $mail Zend_Mail */
                /* @var $transport Zend_Mail_Transport_Abstract */
                list($mail, $transport) = unserialize($message->body);

                Zend_Debug::dump($mail);
                Zend_Debug::dump($transport);

                try {
                    $mail->send($transport);
                    $status = true;
                }
                catch (Exception $e) {
                    $status = false;
                    Mage::logException($e);
//                    return false;
                }
//                Zend_Debug::dump($status);
                if ($status) {
                    $queue->deleteMessage($message);
                }
            }

        }
    }
//
//    public function editAction()
//    {
//        $id = $this->getRequest()->getParam('id', 0);
//
//        $gateway = Mage::getModel('tm_email/gateway')->load($id);
//        $gatewayId = $gateway->getId();
//
//        if (!$gatewayId && 0 !== $id) {
//            Mage::getSingleton('adminhtml/session')->addError(
//                Mage::helper('tm_email')->__('Item does not exist')
//            );
//            $this->_redirect('*/*/');
//        }
//
//        $data = Mage::getSingleton('adminhtml/session')->getFormData(true);
//        if (!empty($data)) {
//            $gateway->setData($data);
//        }
//
//        Mage::register('tm_email_gateway_data', $gateway);
//
//        $this->loadLayout();
//        $this->_setActiveMenu('tm_email/gateway');
//        $this->renderLayout();
//    }
//
//    public function newAction()
//    {
//        $this->_redirect('*/*/edit');
//    }
//
//    public function testAction()
//    {
//        $data = $this->getRequest()->getPost();
//
//        $gateway = Mage::getModel('tm_email/gateway');
//
//        if (empty($data['id'])) {
//            unset($data['id']);
//        }
//        $gateway->setData($data);
//
//        try {
//
//            $storage  = $gateway->getStorage();
//            $storage->countMessages();
//            Mage::getSingleton('adminhtml/session')->addSuccess(
//                Mage::helper('tm_email')->__(
//                    'Connection with mail server was succesfully established'
//                )
//            );
//        } catch (Zend_Exception $e) {
//
//            Mage::getSingleton('adminhtml/session')->addError(
//                $e->getMessage()
//            );
//        }
//        Mage::getSingleton('adminhtml/session')->setFormData($data);
//
//        $this->_redirectReferer();
//    }
//
//    public function saveAction()
//    {
//        $data = $this->getRequest()->getPost();
//
//        if (!$data) {
//             Mage::getSingleton('adminhtml/session')->addError(
//                Mage::helper('tm_email')->__('Unable to find item to save')
//            );
//            $this->_redirect('*/*/');
//        }
//
//        try  {
//            $gateway = Mage::getModel('tm_email/gateway');
//
//            if (empty($data['id'])) {
//                unset($data['id']);
//            }
//            $gateway->setData($data);
//
//            $gateway->save();
//
//            //success
//            Mage::getSingleton('adminhtml/session')->addSuccess(
//                Mage::helper('tm_email')->__('Item was successfully saved')
//            );
//            Mage::getSingleton('adminhtml/session')->setFormData(false);
//
//            if ($this->getRequest()->getParam('back')) {
//                $this->_redirect('*/*/edit', array('id' => $gateway->getId()));
//                return;
//            }
//            $this->_redirect('*/*/');
//            return;
//        } catch (Exception $e) {
//            Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
//            Mage::getSingleton('adminhtml/session')->setFormData($data);
//            $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
//            return;
//        }
//    }
//
//    public function deleteAction()
//    {
//        $gatewayId = $this->getRequest()->getParam('id');
//        if( 0 < $gatewayId) {
//            try {
//                $gateway = Mage::getModel('tm_email/gateway');
//
//                $gateway->setId($gatewayId)->delete();
//
//                Mage::getSingleton('adminhtml/session')->addSuccess(
//                    Mage::helper('adminhtml')->__('Item was successfully deleted')
//                );
//                $this->_redirect('*/*/');
//            } catch (Exception $e) {
//                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
//                $this->_redirect('*/*/edit', array('id' => $gatewayId));
//            }
//        }
//        $this->_redirect('*/*/');
//    }
//
//    public function massDeleteAction() {
//        $gatewayIds = $this->getRequest()->getParam('gateway');
//        if(!is_array($gatewayIds)) {
//            Mage::getSingleton('adminhtml/session')
//                ->addError(Mage::helper('adminhtml')->__('Please select item(s)'));
//        } else {
//            try {
//                foreach ($gatewayIds as $gatewayId) {
//                    $gateway = Mage::getModel('tm_email/gateway')->load($gatewayId);
//                    $gateway->delete();
//                }
//                Mage::getSingleton('adminhtml/session')->addSuccess(
//                    Mage::helper('adminhtml')->__(
//                    'Total of %d record(s) were successfully deleted', count($gatewayIds)
//                    )
//                );
//            } catch (Exception $e) {
//                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
//            }
//        }
//        $this->_redirect('*/*/index');
//    }
//
//    public function exportCsvAction()
//    {
//        $fileName   = 'tm_email_gateway.csv';
//        $content    = $this->getLayout()->createBlock('tm_email/adminhtml_gateway_grid')
//            ->getCsv();
//
//        $this->_sendUploadResponse($fileName, $content);
//    }
//
//    public function exportXmlAction()
//    {
//        $fileName   = 'tm_email_gateway.xml';
//        $content    = $this->getLayout()->createBlock('tm_email/adminhtml_gateway_grid')
//            ->getXml();
//
//        $this->_sendUploadResponse($fileName, $content);
//    }
//
//    protected function _sendUploadResponse(
//        $fileName, $content, $contentType='application/octet-stream')
//    {
//        $response = $this->getResponse();
//        $response->setHeader('HTTP/1.1 200 OK','');
//        $response->setHeader('Pragma', 'public', true);
//        $response->setHeader('Cache-Control', 'must-revalidate, post-check=0, pre-check=0', true);
//        $response->setHeader('Content-Disposition', 'attachment; filename=' . $fileName);
//        $response->setHeader('Last-Modified', date('r'));
//        $response->setHeader('Accept-Ranges', 'bytes');
//        $response->setHeader('Content-Length', strlen($content));
//        $response->setHeader('Content-type', $contentType);
//        $response->setBody($content);
//        $response->sendResponse();
//        die;
//    }
}