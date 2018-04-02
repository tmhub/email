<?php

class TM_Email_Adminhtml_History_TransportController extends TM_Email_Controller_Adminhtml_Email_Abstract
{

    protected $breadcrumbLabel = 'History';
    protected $breadcrumbTitle = 'Transport';

    protected $resource = 'templates_master/tm_email/gateway';

    public function viewAction()
    {
        $this->loadLayout();
        $this->renderLayout();
    }

    protected function _deleteAction($id)
    {
        return Mage::getModel('tm_email/gateway_transport_history')->setId($id)->delete();
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
                    $model = Mage::getModel('tm_email/gateway_transport_history')->load($_id);
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
}
