<?php
/**
 * Copyright © 2015 Ninetwo . All rights reserved.
 */
namespace Ninetwo\Photoupload\Block\Photoupload;
use Ninetwo\Photoupload\Block\BaseBlock;
class Index extends BaseBlock
{
	
	/**
     * Returns action url for contact form
     *
     * @return string
     */
    public function getFormAction()
    {
        return $this->getUrl('photoupload/photoupload/post', ['_secure' => true]);
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
