<?php 
namespace Abc\Sidebarpromo\Block;
 
class SidebarPromo extends \Magento\Framework\View\Element\Template
{
	
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
			$collection->setPageSize(1);
			$collection->addAttributeToFilter(
            'special_price',
            ['gt'=>0], 'left'
        )->addAttributeToFilter(
            'special_from_date',['or' => [ 0 => ['date' => true, 
                                                'to' => date('Y-m-d',time()).' 23:59:59'],
                                          1 => ['is' => new \Zend_Db_Expr(
                                             'null'
                                         )],]], 'left'
        )->addAttributeToFilter(
            'special_to_date',  ['or' => [ 0 => ['date' => true,
                                               'from' => date('Y-m-d',time()).' 00:00:00'],
                                         1 => ['is' => new \Zend_Db_Expr(
                                             'null'
                                         )],]], 'left'
        );
		$collection->getSelect()->orderRand();
			return $collection;
		}
		public function getPro($prid)
		{
			$collection = $this->_productCollectionFactory->create();
			$collection->addAttributeToSelect('*');
			$collection->addAttributeToFilter('entity_id',$prid);
			$collection->load();
			
			
	
			return $collection;
			
		}
}