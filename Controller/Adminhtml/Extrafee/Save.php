<?php
/**
 * MagePrince
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the mageprince.com license that is
 * available through the world-wide-web at this URL:
 * https://mageprince.com/end-user-license-agreement
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 *
 * @category    MagePrince
 * @package     Mageprince_Extrafee
 * @copyright   Copyright (c) MagePrince (https://mageprince.com/)
 * @license     https://mageprince.com/end-user-license-agreement
 */

namespace Mageprince\Extrafee\Controller\Adminhtml\Extrafee;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Config\Storage\WriterInterface;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\Controller\ResultInterface;
use Mageprince\Extrafee\Model\Config\DefaultConfig;
use Magento\SalesRule\Model\Rule;

class Save extends Action
{
    /**
     * @var DataPersistorInterface
     */
    protected $dataPersistor;

    /**
     * @var WriterInterface
     */
    protected $writerInterface;

    /**
     * Save constructor.
     * @param Context $context
     * @param DataPersistorInterface $dataPersistor
     * @param WriterInterface $writerInterface
     */
    public function __construct(
        Context $context,
        DataPersistorInterface $dataPersistor,
        WriterInterface $writerInterface
    ) {
        $this->dataPersistor = $dataPersistor;
        $this->writerInterface = $writerInterface;
        parent::__construct($context);
    }

    /**
     * Save action
     *
     * @return ResultInterface
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        $data = $this->getRequest()->getPostValue();
        try {
            $data['conditions'] = $data['rule']['conditions'];
            unset($data['rule']);

            $salesRule = $this->_objectManager->create(Rule::class);
            $salesRule->loadPost($data);

            $conditionSerialized = json_encode($salesRule->getConditions()->asArray());

            $this->writerInterface->save(
                DefaultConfig::XML_PATH_EXTRAFEE_CONDITIONS,
                $conditionSerialized
            );

            $this->messageManager->addSuccessMessage(__('You saved extra Fee conditions.'));
            return $resultRedirect->setPath('*/*/edit');
        } catch (\Exception $e) {
            $this->messageManager->addExceptionMessage(
                $e,
                __('Something went wrong while saving the Extra Fee conditions.')
            );
        }

        $this->dataPersistor->set('mageprince_extrafee', $data);
        return $resultRedirect->setPath('*/*/edit');
    }
}
