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
namespace Solwin\ProductAttachment\Controller\Adminhtml;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\RedirectFactory;
use Magento\Framework\Registry;
use Solwin\ProductAttachment\Model\AttachmentFactory;

abstract class Attachment extends Action
{
    /**
     * Attachment Factory
     * 
     * @var AttachmentFactory
     */
    protected $_attachmentFactory;

    /**
     * Core registry
     * 
     * @var Registry
     */
    protected $_coreRegistry;

    /**
     * Result redirect factory
     * 
     * @var RedirectFactory
     */
    protected $_resultRedirectFactory;

    /**
     * constructor
     * 
     * @param AttachmentFactory $attachmentFactory
     * @param Registry $coreRegistry
     * @param RedirectFactory $resultRedirectFactory
     * @param Context $context
     */
    public function __construct(
        AttachmentFactory $attachmentFactory,
        Registry $coreRegistry, 
        Context $context
    )
    {
        $this->_attachmentFactory     = $attachmentFactory;
        $this->_coreRegistry          = $coreRegistry;  
        parent::__construct($context);
        $this->_resultRedirectFactory = $context->getResultRedirectFactory();
    }

    /**
     * Init Attachment
     *
     * @return \Solwin\ProductAttachment\Model\Attachment
     */
    protected function _initAttachment()
    {
        $attachmentId  = (int) $this->getRequest()->getParam('attachment_id');
        /** @var \Solwin\ProductAttachment\Model\Attachment $attachment */
        $attachment    = $this->_attachmentFactory->create();
        if ($attachmentId) {
            $attachment->load($attachmentId);
        }
        $this->_coreRegistry->register('solwin_productattachment_attachment', $attachment);
        return $attachment;
    }
}
