<?php
namespace Ninetwo\Photoupload\Block\Adminhtml\Photoupload\Grid\Renderer;


use Magento\Backend\Block\Widget\Grid\Column\Renderer\AbstractRenderer;
use Magento\Framework\DataObject;
use Magento\Store\Model\StoreManagerInterface;

class Product extends AbstractRenderer
{
    private $_storeManager;
    
	private $_productRepository;
    /**
     * @param \Magento\Backend\Block\Context $context
     * @param array $data
     */
    public function __construct(
		\Magento\Backend\Block\Context $context, 
		StoreManagerInterface $storemanager, 
		\Magento\Catalog\Model\ProductRepository $productRepository,
		array $data = []
		)
    {
        $this->_storeManager = $storemanager;
		$this->_productRepository = $productRepository;
        parent::__construct($context, $data);
        $this->_authorization = $context->getAuthorization();
    }
    /**
     * Renders grid column
     *
     * @param Object $row
     * @return  string
     */
    public function render(DataObject $row)
    {
        $mediaDirectory = $this->_storeManager->getStore()->getBaseUrl(
            \Magento\Framework\UrlInterface::URL_TYPE_MEDIA
        );
		if($this->_getValue($row)){
			$product = $this->_productRepository->getById($this->_getValue($row));
			return '<a target="_blank" href="'.$product->getUrlModel()->getUrl($product).'" width="50">'.$product->getName().'</a>';
		}
		return '';
    }
}
