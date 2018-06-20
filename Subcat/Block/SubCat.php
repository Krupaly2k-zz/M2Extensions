<?php 
namespace Abc\Subcat\Block;
 
class SubCat extends \Magento\Framework\View\Element\Template
{
	protected $_categoryHelper;
	protected $_categoryRepository;
	
	public function __construct(
         \Magento\Backend\Block\Template\Context $context,        
        \Magento\Catalog\Model\ResourceModel\Category\CollectionFactory $categoryCollectionFactory,
        \Magento\Catalog\Helper\Category $categoryHelper,
        array $data = []
    ){    
         $this->_categoryCollectionFactory = $categoryCollectionFactory;
        $this->_categoryHelper = $categoryHelper;
        parent::__construct($context, $data);
    }

	public function getDescendants($category, $levels = 2)
{
    if ((int)$levels < 1) {
        $levels = 1;
    }
    $collection = $this->categoryCollectionFactory->create()
          ->addPathsFilter($category->getPath().'/') 
          ->addLevelFilter($category->getLevel() + $levels);
    return $collection;
}
	public function getStoreCategories($sorted = false, $asCollection = false, $toLoad = true)
    {
        return $this->_categoryHelper->getStoreCategories($sorted = false, $asCollection = false, $toLoad = true);
    }
}