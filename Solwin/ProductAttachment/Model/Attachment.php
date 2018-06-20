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
namespace Solwin\ProductAttachment\Model;

use Magento\Framework\Model\AbstractModel;

/**
 * @method Attachment setTitle($title)
 * @method Attachment setAttachmentFile($attachmentFile)
 * @method Attachment setCustomerGroup($customerGroup)
 * @method Attachment setStatus($status)
 * @method mixed getTitle()
 * @method mixed getAttachmentFile()
 * @method mixed getCustomerGroup()
 * @method mixed getStatus()
 * @method Attachment setCreatedAt(\string $createdAt)
 * @method string getCreatedAt()
 * @method Attachment setUpdatedAt(\string $updatedAt)
 * @method string getUpdatedAt()
 */
class Attachment extends AbstractModel
{
    /**
     * Cache tag
     * 
     * @var string
     */
    const CACHE_TAG = 'solwin_productattachment_attachment';

    /**
     * Cache tag
     * 
     * @var string
     */
    protected $_cacheTag = 'solwin_productattachment_attachment';

    /**
     * Event prefix
     * 
     * @var string
     */
    protected $_eventPrefix = 'solwin_productattachment_attachment';


    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Solwin\ProductAttachment\Model\ResourceModel\Attachment');
    }

    /**
     * Get identities
     *
     * @return array
     */
    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }

    /**
     * get entity default values
     *
     * @return array
     */
    public function getDefaultValues()
    {
        $values = [];
        $values['status'] = '1';
        return $values;
    }
}
