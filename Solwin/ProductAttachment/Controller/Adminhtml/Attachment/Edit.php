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

use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\Session as BackendSession;
use Magento\Backend\Model\View\Result\RedirectFactory;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Registry;
use Magento\Framework\View\Result\PageFactory;
use Solwin\ProductAttachment\Controller\Adminhtml\Attachment as AttachmentController;
use Solwin\ProductAttachment\Model\AttachmentFactory;

class Edit extends AttachmentController
{
    /**
     * Backend session
     * 
     * @var BackendSession
     */
    protected $_backendSession;

    /**
     * Page factory
     * 
     * @var PageFactory
     */
    protected $_resultPageFactory;

    /**
     * Result JSON factory
     * 
     * @var JsonFactory
     */
    protected $_resultJsonFactory;

    /**
     * constructor
     * 
     * @param BackendSession $backendSession
     * @param PageFactory $resultPageFactory
     * @param JsonFactory $resultJsonFactory
     * @param AttachmentFactory $attachmentFactory
     * @param Registry $registry
     * @param RedirectFactory $resultRedirectFactory
     * @param Context $context
     */
    public function __construct( 
        PageFactory $resultPageFactory,
        JsonFactory $resultJsonFactory,
        AttachmentFactory $attachmentFactory,
        Registry $registry, 
        Context $context
    )
    {
        $this->_backendSession    = $context->getSession();
        $this->_resultPageFactory = $resultPageFactory;
        $this->_resultJsonFactory = $resultJsonFactory;
        parent::__construct($attachmentFactory, $registry, $context);
    }

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
     * @return \Magento\Backend\Model\View\Result\Page|\Magento\Backend\Model\View\Result\Redirect|\Magento\Framework\View\Result\Page
     */
    public function execute()
    {
        $id = $this->getRequest()->getParam('attachment_id');
        /** @var \Solwin\ProductAttachment\Model\Attachment $attachment */
        $attachment = $this->_initAttachment();
        /** @var \Magento\Backend\Model\View\Result\Page|\Magento\Framework\View\Result\Page $resultPage */
        $resultPage = $this->_resultPageFactory->create();
        $resultPage->setActiveMenu('Solwin_ProductAttachment::attachment');
        $resultPage->getConfig()->getTitle()->set(__('Attachments'));
        if ($id) {
            $attachment->load($id);
            if (!$attachment->getId()) {
                $this->messageManager->addError(__('This Attachment no longer exists.'));
                $resultRedirect = $this->_resultRedirectFactory->create();
                $resultRedirect->setPath(
                    'solwin_productattachment/*/edit',
                    [
                        'attachment_id' => $attachment->getId(),
                        '_current' => true
                    ]
                );
                return $resultRedirect;
            }
        }
        $title = $attachment->getId() ? $attachment->getTitle() : __('New Attachment');
        $resultPage->getConfig()->getTitle()->prepend($title);
        $data = $this->_backendSession->getData('solwin_productattachment_attachment_data', true);
        if (!empty($data)) {
            $attachment->setData($data);
        }
        return $resultPage;
    }
}
