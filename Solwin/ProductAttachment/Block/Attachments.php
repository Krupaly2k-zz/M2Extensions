<?php
/**
 * Solwin Infotech
 * Solwin Product Attachments
 * 
 * @category   Solwin
 * @package    Solwin_ProductAttachment
 * @copyright  Copyright © 2006-2016 Solwin (https://www.solwininfotech.com)
 * @license    https://www.solwininfotech.com/magento-extension-license/
 */
namespace Solwin\ProductAttachment\Block;

use Magento\Framework\View\Element\Template;
use Solwin\ProductAttachment\Model\AttachmentFactory;

class Attachments extends Template
{

    /**
     * @var Attachment
     */
    protected $_attachmentFactory;

    /**
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry;
    
    /**
     * @var \Magento\Customer\Model\Session
     */
    protected $_customerSession;

    
    /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Framework\Registry                      $coreRegistry
     * @param Attachment                                         $attachment
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Framework\Registry $coreRegistry,
        AttachmentFactory $attachmentFactory
    ) {
        $this->_attachmentFactory = $attachmentFactory;
        $this->_coreRegistry = $coreRegistry;
        $this->_customerSession = $customerSession;

        parent::__construct($context);

        $this->setTabTitle();
    }

    /**
     * Set tab title
     *
     * @return void
     */
    public function setTabTitle() {
        $this->setTitle(__('Attachments'));
    }
    
    public function getCurrentCustomerGroupId() {

        return $this->_customerSession->getCustomerGroupId();
    }
    
    public function getIcon($image) {
        echo $this->getViewFileUrl('Solwin_ProductAttachment::images/'.$image); 
    }
    
    /**
     * Return Attachments
     *
     * @return mixed
     */
    public function getSingleAttachmentCollection($attachmentId) {
        
        $currentCustomerGroupId = $this->getCurrentCustomerGroupId();
        
        $collection = $this->_attachmentFactory->create()
                ->getCollection()
                ->addFieldToFilter('attachment_id', $attachmentId)
                ->addFieldToFilter('status', 1)
                ->addFieldToFilter('customer_group', [
                    ['finset'=> $currentCustomerGroupId]])
                ->addStoreFilter($this->_storeManager->getStore()->getId())
                ->getFirstItem();
        
       return $collection;
        
        
        
    }
    
     public function getMediaUrl() {
        return $this->_storeManager->getStore()
                ->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
    }

    /**
     * Return current product instance
     *
     * @return \Magento\Catalog\Model\Product
     */
    public function getProduct() {
        return $this->_coreRegistry->registry('product');
    }

    /* get store base url */

    public function getBaseUrl() {
        return $this->_storeManager
                ->getStore()
                ->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_WEB);
    }

    

}