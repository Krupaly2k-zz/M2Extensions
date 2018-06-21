<?php
/**
 * Copyright © 2015 Ninetwo. All rights reserved.
 */
namespace Ninetwo\Photoupload\Model\ResourceModel;

/**
 * Photoupload resource
 */
class Photoupload extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * Initialize resource
     *
     * @return void
     */
    public function _construct()
    {
        $this->_init('photoupload', 'id');
    }

  
}
