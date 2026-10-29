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

namespace Mageprince\Extrafee\Block\Adminhtml\Extrafee\Edit\Tab;

use Magento\Backend\Block\Template\Context;
use Magento\Backend\Block\Widget\Form\Generic;
use Magento\Backend\Block\Widget\Form\Renderer\Fieldset;
use Magento\Backend\Block\Widget\Tab\TabInterface;
use Magento\Framework\Data\FormFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Registry;
use Magento\Rule\Block\Conditions as BlockConditions;
use Mageprince\Extrafee\Model\Config\Source\ApplyFor;

class Condition extends Generic implements TabInterface
{
    /**
     * @var Fieldset
     */
    protected $_rendererFieldset;

    /**
     * @var BlockConditions
     */
    protected $_blockConditions;

    /**
     * @var ApplyFor
     */
    protected $applyFor;

    /**
     * Condition constructor.
     * @param Context $context
     * @param Registry $registry
     * @param FormFactory $formFactory
     * @param Fieldset $rendererFieldset
     * @param BlockConditions $blockConditions
     * @param ApplyFor $applyFor
     * @param array $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        FormFactory $formFactory,
        Fieldset $rendererFieldset,
        BlockConditions $blockConditions,
        ApplyFor $applyFor,
        array $data = []
    ) {
        $this->_rendererFieldset = $rendererFieldset;
        $this->_blockConditions = $blockConditions;
        $this->applyFor = $applyFor;
        parent::__construct($context, $registry, $formFactory, $data);
    }

    /**
     * Prepare label for tab
     *
     * @return \Magento\Framework\Phrase
     */
    public function getTabLabel()
    {
        return __('Conditions');
    }

    /**
     * Prepare title for tab
     *
     * @return \Magento\Framework\Phrase
     */
    public function getTabTitle()
    {
        return $this->getTabLabel();
    }

    /**
     * @inheritdoc
     */
    public function canShowTab()
    {
        return true;
    }

    /**
     * @inheritdoc
     */
    public function isHidden()
    {
        return false;
    }

    /**
     * Prepare form
     *
     * @return Generic
     * @throws LocalizedException
     */
    protected function _prepareForm()
    {
        $form = $this->_formFactory->create();

        $rule = $this->_coreRegistry->registry('mageprince_extrafee_rule');

        $form->setHtmlIdPrefix('rule_');

        $applyFieldset = $form->addFieldset(
            'apply_fieldset',
            ['legend' => __('Fee Application')]
        );

        $applyFieldset->addField(
            'apply_fee_for',
            'select',
            [
                'name' => 'apply_fee_for',
                'label' => __('Apply Fees for'),
                'title' => __('Apply Fees for'),
                'values' => $this->applyFor->toOptionArray(),
                'value' => (int) $rule->getData('apply_fee_for'),
                'note' => __(
                    'Matching Cart Items Only calculates the fee from the cart items '
                    . 'that match the product conditions below.'
                )
            ]
        );

        $renderer = $this->getLayout()->createBlock(Fieldset::class);
        $renderer->setTemplate(
            'Magento_CatalogRule::promo/fieldset.phtml'
        )->setNewChildUrl(
            $this->getUrl('extrafee_single/extrafee/newRuleCondition/form/rule_conditions_fieldset')
        );

        $fieldset = $form->addFieldset(
            'conditions_fieldset',
            ['legend' => __('Conditions (don\'t add conditions if need all products)')]
        )->setRenderer(
            $renderer
        );

        $fieldset->addField(
            'conditions',
            'text',
            ['name' => 'conditions', 'label' => __('Conditions'), 'title' => __('Conditions'), 'required' => true]
        )->setRule(
            $rule
        )->setRenderer(
            $this->_blockConditions
        );

        $this->setForm($form);

        return parent::_prepareForm();
    }
}
