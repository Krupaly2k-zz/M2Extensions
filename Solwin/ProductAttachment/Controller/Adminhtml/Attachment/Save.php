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
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Backend\Model\View\Result\RedirectFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Registry;
use Solwin\ProductAttachment\Controller\Adminhtml\Attachment as AttachmentController;
use Solwin\ProductAttachment\Model\AttachmentFactory;
use Solwin\ProductAttachment\Model\Attachment\File as AttachmentFile;
use Solwin\ProductAttachment\Model\Upload;

class Save extends AttachmentController
{
    /**
     * Upload model
     * 
     * @var Upload
     */
    protected $_uploadModel;

    /**
     * File model
     * 
     * @var AttachmentFile
     */
    protected $_fileModel;

    /**
     * Backend session
     * 
     * @var BackendSession
     */
    protected $_backendSession;

    /**
     * constructor
     * 
     * @param Upload $uploadModel
     * @param AttachmentFile $fileModel
     * @param BackendSession $backendSession
     * @param AttachmentFactory $attachmentFactory
     * @param Registry $registry
     * @param RedirectFactory $resultRedirectFactory
     * @param Context $context
     */
    public function __construct(
        Upload $uploadModel,
        AttachmentFile $fileModel, 
        AttachmentFactory $attachmentFactory,
        Registry $registry, 
        Context $context
    )
    {
        $this->_uploadModel    = $uploadModel;
        $this->_fileModel      = $fileModel;
        $this->_backendSession = $context->getSession();
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
     * run the action
     *
     * @return Redirect
     */
    public function execute()
    {
        $data = $this->getRequest()->getPost('attachment');
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($data) {
            $data = $this->_filterData($data);
            $attachment = $this->_initAttachment();
            $attachment->setData($data);
            $attachmentFile = $this->_uploadModel->uploadFileAndGetName('attachment_file', $this->_fileModel->getBaseDir(), $data);
            $attachment->setAttachmentFile($attachmentFile);
            $this->_eventManager->dispatch(
                'solwin_productattachment_attachment_prepare_save',
                [
                    'attachment' => $attachment,
                    'request' => $this->getRequest()
                ]
            );
            try {
                $attachment->save();
                $this->messageManager->addSuccess(__('The Attachment has been saved.'));
                $this->_backendSession->setSolwinProductAttachmentAttachmentData(false);
                if ($this->getRequest()->getParam('back')) {
                    $resultRedirect->setPath(
                        'solwin_productattachment/*/edit',
                        [
                            'attachment_id' => $attachment->getId(),
                            '_current' => true
                        ]
                    );
                    return $resultRedirect;
                }
                $resultRedirect->setPath('solwin_productattachment/*/');
                return $resultRedirect;
            } catch (LocalizedException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\RuntimeException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addException($e, __('Something went wrong while saving the Attachment.'));
            }
            $this->_getSession()->setSolwinProductAttachmentAttachmentData($data);
            $resultRedirect->setPath(
                'solwin_productattachment/*/edit',
                [
                    'attachment_id' => $attachment->getId(),
                    '_current' => true
                ]
            );
            return $resultRedirect;
        }
        $resultRedirect->setPath('solwin_productattachment/*/');
        return $resultRedirect;
    }

    /**
     * filter values
     *
     * @param array $data
     * @return array
     */
    protected function _filterData($data)
    {
        if (isset($data['customer_group'])) {
            if (is_array($data['customer_group'])) {
                $data['customer_group'] = implode(',', $data['customer_group']);
            }
        }
        return $data;
    }
}
