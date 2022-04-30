<?php

use Phalcon\Mvc\Controller;
use Phalcon\Acl\Adapter\Memory;
use Phalcon\Acl\Role;
use Phalcon\Acl\Component;

class SecureController extends Controller
{
    public function buildACLAction()
    {
        $aclFile =  APP_PATH.'/security/acl.cache';
        // Check whether ACL data already exist
        if (true !== is_file($aclFile)) {

            // The ACL does not exist - build it
            $acl = new Memory();

            /**
             * Add the roles
             */
            $acl->addRole('admin');
            $acl->addRole('manager');
            $acl->addRole('guest');


            /**
             * Add the Components
             */

            $acl->addComponent(
                'aclTest',
                [
                    'index',
                    'addRole',
                    'addComponent',
                ]
            );

            $acl->addComponent(
                'index',
                [
                    'index',
                ]
            );

            $acl->addComponent(
                'order',
                [
                    'index',
                    'addOrder',
                    'displayOrder',
                ]
            );

            $acl->addComponent(
                'product',
                [
                    'index',
                    'addProduct',
                    'displayProduct',
                ]
            );


            $acl->addComponent(
                'secure',
                [
                    'index',
                    'buildACL',
                    'setSetting',
                ]
            );

            $acl->addComponent(
                'setting',
                [
                    'index',
                ]
            );

            /**
             * Now tie them all together 
             */
            $acl->allow('admin', '*', '*');
            $acl->allow('manager', 'product', '*');
            $acl->allow('manager', 'order', '*');
            $acl->deny('guest', 'product', 'displayProduct');

            // Store serialized list into plain file
            file_put_contents(
                $aclFile,
                serialize($acl)
            );
        } else {
            // Restore ACL object from serialized file
            $acl = unserialize(
                file_get_contents($aclFile)
            );
        }

        // Use ACL list as needed
        // if (true === $acl->isAllowed('manager', 'admin', 'dashboard')) {
        //     echo 'Access granted!';
        // } else {
        //     echo 'Access denied :(';
        // }
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
