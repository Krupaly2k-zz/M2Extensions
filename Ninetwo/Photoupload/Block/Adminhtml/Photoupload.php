<?php
namespace Ninetwo\Photoupload\Block\Adminhtml;
class Photoupload extends \Magento\Backend\Block\Widget\Grid\Container
{
    /**
     * Constructor
     *
     * @return void
     */
    protected function _construct()
    {
		
        $this->_controller = 'adminhtml_photoupload';/*block grid.php directory*/
        $this->_blockGroup = 'Ninetwo_Photoupload';
        $this->_headerText = __('Photoupload');
        $this->_addButtonLabel = __('Add New Entry'); 
        parent::_construct();
		
    }
}