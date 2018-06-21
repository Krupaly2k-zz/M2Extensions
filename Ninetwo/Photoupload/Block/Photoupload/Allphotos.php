<?php
/**
 * Copyright © 2015 Ninetwo . All rights reserved.
 */
namespace Ninetwo\Photoupload\Block\Photoupload;
use Ninetwo\Photoupload\Block\BaseBlock;
use Magento\Framework\App\Filesystem\DirectoryList;
class Allphotos extends BaseBlock
{
	
	protected $_collectionFactory;
	protected $_filesystem;
	protected $_directory;
	protected $_imageFactory;
	protected $_productRepository;
	
	/**
     * Store manager
     *
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;
	
	public function __construct( 
		\Ninetwo\Photoupload\Block\Context $context,
		\Ninetwo\Photoupload\Model\ResourceModel\Photoupload\Collection $collectionFactory,
		\Magento\Store\Model\StoreManagerInterface $storeManager,
		\Magento\Framework\Filesystem $filesystem,
		\Magento\Catalog\Model\ProductRepository $productRepository,
		\Magento\Framework\Image\AdapterFactory $imageFactory
	)
    {
		$this->_collectionFactory = $collectionFactory;
		$this->_storeManager = $storeManager;
		$this->_filesystem = $filesystem;
		// $this->_directory = $filesystem->getDirectoryWrite(DirectoryList::MEDIA);
		$this->_imageFactory = $imageFactory;
		$this->_productRepository = $productRepository;
		parent::__construct($context);
    }
	
	public function getAllPhotoForProduct(){
		
		$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
		
		$catalogSession = $objectManager->create('\Magento\Catalog\Model\Session');
        
		$postProductId = $this->getRequest()->getParam('product_id');
		
		$photoProductId = ($postProductId) ? $postProductId : $catalogSession->getViewAllPhotoProductId();
		
		$recentPhotos = $this->_collectionFactory
						->addFieldToFilter('product_id',$photoProductId)
						->addFieldToFilter('image',array('neq' => NULL))
						->addFieldToFilter('status',1)
						->setOrder('created_datetime','DESC')
						;			
		return $recentPhotos;
	}
	
	/****
		Get product all images
		@rerurn product all images objecct
	***/
	public function getProductImages(){
		$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
		$postProductId = $this->getRequest()->getParam('product_id');
		$product = $objectManager->create('Magento\Catalog\Model\Product')->load($postProductId);        
		$images = $product->getMediaGalleryImages();
	
		return $images;
	}
	/*****
		Product images tab titles
		@return array tab title
	****/
	public function getTabsTitle(){
		
		return array(
					'Product Images', 'Customer Photos'
				);
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
	
	public function getCurrentProductUrl(){
		$productId = $this->getRequest()->getParam('product_id');
		
		$product = $this->_productRepository->getById($productId);
		
		return $product->getUrlModel()->getUrl($product);
	}
	
	/*****
		Resized  Image for Aspect Ratio
	******/
	
	public function getResizedImage($image){
		
		$absPath = $this->_filesystem->getDirectoryRead(DirectoryList::MEDIA)->getAbsolutePath().$image;

		$imageResized = $this->_filesystem->getDirectoryRead(DirectoryList::MEDIA)->getAbsolutePath('resized/').$image;

		$resizedURL = $this->_storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA).'resized/'.$image;
		
		if(is_file($imageResized)) {
			return $resizedURL;
		}

		$imageResize = $this->_imageFactory->create();

		$imageResize->open($absPath);

		$imageResize->constrainOnly(TRUE);

		$imageResize->keepTransparency(TRUE);

		$imageResize->keepFrame(FALSE);

		$imageResize->keepAspectRatio(true);

		$imageResize->resize(700,500);


		$dest = $imageResized ;

		$imageResize->save($dest);


		$resizedURL = $this->_storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA).'resized/'.$image;

		return $resizedURL;
	}
	/*******
		Resized product images
	******/
	public function getResizedProductImage($imageUrl){
		
		$productImagePart = explode('pub/media/',$imageUrl);
		$image = $productImagePart[1];
		
		$absPath = $this->_filesystem->getDirectoryRead(DirectoryList::MEDIA)->getAbsolutePath().$image;

		$imageResized = $this->_filesystem->getDirectoryRead(DirectoryList::MEDIA)->getAbsolutePath('resized/productimage/').$image;

		$resizedURL = $this->_storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA).'resized/productimage/'.$image;
		
		if(is_file($imageResized)) {
			return $resizedURL;
		}

		$imageResize = $this->_imageFactory->create();

		$imageResize->open($absPath);

		$imageResize->constrainOnly(TRUE);

		$imageResize->keepTransparency(TRUE);

		$imageResize->keepFrame(FALSE);

		$imageResize->keepAspectRatio(true);

		$imageResize->resize(700,450);


		$dest = $imageResized ;

		$imageResize->save($dest);


		$resizedURL = $this->_storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA).'resized/productimage/'.$image;

		return $resizedURL;
	}
}
