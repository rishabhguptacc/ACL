<?php

use Phalcon\Mvc\Model;

class Orders extends Model
{
    public $order_id;
    public $customer_name;
    public $customer_address;
    public $zipcode;
    public $product;
    public $quantity;

}
