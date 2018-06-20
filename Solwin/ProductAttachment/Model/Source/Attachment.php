<?php
/**
 * Solwin Infotech
 * Solwin Product Label Extension
 *
 * @category   Solwin
 * @package    Solwin_ProductAttachment
 * @copyright  Copyright © 2006-2016 Solwin (https://www.solwininfotech.com)
 * @license    https://www.solwininfotech.com/magento-extension-license/ 
 */
?>
<?php

namespace Solwin\ProductAttachment\Model\Source;

use Solwin\ProductAttachment\Model\AttachmentFactory;

class Attachment extends \Magento\Eav\Model\Entity\Attribute\Source\AbstractSource
{

    protected $_modelAttachmentFactory;
    protected $_entityAttributeFactory;

    public function __construct(
        AttachmentFactory $modelAttachmentFactory,
        \Magento\Eav\Model\ResourceModel\Entity\AttributeFactory $entityAttributeFactory
    ) {
        $this->_modelAttachmentFactory = $modelAttachmentFactory;
        $this->_entityAttributeFactory = $entityAttributeFactory;
    }

    public function getAllOptions() {
        if (!$this->_options) {
            $collection = $this->_modelAttachmentFactory->create()->getCollection();

            foreach ($collection as $val) {
                $this->_options[] = [
                    'value' => $val->getAttachmentId(),
                    'label' => $val->getTitle(),
                ];
            }
        }
        return $this->_options;
    }

    /**
     * Retrieve flat column definition
     *
     * @return array
     */
    public function getFlatColumns() {
        $attributeCode = $this->getAttribute()->getAttributeCode();

        return [
            $attributeCode => [
                'unsigned' => true,
                'default' => null,
                'extra' => null,
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                'nullable' => true,
                'comment' => $attributeCode . ' tax column',
            ],
        ];
    }

    /**
     * Retrieve Select for update attribute value in flat table
     *
     * @param   int $store
     * @return  \Magento\Framework\DB\Select|null
     */
    public function getFlatUpdateSelect($store) {
        return $this->_entityAttributeFactory->create()
                ->getFlatUpdateSelect($this->getAttribute(), $store);
    }

}
