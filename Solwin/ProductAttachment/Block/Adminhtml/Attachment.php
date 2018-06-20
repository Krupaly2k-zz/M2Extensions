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
namespace Solwin\ProductAttachment\Block\Adminhtml;

use Magento\Backend\Block\Widget\Grid\Container as GridContainer;

class Attachment extends GridContainer
{
    /**
     * constructor
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_controller = 'adminhtml_attachment';
        $this->_blockGroup = 'Solwin_ProductAttachment';
        $this->_headerText = __('Attachments');
        $this->_addButtonLabel = __('Create New Attachment');
        parent::_construct();
    }
}
