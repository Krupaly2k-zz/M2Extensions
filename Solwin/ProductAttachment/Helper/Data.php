<?php
/**
 * Solwin Infotech
 * Solwin Product Attachments
 * 
 * @category   Solwin
 * @package    Solwin_ProductAttachment
 * @copyright  Copyright © 2006-2016 Solwin (https://www.solwininfotech.com)
 * @license    https://www.solwininfotech.com/magento-extension-license/
 */
namespace Solwin\ProductAttachment\Helper;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{

    /**
     * Get System configuration option values
     */
    public function getConfigValue($value = '') {
        return $this->scopeConfig
                ->getValue(
                        $value,
                        \Magento\Store\Model\ScopeInterface::SCOPE_STORE
                        );
    }
    
}