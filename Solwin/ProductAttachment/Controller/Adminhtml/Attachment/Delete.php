<?php
/**
 * Solwin Infotech
 * Solwin ProductAttachment Extension
 * 
 * @category   Solwin
 * @package    Solwin_ProductAttachment
 * @copyright  Copyright © 2006-2016 Solwin (https://www.solwininfotech.com)
 * @license    https://www.solwininfotech.com/magento-extension-license/
 */
namespace Solwin\ProductAttachment\Controller\Adminhtml\Attachment;

use Solwin\ProductAttachment\Controller\Adminhtml\Attachment as AttachmentController;

class Delete extends AttachmentController
{
    
    /**
     * is action allowed
     *
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Solwin_ProductAttachment::attachment');
    }
    
    /**
     * execute action
     *
     * @return \Magento\Backend\Model\View\Result\Redirect
     */
    public function execute()
    {
        $resultRedirect = $this->_resultRedirectFactory->create();
        $id = $this->getRequest()->getParam('attachment_id');
        if ($id) {
            $title = "";
            try {
                /** @var \Solwin\ProductAttachment\Model\Attachment $attachment */
                $attachment = $this->_attachmentFactory->create();
                $attachment->load($id);
                $title = $attachment->getTitle();
                $attachment->delete();
                $this->messageManager->addSuccess(__('The Attachment has been deleted.'));
                $this->_eventManager->dispatch(
                    'adminhtml_solwin_productattachment_attachment_on_delete',
                    ['title' => $title, 'status' => 'success']
                );
                $resultRedirect->setPath('solwin_productattachment/*/');
                return $resultRedirect;
            } catch (\Exception $e) {
                $this->_eventManager->dispatch(
                    'adminhtml_solwin_productattachment_attachment_on_delete',
                    ['title' => $title, 'status' => 'fail']
                );
                // display error message
                $this->messageManager->addError($e->getMessage());
                // go back to edit form
                $resultRedirect->setPath('solwin_productattachment/*/edit', ['attachment_id' => $id]);
                return $resultRedirect;
            }
        }
        // display error message
        $this->messageManager->addError(__('Attachment to delete was not found.'));
        // go to grid
        $resultRedirect->setPath('solwin_productattachment/*/');
        return $resultRedirect;
    }
}
