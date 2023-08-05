<?php declare(strict_types=1);

namespace Umanskiy\Blog\Block;

use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Magento\Theme\Block\Html\Pager;
use Umanskiy\Blog\Model\ResourceModel\Category\CollectionFactory;

class CategoryList extends Template
{
    protected CollectionFactory $_collectionFactory;
    protected Pager $_pager;

    /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Umanskiy\Blog\Model\ResourceModel\Category\CollectionFactory $collectionFactory
     * @param array $data
     */
    public function __construct(Context $context, CollectionFactory $collectionFactory, array $data = [])
    {
        parent::__construct($context, $data);
        $this->_collectionFactory = $collectionFactory;
    }

    /**
     * @return $this
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function _prepareLayout(): CategoryList
    {
        parent::_prepareLayout();
        $this->pageConfig->getTitle()->set(__('Category List'));

        $pager = $this->getLayout()->createBlock(Pager::class, 'category.list.pager');
        $pager->setAvailableLimit([3 => 3, 5 => 5, 10 => 10, 15 => 15]);
        $pager->setShowPerPage(true);

        $this->_pager = $pager;

        $pager->setCollection($this->getCollection());
        $this->setChild('pager', $pager);

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCollection()
    {
        $collection = $this->_collectionFactory->create();

        if ($this->_pager) {
            $collection->setPageSize($this->_pager->getLimit());
            $collection->setCurPage($this->_pager->getCurrentPage());
        }

        return $collection->load();
    }

    /**
     * @return string
     */
    public function getPagerHtml(): string
    {
        return $this->getChildHtml('pager');
    }
}
