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
namespace Solwin\ProductAttachment\Block\Adminhtml\Attachment;

use Magento\Backend\Block\Widget\Context;
use Magento\Backend\Block\Widget\Form\Container as FormContainer;
use Magento\Framework\Registry;

class Edit extends FormContainer
{
    /**
     * Core registry
     * 
     * @var Registry
     */
    protected $_coreRegistry;

    /**
     * constructor
     * 
     * @param Registry $coreRegistry
     * @param Context $context
     * @param array $data
     */
    public function __construct(
        Registry $coreRegistry,
        Context $context,
        array $data = []
    )
    {
        $this->_coreRegistry = $coreRegistry;
        parent::__construct($context, $data);
    }

    /**
     * Initialize Attachment edit block
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_objectId = 'attachment_id';
        $this->_blockGroup = 'Solwin_ProductAttachment';
        $this->_controller = 'adminhtml_attachment';
        parent::_construct();
        $this->buttonList->update('save', 'label', __('Save Attachment'));
        $this->buttonList->add(
            'save-and-continue',
            [
                'label' => __('Save and Continue Edit'),
                'class' => 'save',
                'data_attribute' => [
                    'mage-init' => [
                        'button' => [
                            'event' => 'saveAndContinueEdit',
                            'target' => '#edit_form'
                        ]
                    ]
                ]
            ],
            -100
        );
        $this->buttonList->update('delete', 'label', __('Delete Attachment'));
    }
    /**
     * Retrieve text for header element depending on loaded Attachment
     *
     * @return string
     */
    public function getHeaderText()
    {
        /** @var \Solwin\ProductAttachment\Model\Attachment $attachment */
        $attachment = $this->_coreRegistry->registry('solwin_productattachment_attachment');
        if ($attachment->getId()) {
            return __("Edit Attachment '%1'", $this->escapeHtml($attachment->getTitle()));
        }
        return __('New Attachment');
    }
}
