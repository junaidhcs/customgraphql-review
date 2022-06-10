<?php

declare(strict_types=1);

namespace Mytask\CustomGraphql\Model\Resolver;


use Magento\Framework\GraphQl\Config\Element\Field;

use Magento\Framework\GraphQl\Query\ResolverInterface;

use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;

use Magento\Review\Model\ResourceModel\Review\CollectionFactory as ReviewCollectionFactory;

use Magento\Review\Model\Review;

use Magento\Store\Model\StoreManagerInterface;


class Reviews implements ResolverInterface

{

    protected $storeManager;

    protected $reviewCollection;


    public function __construct(

        StoreManagerInterface $storeManager,

        ReviewCollectionFactory $reviewCollection

    ) {

        $this->storeManager = $storeManager;

        $this->reviewCollection = $reviewCollection;

    }


    public function resolve(Field $field, $context, ResolveInfo $info, array $value = null, array $args = null)

    {

        $currentStoreId = $this->storeManager->getStore()->getId();


        $collection = $this->reviewCollection->create()

            ->addStoreFilter($currentStoreId)

            ->addStatusFilter(Review::STATUS_APPROVED)

            ->addEntityFilter('product', $args['id'])

            ->setDateOrder()

            ->getFirstItem();


        return $collection->getData();

    }

}