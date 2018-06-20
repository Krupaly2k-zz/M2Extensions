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

class Status implements ArrayInterface
{
    const ENABLE = 1;
    const DISABLE = 2;


    /**
     * to option array
     *
     * @return array
     */
    public function toOptionArray()
    {
        $options = [
            [
                'value' => self::ENABLE,
                'label' => __('Enable')
            ],
            [
                'value' => self::DISABLE,
                'label' => __('Disable')
            ],
        ];
        return $options;

    }
}
