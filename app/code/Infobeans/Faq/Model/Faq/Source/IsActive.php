<?php
namespace Infobeans\Faq\Model\Faq\Source;

class IsActive implements \Magento\Framework\Data\OptionSourceInterface
{
    /**
     * @var \Infobeans\Faq\Model\Faq
     */
    protected $faq;

    /**
     * Constructor
     *
     * @param \Infobeans\Faq\Model\Faq $faq
     */
    public function __construct(\Infobeans\Faq\Model\Faq $faq)
    {
        $this->faq = $faq;
    }

    /**
     * Get options
     *
     * @return array
     */
    public function toOptionArray()
    {
        $options[] = ['label' => '', 'value' => ''];
        $availableOptions = $this->faq->getAvailableStatuses();
        foreach ($availableOptions as $key => $value) {
            $options[] = [
                'label' => $value,
                'value' => $key,
            ];
        }
        return $options;
    }
}
