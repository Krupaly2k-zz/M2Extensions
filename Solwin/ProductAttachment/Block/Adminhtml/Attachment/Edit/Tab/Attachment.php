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
namespace Solwin\ProductAttachment\Block\Adminhtml\Attachment\Edit\Tab;

use Magento\Backend\Block\Template\Context;
use Magento\Backend\Block\Widget\Form\Generic as GenericForm;
use Magento\Backend\Block\Widget\Tab\TabInterface;
use Magento\Framework\Data\FormFactory;
use Magento\Framework\Registry;
use Solwin\ProductAttachment\Model\Attachment\Source\CustomerGroup;
use Solwin\ProductAttachment\Model\Attachment\Source\Status;

class Attachment extends GenericForm implements TabInterface
{
    /**
     * Customer Group options
     * 
     * @var CustomerGroup
     */
    protected $_customerGroupOptions;

    /**
     * Status options
     * 
     * @var Status
     */
    protected $_statusOptions;
    
    /**
     * @var \Magento\Store\Model\System\Store
     */
    protected $_systemStore;

    /**
     * constructor
     * 
     * @param CustomerGroup $customerGroupOptions
     * @param Status $statusOptions
     * @param Context $context
     * @param Registry $registry
     * @param FormFactory $formFactory
     * @param array $data
     */
    public function __construct(
        CustomerGroup $customerGroupOptions,
        Status $statusOptions,
        \Magento\Store\Model\System\Store $systemStore,
        Context $context,
        Registry $registry,
        FormFactory $formFactory,
        array $data = []
    )
    {
        $this->_customerGroupOptions = $customerGroupOptions;
        $this->_statusOptions        = $statusOptions;
        $this->_systemStore = $systemStore;
        parent::__construct($context, $registry, $formFactory, $data);
    }

    /**
     * Prepare form
     *
     * @return $this
     */
    protected function _prepareForm()
    {
        /** @var \Solwin\ProductAttachment\Model\Attachment $attachment */
        $attachment = $this->_coreRegistry->registry('solwin_productattachment_attachment');
        $form = $this->_formFactory->create();
        $form->setHtmlIdPrefix('attachment_');
        $form->setFieldNameSuffix('attachment');
        $fieldset = $form->addFieldset(
            'base_fieldset',
            [
                'legend' => __('Attachment Information'),
                'class'  => 'fieldset-wide'
            ]
        );
        $fieldset->addType('file', 'Solwin\ProductAttachment\Block\Adminhtml\Attachment\Helper\File');
        if ($attachment->getId()) {
            $fieldset->addField(
                'attachment_id',
                'hidden',
                ['name' => 'attachment_id']
            );
        }
        $fieldset->addField(
            'title',
            'text',
            [
                'name'  => 'title',
                'label' => __('Title'),
                'title' => __('Title'),
                'required' => true,
            ]
        );
        
         /**
         * Check is single store mode
         */
        if (!$this->_storeManager->isSingleStoreMode()) {
            $field = $fieldset->addField(
                'store_id',
                'multiselect',
                [
                    'name' => 'stores[]',
                    'label' => __('Store View'),
                    'title' => __('Store View'),
                    'required' => true,
                    'values' => $this->_systemStore
                    ->getStoreValuesForForm(false, true),
                ]
            );
            $renderer = $this->getLayout()
                    ->createBlock(
                'Magento\Backend\Block\Store\Switcher\Form\Renderer\Fieldset'
                            . '\Element'
                    );
            $field->setRenderer($renderer);
        } else {
            $fieldset->addField(
                'store_id',
                'hidden',
                ['name' => 'stores[]',
                    'value' => $this->_storeManager->getStore(true)->getId()]
            );
            $attachment->setStoreId($this->_storeManager->getStore(true)->getId());
        }
        
        $fieldset->addField(
            'attachment_file',
            'file',
            [
                'name'  => 'attachment_file',
                'label' => __('Attachment File'),
                'title' => __('Attachment File'),
                
            ]
        );
        $fieldset->addField(
            'customer_group',
            'multiselect',
            [
                'name'  => 'customer_group',
                'label' => __('Customer Group'),
                'title' => __('Customer Group'),
                'required' => true,
                'values' => $this->_customerGroupOptions->toOptionArray(),
            ]
        );
        $fieldset->addField(
            'status',
            'select',
            [
                'name'  => 'status',
                'label' => __('Status'),
                'title' => __('Status'),
                'values' => array_merge(['' => ''], $this->_statusOptions->toOptionArray()),
            ]
        );

        $attachmentData = $this->_session->getData('solwin_productattachment_attachment_data', true);
        if ($attachmentData) {
            $attachment->addData($attachmentData);
        } else {
            if (!$attachment->getId()) {
                $attachment->addData($attachment->getDefaultValues());
            }
        }
        $form->addValues($attachment->getData());
        $this->setForm($form);
        return parent::_prepareForm();
    }

    /**
     * Prepare label for tab
     *
     * @return string
     */
    public function getTabLabel()
    {
        return __('Attachment');
    }

    /**
     * Prepare title for tab
     *
     * @return string
     */
    public function getTabTitle()
    {
        return $this->getTabLabel();
    }

    /**
     * Can show tab in tabs
     *
     * @return boolean
     */
    public function canShowTab()
    {
        return true;
    }

    /**
     * Tab is hidden
     *
     * @return boolean
     */
    public function isHidden()
    {
        return false;
    }
}
