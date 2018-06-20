<?php 
namespace Abc\Catlist\Block;
 
class CatList extends \Magento\Framework\View\Element\Template
{
	protected $_categoryHelper;
	protected $_categoryRepository;
	
	public function __construct(
         \Magento\Backend\Block\Template\Context $context,        
        \Magento\Catalog\Model\ResourceModel\Category\CollectionFactory $categoryCollectionFactory,
		\Magento\Catalog\Model\CategoryRepository $categoryRepository,
        \Magento\Catalog\Helper\Category $categoryHelper,
        array $data = []
    ){    
         $this->_categoryCollectionFactory = $categoryCollectionFactory;
        $this->_categoryHelper = $categoryHelper;
		$this->categoryRepository = $categoryRepository;
        parent::__construct($context, $data);
    }
	public function getDescendants($parent_category_id)
	{
		$categoryObj = $this->categoryRepository->get($parent_category_id);
		$subcategories = $categoryObj->getChildrenCategories();
		return $subcategories;
		/*foreach($subcategories as $subcategorie) {
			
			if($subcategorie->hasChildren()) {
				$childCategoryObj = $this->categoryRepository->get($subcategorie->getId());
				$childSubcategories = $childCategoryObj->getChildrenCategories();
				foreach($childSubcategories as $childSubcategorie) {
					echo '        ------> '.$childSubcategorie->getName().'';
				}
			}
			
		}*/
	}
	public function getCategoryCollection($isActive = true, $level = false, $sortBy = false, $pageSize = false)
    {
        $collection = $this->_categoryCollectionFactory->create();
        $collection->addAttributeToSelect('*');    
		$collection->addAttributeToSort('position');    
        
        // select only active categories
        if ($isActive) {
            $collection->addIsActiveFilter();
        }
                
        // select categories of certain level
        if ($level) {
            $collection->addLevelFilter($level);
        }
        
        // sort categories by some value
      /*  if ($sortBy) {
            $collection->addOrderField($sortBy);
        } */
        
        // select certain number of categories
        if ($pageSize) {
            $collection->setPageSize($pageSize); 
        }   

		 
        return $collection;
    }

	public function getStoreCategories($sorted = false, $asCollection = false, $toLoad = true)
    {
        return $this->_categoryHelper->getStoreCategories($sorted = false, $asCollection = false, $toLoad = true);
    }
}