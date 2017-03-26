<?php

namespace AdminBundle\Event;

use AdminBundle\Event\ProductEvent;

class ProductSubscriber
{
    public function onProductAddEvent(ProductEvent $event)
    {
        //code for add event
    }

    public function onProductEditEvent($event)
    {
        //code for edit event
    }
}
