<?php 
namespace Abc\Subcat\Block;
 
class SubCat extends \Magento\Framework\View\Element\Template
{
	protected $_categoryHelper;
	protected $_categoryRepository;
	
	 protected $_productCollectionFactory;
	public function __construct(
       \Magento\Backend\Block\Template\Context $context,        
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,        
        array $data = []
        
    ){    
       $this->_productCollectionFactory = $productCollectionFactory;    
        parent::__construct($context, $data);
    }
	 public function getProductCollection()
		{
			$collection = $this->_productCollectionFactory->create();
			$collection->addAttributeToSelect('*');
			
			
			return $collection;
		}
}