<?php
/**
 * Solwin Infotech
 * Solwin ProductAttachment Extension
 * 
 * @category   Solwin
 * @package    Solwin_ProductAttachment
 * @copyright  Copyright © 2006-2016 Solwin (https://www.solwininfotech.com)
 * @license    https://www.solwininfotech.com/magento-extension-license/
 */
namespace Solwin\ProductAttachment\Model\Attachment\Source;

use Magento\Framework\Option\ArrayInterface;

class CustomerGroup implements ArrayInterface
{
    
    
    protected $_customerGroupFactory;

    public function __construct(
        \Magento\Customer\Model\GroupFactory $customerGroupFactory
    ) {
        $this->_customerGroupFactory = $customerGroupFactory;
    }

    public function toOptionArray() {
        $group = [];
        $groupCollection = $this->_customerGroupFactory->create()->getCollection();
        foreach ($groupCollection as $customerGroup) {
            
            $group[] = [
                'value' => $customerGroup->getCustomerGroupId(),
                'label' => $customerGroup->getCustomerGroupCode()
            ];
            
        }
        return $group;
    }
}
