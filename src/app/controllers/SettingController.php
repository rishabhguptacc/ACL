<?php

use Phalcon\Mvc\Controller;


class SettingController extends Controller
{
    public function indexAction()
    {
        $this->view->settingList = array('with_tag' => "with tag", 'without_tag' => "without tag");
        // $this->view->settingList = array("with tag", "without tag");
    }
}
