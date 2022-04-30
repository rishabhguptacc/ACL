<?php
namespace App\Listeners;

use Phalcon\Events\Event;
use Phalcon\Mvc\Application;

class notificationsListeners
{
    public function beforeHandleRequest(Event $event, \Phalcon\Mvc\Application $application)
    {
        // echo "hii";
        // die;
        $aclFile = APP_PATH. '/security/acl.cache';

        // check if acl data exists
        if (true === is_file($aclFile)) {
            $acl = unserialize(file_get_contents($aclFile));
        

        // use ACL list as needed
        $role = $application->request->get("role");
        // echo $role;
        // die;
        $controller = $application->router->getControllerName();
        
        $action = $application->router->getActionName();
        // echo $action;

        if (!$role || true !== $acl->isAllowed($role, $controller, $action)) {
            echo "Access denied :(";
            die;
        } else {
            
        }
        

        
            
        }else {
            echo "We don't find any ACL list. Try after sometime";
            die;
        }
    }
}
