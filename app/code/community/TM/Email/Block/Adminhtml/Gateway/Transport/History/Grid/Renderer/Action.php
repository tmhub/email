<?php

class TM_Email_Block_Adminhtml_Gateway_Transport_History_Grid_Renderer_Action
    extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    /**
     * Renders grid column
     *
     * @param   Varien_Object $row
     * @return  string
     */
    public function render(Varien_Object $row)
    {
        $links = array();

        $title = Mage::helper('tm_email')->__('View Email');
        $script = '
                var changesUrl = \'' . $this->getUrl('*/*/preview', array('id' => $row->getId())) .  '\';
                Dialog.info(null, {
                    draggable:true,
                    resizable:true,
                    closable:true,
                    className: \'magento\',
                    windowClassName:\'popup-window\',
                    title: \'' . $title . '\',
                    top:50,
                    width:1100,
                    height:960,
                    zIndex:1000,
                    recenterAuto:false,
                    hideEffect:Element.hide,
                    showEffect:Element.show,
                    id:\'grid-action-changes\',
                   // onClose: this.closeDialogWindow.bind(this)
                });
                new Ajax.Updater(\'modal_dialog_message\', changesUrl, {evalScripts: true});
                ';

        $links[] = sprintf(
            '<a href="javascript:void(0)" onclick="%s">%s</a>',
            $script,
            $title
        );

        return implode(' | ', $links);
    }
}
