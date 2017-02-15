<?php
namespace Infobeans\Faq\Block\Adminhtml\Faq\Edit;

/**
 * Adminhtml FAQ edit form
 */
class Form extends \Magento\Backend\Block\Widget\Form\Generic
{

    /**
     * @var \Magento\Store\Model\System\Store
     */
    protected $_systemStore;
    
    protected $_categoryCollection;
    /**
     * @var \Magento\Cms\Model\Wysiwyg\Config
     */
    protected $_wysiwygConfig; 
    
    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Data\FormFactory $formFactory
     * @param \Magento\Cms\Model\Wysiwyg\Config $wysiwygConfig
     * @param \Magento\Store\Model\System\Store $systemStore
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        \Magento\Store\Model\System\Store $systemStore,
        \Infobeans\Faq\Model\ResourceModel\Category\Collection $categoryCollection,  
        \Magento\Cms\Model\Wysiwyg\Config $wysiwygConfig,    
        array $data = []
    ) {
        $this->_systemStore = $systemStore;
        $this->_categoryCollection = $categoryCollection;
        $this->_wysiwygConfig = $wysiwygConfig;
        parent::__construct($context, $registry, $formFactory, $data);
    }

    /**
     * Init form
     *
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('faq_form');
        $this->setTitle(__('FAQ Information'));
    }

    /**
     * Prepare form
     *
     * @return $this
     */
    protected function _prepareForm()
    {
        
        $model = $this->_coreRegistry->registry('faq_faq');

        
        $form = $this->_formFactory->create(
            ['data' => ['id' => 'edit_form', 'action' => $this->getData('action'), 'method' => 'post']]
        );

        $form->setHtmlIdPrefix('faq_');

        $fieldset = $form->addFieldset(
            'base_fieldset',
            ['legend' => __('General Information'), 'class' => 'fieldset-wide']
        );

        if ($model->getFaqId()) {
            $fieldset->addField('faq_id', 'hidden', ['name' => 'faq_id']);
        }

        $fieldset->addField(
            'title',
            'text',
            ['name' => 'title', 'label' => __('Title'), 'title' => __('Title'), 'required' => true]
        );

        
        $fieldset->addField(
            'is_active',
            'select',
            [
                'label' => __('Status'),
                'title' => __('Status'),
                'name' => 'is_active',
                'required' => true,
                'options' => ['1' => __('Enabled'), '0' => __('Disabled')]
            ]
        );
        if (!$model->getId()) {
            $model->setData('is_active', '1');
        }
        
        $categories[] = ['label' => __('Please select'), 'value' => ""];
        $collection = $this->_categoryCollection
            ->setOrder('sort_order');
           

        foreach($collection as $item) {
            $categories[] = array(
                'label' => $item->getCategoryName() . ($item->getIsActive() ? '' : ' ('.__('Disabled').')' ),
                'value' => $item->getId() ,
            );
        }
        
        
        $field = $fieldset->addField(
            'category_id',
            'select',
            [
                'name' => 'category_id',
                'label' => __('Category'),
                'title' => __('Category'),
                'values' => $categories, 
                'style' => 'width:100%',
                'required' => true,
            ]
        );
        

        $fieldset->addField(
            'content',
            'editor',
            [
                'name' => 'content',
                'label' => __('Content'),
                'title' => __('Content'),
                'style' => 'height:36em',
                'required' => true,
                'config' => $this->_wysiwygConfig->getConfig()
                
            ]
        );

        $form->setValues($model->getData());
        $form->setUseContainer(true);
        $this->setForm($form);

        return parent::_prepareForm();
    }
}
