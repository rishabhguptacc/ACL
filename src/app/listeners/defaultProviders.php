<?php
namespace App\Listeners;

use Phalcon\Events\Event;

class defaultProviders
{
    public function updateToDefaults(Event $event, $values, $settings)
    {
        // echo "event triggered \n";
        if ($settings->title_optimization == "with_tag") {
            $values->product_name = $values->product_name.'['.$values->tags.']';
        }

        if ($values->price == '') {
            $values->price = $settings->default_price;
        }

        if ($values->stock == '') {
            $values->stock = $settings->default_stock;
        }

        return $values;
    }

    public function updateToDefaultZipcode(Event $event, $values, $settings)
    {
        # code...
        // print_r($settings); die;
        if ($values->zipcode == '') {
            $values->zipcode = $settings->default_zipcode;
        }
        return $values;
    }
}
