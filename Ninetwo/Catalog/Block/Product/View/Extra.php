<?php

namespace Ninetwo\Catalog\Block\Product\View;

use Magento\Catalog\Block\Product\AbstractProduct;

class Extra extends AbstractProduct
{
	
	/**
     * Returns action url for contact form
     *
     * @return string
     */
    public function getUploadFormAction()
    {
		$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
		
		$product = $objectManager->get('Magento\Framework\Registry')->registry('current_product');//get current product
		
		$catalogSession = $objectManager->create('\Magento\Catalog\Model\Session');
        
		$catalogSession->setPhotoProductId($product->getId());
		
		return $this->getUrl('photoupload/photoupload/index', ['_secure' => true,'product_id' => $product->getId()]);
    }
	
}