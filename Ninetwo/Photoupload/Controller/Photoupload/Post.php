<?php
/**
 *
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Ninetwo\Photoupload\Controller\Photoupload;

class Post extends \Magento\Framework\App\Action\Action
{
	/**
     * Recipient email config path
     */
    const XML_PATH_EMAIL_RECIPIENT = 'photoupload/email/recipient_email';

    /**
     * Sender email config path
     */
    const XML_PATH_EMAIL_SENDER = 'photoupload/email/sender_email_identity';

    /**
     * Email template config path
     */
    const XML_PATH_EMAIL_TEMPLATE = 'photoupload/email/email_template';
	
	protected $_objectManager;

	/**
	* @var \Magento\Framework\Image\AdapterFactory
	*/
	protected $_adapterFactory;
	/**
	* @var \Magento\MediaStorage\Model\File\UploaderFactory
	*/
	protected $_uploader;
	/**
	* @var \Magento\Framework\Filesystem
	*/
	protected $_filesystem;
	/**
	* @var \Magento\Framework\Stdlib\DateTime\TimezoneInterface
	*/
	/**
     * @var \Magento\Framework\Mail\Template\TransportBuilder
     */
    protected $_transportBuilder;

	 /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;
	
	public function __construct(
		\Magento\Framework\App\Action\Context $context,
		\Magento\Framework\ObjectManagerInterface $objectManager,
		\Magento\Framework\Image\AdapterFactory $adapterFactory,
		\Magento\MediaStorage\Model\File\UploaderFactory $uploader,
		\Magento\Framework\Mail\Template\TransportBuilder $transportBuilder,
		\Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
		\Magento\Framework\Filesystem $filesystem
		
	)
	{
		parent::__construct($context);
		$this->_objectManager = $objectManager;
		$this->_transportBuilder = $transportBuilder;
		$this->scopeConfig = $scopeConfig;
		$this->_adapterFactory = $adapterFactory;
		$this->_uploader = $uploader;
		$this->_filesystem = $filesystem;

	}
    /**
     * Post user question
     *
     * @return void
     * @throws \Exception
     */
    public function execute()
    {
        $post = $this->getRequest()->getPostValue();
		
		if (!$post) {
            $this->_redirect('photoupload/photoupload/index');
            return;
        }
		
        try {
			
				$post['image'] = NULL;
				if (isset($_FILES['image']) && isset($_FILES['image']['name']) && strlen($_FILES['image']['name'])) {
					
					try {
						$base_media_path = 'photoupload/images';
						
						$uploader = $this->_uploader->create(['fileId' => 'image']);
						
						$uploader->setAllowedExtensions(['jpg', 'jpeg', 'gif', 'png']);
						
						$imageAdapter = $this->_adapterFactory->create();
						
						$uploader->addValidateCallback('image', $imageAdapter, 'validateUploadFile');
						
						$uploader->setAllowRenameFiles(true);
						
						$uploader->setFilesDispersion(true);
						
						$mediaDirectory = $this->_filesystem->getDirectoryRead(\Magento\Framework\App\Filesystem\DirectoryList::MEDIA);
						
						$result = $uploader->save($mediaDirectory->getAbsolutePath($base_media_path));
						
						$post['image'] = $base_media_path.$result['file'];
					} catch (\Exception $e) {
						if ($e->getCode() == 0) {
							$this->messageManager->addError($e->getMessage());
						}
					}
				} else {
					if (isset($post['image']) && isset($post['image']['value'])) {
						
						if (isset($post['image']['delete'])) {
							
							$post['image'] = null;
							$post['delete_image'] = true;
						
						} elseif (isset($post['image']['value'])) {
							
							$post['image'] = $post['image']['value'];
						
						} else {
							
							$post['image'] = null;
						
						}
					}
				}
				
			$photoupload = $this->_objectManager->create('Ninetwo\Photoupload\Model\Photoupload');
			$photoupload->setName($post['name']);
			$photoupload->setYear($post['year']);
			$photoupload->setModel($post['model']);
			$photoupload->setColor($post['color']);
			$photoupload->setEmail($post['email']);
			$photoupload->setOrderNo($post['order_no']);
			$photoupload->setImage($post['image']);
			$photoupload->setComment($post['comment']);
			$photoupload->setStatus('0');
			$photoupload->setProductId($post['product_id']);
			$photoupload->setCreatedDatetime(date('Y-m-d H:i:s'));
			$photoupload->setUpdatedDatetime(date('Y-m-d H:i:s'));
			$photoupload->save();
			
			$product = $this->_objectManager->get('Magento\Catalog\Model\Product')->load($post['product_id']);
			
			// $post['product_name'] = $product->getName();
			
			$parseDataVars = new \Magento\Framework\DataObject();
			
			$parseDataVars->setData(array('product_name' => $product->getName()));
		
			$storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;
            $transport = $this->_transportBuilder
                ->setTemplateIdentifier($this->scopeConfig->getValue(self::XML_PATH_EMAIL_TEMPLATE, $storeScope))
                ->setTemplateOptions(
                    [
                        'area' => \Magento\Backend\App\Area\FrontNameResolver::AREA_CODE,
                        'store' => \Magento\Store\Model\Store::DEFAULT_STORE_ID,
                    ]
                )
                ->setTemplateVars(['data' => $parseDataVars])
                ->setFrom($this->scopeConfig->getValue(self::XML_PATH_EMAIL_SENDER, $storeScope))
                ->addTo($this->scopeConfig->getValue(self::XML_PATH_EMAIL_RECIPIENT, $storeScope))
                // ->setReplyTo($post['email'])
                ->getTransport();

            $transport->sendMessage();
			
            $this->messageManager->addSuccess(
                __('Your image was uploaded and will be reviewed shortly.')
            );
            $this->_redirect('photoupload/photoupload/index');
            return;
        } catch (\Exception $e) {
            // $this->inlineTranslation->resume();
            $this->messageManager->addError(
                $e->getMessage()
            );
			$this->messageManager->addError(
                __('We can\'t process your request right now. Sorry, that\'s all we know.')
            );
            $this->_redirect('photoupload/photoupload/index');
            return;
        }
    }
}
