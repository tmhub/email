<?php

class TM_Email_Adminhtml_Queue_QueueController extends TM_Email_Controller_Adminhtml_Email_Abstract
{
    protected $resource = 'templates_master/tm_email/queue';

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

    public function editAction()
    {
        $_id = $this->getRequest()->getParam('id', 0);

        $model = Mage::getModel('tm_email/queue_queue')->load($_id);
        $id = $model->getId();

        if (!$id && 0 !== $_id) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('tm_email')->__('Item does not exist')
            );
            $this->_redirect('*/*/');
        }

        $data = Mage::getSingleton('adminhtml/session')->getFormData(true);
        if (!empty($data)) {
            $model->setData($data);
        }

        Mage::register('tm_email_queue_data', $model);

        $this->loadLayout();
        $this->_setActiveMenu('tm_email/queue_queue');
        $this->renderLayout();
    }

    protected function _saveAction(array $data)
    {
        $model = Mage::getModel('tm_email/queue_queue');
        if (empty($data['queue_id'])) {
            unset($data['queue_id']);
        }
        $model->addData($data);
        $model->save();
        return $model;
    }

    protected function _deleteAction($id)
    {
        return Mage::getModel('tm_email/queue_queue')->setId($id)->delete();
    }

    public function massDeleteAction()
    {
        $_ids = $this->getRequest()->getParam('tm_email');
        if (!is_array($_ids)) {
            Mage::getSingleton('adminhtml/session')
                ->addError(Mage::helper('adminhtml')->__('Please select item(s)'));
        } else {
            try {
                foreach ($_ids as $_id) {
                    $model = Mage::getModel('tm_email/queue_queue')->load($_id);
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
        $fileName   = 'tm_email_queue.csv';
        $content    = $this->getLayout()
            ->createBlock('tm_email/adminhtml_queue_queue_grid')
            ->getCsv();

        $this->_prepareDownloadResponse($fileName, $content);
    }

    public function exportXmlAction()
    {
        $fileName   = 'tm_email_queue.xml';
        $content    = $this->getLayout()
            ->createBlock('tm_email/adminhtml_queue_queue_grid')
            ->getXml();

        $this->_prepareDownloadResponse($fileName, $content);
    }
}
