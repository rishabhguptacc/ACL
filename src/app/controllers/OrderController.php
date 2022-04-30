<?php

use Phalcon\Mvc\Controller;

class OrderController extends Controller
{
    public function indexAction()
    {
        //return '<h1>Hello!!!</h1>';
        $this->view->productList = Products::find();
    }


    public function addOrderAction()
    {
        $order = new Orders;

        $orderData = $this->request->getpost();

        $settings = Settings::findFirst();

        // echo "<pre>";
        // print_r($orderData);
        // print_r($settings);
        // die;

        $eventsManager = $this->eventsManager;   // $this->di->get('eventsManager');

        $updatedValue = $eventsManager->fire('defaults:updateToDefaultZipcode', (object)$orderData, $settings);

        $updatedArray = array(
            'customer_name' => $updatedValue->customer_name,
            'customer_address' => $updatedValue->customer_address,
            'zipcode' => $updatedValue->zipcode,
            'product'=> $updatedValue->product,
            'quantity' => $updatedValue->quantity
        );

        $order->assign(
            $updatedArray,
            [
                'customer_name',
                'customer_address',
                'zipcode',
                'product',
                'quantity'
            ]
        );

        // Store and check for errors
        $success = $order->save();

        // passing the result to the view
        $this->view->success = $success;

        if ($success) {
            $message = "Thanks for ordering!";
        } else {
            $message = "Sorry, the following problems were generated:<br>"
                     . implode('<br>', $order->getMessages());
        }

        // passing a message to the view
        $this->view->message = $message;
    }

    public function displayOrdersAction()
    {
        $this->view->orders = Orders::find();
    }
}
