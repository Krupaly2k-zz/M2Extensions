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
namespace Solwin\ProductAttachment\Model\ResourceModel;

use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Magento\Framework\Model\ResourceModel\Db\Context;
use Magento\Framework\Stdlib\DateTime\DateTime;

class Attachment extends AbstractDb
{
    /**
     * Date model
     * 
     * @var DateTime
     */
    protected $_date;

    /**
     * constructor
     * 
     * @param DateTime $date
     * @param Context $context
     */
    public function __construct(
        DateTime $date,
        Context $context
    )
    {
        $this->_date = $date;
        parent::__construct($context);
    }


    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('solwin_productattachment_attachment', 'attachment_id');
    }

    /**
     * Retrieves Attachment Title from DB by passed id.
     *
     * @param string $id
     * @return string|bool
     */
    public function getAttachmentTitleById($id)
    {
        $adapter = $this->getConnection();
        $select = $adapter->select()
            ->from($this->getMainTable(), 'title')
            ->where('attachment_id = :attachment_id');
        $binds = ['attachment_id' => (int)$id];
        return $adapter->fetchOne($select, $binds);
    }
    /**
     * before save callback
     *
     * @param AbstractModel|\Solwin\ProductAttachment\Model\Attachment $object
     * @return $this
     */
    protected function _beforeSave(AbstractModel $object)
    {
        $object->setUpdatedAt($this->_date->date());
        if ($object->isObjectNew()) {
            $object->setCreatedAt($this->_date->date());
        }
        return parent::_beforeSave($object);
    }
    
    /**
     * Process post data before deleting
     *
     * @param \Magento\Framework\Model\AbstractModel $object
     * @return $this
     */
    protected function _beforeDelete(
        \Magento\Framework\Model\AbstractModel $object
    ) {
        $condition = ['attachment_id = ?' => (int)$object->getId()];

        $this->getConnection()
                ->delete(
                        $this->getTable('solwin_productattachment_store'),
                        $condition
                        );
       
        return parent::_beforeDelete($object);
    }
    /**
     * Assign post to store views, categories, related posts, etc.
     *
     * @param \Magento\Framework\Model\AbstractModel $object
     * @return $this
     */
    protected function _afterSave(
        \Magento\Framework\Model\AbstractModel $object
    ) {
        $oldIds = $this->lookupStoreIds($object->getId());
        $newIds = (array)$object->getStores();
        
        if (empty($newIds)) {
            $newIds = (array)$object->getStoreId();
        }
        $this->_updateLinks($object, $newIds, $oldIds,
                'solwin_productattachment_store', 'store_id');

        return parent::_afterSave($object);
    }
    
    /**
     * Update post connections
     * @param  \Magento\Framework\Model\AbstractModel $object
     * @param  Array $newRelatedIds
     * @param  Array $oldRelatedIds
     * @param  String $tableName
     * @param  String  $field
     * @return void
     */
    protected function _updateLinks(
        \Magento\Framework\Model\AbstractModel $object,
        Array $newRelatedIds,
        Array $oldRelatedIds,
        $tableName,
        $field
    ) {
        $table = $this->getTable($tableName);

        $insert = array_diff($newRelatedIds, $oldRelatedIds);
        $delete = array_diff($oldRelatedIds, $newRelatedIds);

        if ($delete) {
            $where = [
                        'attachment_id = ?' => (int)$object->getId(),
                        $field.' IN (?)' => $delete
                    ];

            $this->getConnection()->delete($table, $where);
        }

        if ($insert) {
            $data = [];

            foreach ($insert as $storeId) {
                $data[] = [
                            'attachment_id' => (int)$object->getId(),
                            $field => (int)$storeId
                        ];
            }

            $this->getConnection()->insertMultiple($table, $data);
        }
    }
   
    /**
     * Perform operations after object load
     *
     * @param \Magento\Framework\Model\AbstractModel $object
     * @return $this
     */
    protected function _afterLoad(
        \Magento\Framework\Model\AbstractModel $object
    ) {
        if ($object->getId()) {
            $stores = $this->lookupStoreIds($object->getId());
            $object->setData('store_id', $stores);
        }

        return parent::_afterLoad($object);
    }
    
    /**
     * Get store ids to which specified item is assigned
     *
     * @param int $pageId
     * @return array
     */
    public function lookupStoreIds($faqId)
    {
        return $this->_lookupIds($faqId, 'solwin_productattachment_store', 'store_id');
    }
    /**
     * Get ids to which specified item is assigned
     * @param  int $postId
     * @param  string $tableName
     * @param  string $field
     * @return array
     */
    
    protected function _lookupIds($faqId, $tableName, $field)
    {
        $adapter = $this->getConnection();

        $select = $adapter->select()->from(
            $this->getTable($tableName),
            $field
        )->where(
            'attachment_id = ?',
            (int)$faqId
        );

        return $adapter->fetchCol($select);
    }
    
}
