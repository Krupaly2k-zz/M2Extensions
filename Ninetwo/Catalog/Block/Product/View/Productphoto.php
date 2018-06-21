<?php

namespace Ninetwo\Catalog\Block\Product\View;

use Magento\Catalog\Block\Product\AbstractProduct;

class Productphoto extends AbstractProduct
{
	
	protected $_collectionFactory;
	
	 /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry;
	
	 /**
     * Store manager
     *
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;
	
	
	public function __construct(
		\Magento\Catalog\Block\Product\Context $context, 
		\Ninetwo\Photoupload\Model\ResourceModel\Photoupload\Collection $collectionFactory,
		\Magento\Store\Model\StoreManagerInterface $storeManager,
		array $data = []
	)
    {
		$this->_coreRegistry = $context->getRegistry();
		$this->_collectionFactory = $collectionFactory;
		$this->_storeManager = $storeManager;
        parent::__construct($context, $data);
    }
	
	/**
     * Returns action url for contact form
     *
     * @return string
     */
    public function getUploadFormAction()
    {
        return $this->getUrl('photoupload/photoupload/index', ['_secure' => true]);
    }
	
	public function getRecentPhotoForProductView(){
		
		$product = $this->_coreRegistry->registry('current_product');
		
		$recentPhotos = $this->_collectionFactory
						->addFieldToFilter('product_id',$product->getId())
						->addFieldToFilter('image',array('neq' => NULL))
						->addFieldToFilter('status',1)
						->setCurPage(1)
						->setPageSize(4)
						->setOrder('created_datetime','DESC')
						;			
		
		return $recentPhotos;
		
	}
	
	/**
     * @return string
     */
    public function getPhotoUrl($photoPath)
    {
        
		$url = $this->_storeManager->getStore()->getBaseUrl(
			\Magento\Framework\UrlInterface::URL_TYPE_MEDIA
		) . $photoPath;

        return $url;
    }
	
	public function getCollectionSize(){
		$product = $this->_coreRegistry->registry('current_product');
		
		$allPhotos = $this->_collectionFactory
						->addFieldToFilter('product_id',$product->getId())
						->addFieldToFilter('status',1)
						->addFieldToFilter('image',array('neq' => NULL))
						;			
		
		return $allPhotos->getSize();
	}
	
	/*****
		View all products photos url
	****/
	public function getViewAllUrl(){
		
        return $this->getUrl('photoupload/photoupload/allphotos', ['_secure' => true,'product_id' => $this->getPhotoProductId()]);
	}
	
	/**
     * Product id to upload photo
     *
     * @return int
     */
	public function getPhotoProductId(){
		
		$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
		
		$catalogSession = $objectManager->create('\Magento\Catalog\Model\Session');
        
		$postProductId = $this->getRequest()->getParam('product_id');
		
		$photoProductId = ($postProductId) ? $postProductId : $catalogSession->getPhotoProductId();
		
		return $photoProductId;
		
	}
}