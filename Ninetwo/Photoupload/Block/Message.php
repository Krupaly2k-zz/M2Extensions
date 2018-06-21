<?php
/**
 * Copyright © 2015 Ninetwo . All rights reserved.
 */
namespace Ninetwo\Photoupload\Block;

class Message extends \Magento\Framework\View\Element\Messages {

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Framework\Message\Factory $messageFactory,
        \Magento\Framework\Message\CollectionFactory $collectionFactory,
        \Magento\Framework\Message\ManagerInterface $messageManager,      
		\Magento\Framework\View\Element\Message\InterpretationStrategyInterface $interpretationStrategy,
        array $data = []
    ) {
        parent::__construct(
            $context,
            $messageFactory,
            $collectionFactory,
            $messageManager,
			$interpretationStrategy,
            $data
        );       
    }

    protected function _prepareLayout()
    {
        $this->addMessages($this->messageManager->getMessages(true));       
        return parent::_prepareLayout();
    }

}
