<?php

use Phalcon\Mvc\Controller;

class ProductController extends Controller
{
    public function indexAction()
    {
        //return '<h1>Hello!!!</h1>';
    }

    public function addProductAction()
    {
        $product = new Products;

        // $productData = $_POST;
        $productData = $this->request->getPost();
        
        $eventsManager = $this->di->get('eventsManager');
        // $settings = Settings::find();
        $setting = Settings::findFirst();
        $updatedValue = $eventsManager->fire('defaults:updateToDefaults', (object)$productData, $setting);

        // print_r($productData);
        // print_r($setting);
        // print_r($settings);
        // print_r($updatedValue);
        // die;

        $updatedArray = array(
            'product_name' => $updatedValue->product_name,
            'description' => $updatedValue->description,
            'tags' => $updatedValue->tags,
            'price' => $updatedValue->price,
            'stock' =>  $updatedValue->stock
        );


        $product->assign(
            $updatedArray,
            [
                'product_name',
                'description',
                'tags',
                'price',
                'stock'
            ]
        );

        // Store and check for errors
        $success = $product->save();

        // passing the result to the view
        $this->view->success = $success;

        if ($success) {
            $message = "Product is added!";
        } else {
            $message = "Sorry, the following problems were generated:<br>"
                     . implode('<br>', $product->getMessages());
        }

        // passing a message to the view
        $this->view->message = $message;
    }

    public function displayProductsAction()
    {
        $this->view->products = Products::find();
    }
}
