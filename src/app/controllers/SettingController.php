<?php

use Phalcon\Mvc\Controller;

class SettingController extends Controller
{
    public function indexAction()
    {
        $this->view->settingList = array('with_tag'=>"with tag", 'without_tag'=>"without tag");
        // $this->view->settingList = array("with tag", "without tag");

    }

    public function setSettingAction()
    {
        // $setting = new Settings;

        $setting = Settings::findFirst();

        $setting->assign(
            $this->request->getpost(),
            [
                'title_optimization',
                'default_price',
                'default_stock',
                'default_zipcode'
            ]
        );

        // Store and check for errors
        $success = $setting->save();


        // ---------------1st event to be trigger here ---------------------------------------
        /**
         * if ($this->request->getpost(title_optimization) == 'with_tag') { event trigger to  }
         */







        // passing the result to the view
        $this->view->success = $success;

        if ($success) {
            $message = "Setting is saved!";
        } else {
            $message = "Sorry, the following problems were generated:<br>"
                     . implode('<br>', $setting->getMessages());
        }

        // passing a message to the view
        $this->view->message = $message;

    }

}
