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
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Registry;
use Magento\Framework\View\Result\PageFactory;
use Magento\SalesRule\Model\RuleFactory;
use Mageprince\Extrafee\Helper\Data as FeeHelperData;

class Edit extends Action
{
    /**
     * @var Registry
     */
    protected $coreRegistry;

    /**
     * @var RuleFactory
     */
    protected $ruleFactory;

    /**
     * @var PageFactory
     */
    protected $resultPageFactory;

    /**
     * @var FeeHelperData
     */
    protected $feeHelper;

    /**
     * Edit constructor.
     * @param Action\Context $context
     * @param Registry $coreRegistry
     * @param PageFactory $resultPageFactory
     * @param RuleFactory $ruleFactory
     * @param FeeHelperData $feeHelper
     */
    public function __construct(
        Action\Context $context,
        Registry $coreRegistry,
        PageFactory $resultPageFactory,
        RuleFactory $ruleFactory,
        FeeHelperData $feeHelper
    ) {
        $this->coreRegistry = $coreRegistry;
        $this->resultPageFactory = $resultPageFactory;
        $this->ruleFactory = $ruleFactory;
        $this->feeHelper = $feeHelper;
        parent::__construct($context);
    }

    /**
     * Edit action
     *
     * @return ResultInterface
     */
    public function execute()
    {
        $conditions = $this->feeHelper->getExtraFeeConditions();
        $rule = $this->getSalesRule($conditions);

        $this->coreRegistry->register('mageprince_extrafee_rule', $rule);

        $rule->getConditions()->setJsFormObject('rule_conditions_fieldset');

        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();

        $resultPage->getConfig()->getTitle()->prepend(__('Extra Fee Conditions'));
        return $resultPage;
    }

    /**
     * Get sales rule
     *
     * @param mixed $conditions
     * @return \Magento\SalesRule\Model\Rule
     */
    public function getSalesRule($conditions)
    {
        $rule = $this->ruleFactory->create();
        $rule->setConditionsSerialized($conditions);
        return $rule;
    }
}
