<?php

use Phalcon\Mvc\Controller;
use MongoDB\BSON\ObjectId;


class AclTestController extends Controller
{
    public function indexAction()
    {
        echo "inside add AclTest!";

    }

    public function addRoleAction()
    {
        // echo "inside add role!";
        $role = $this->request->getPost('role');
        $role = strtolower(trim($role));
        
        

        if (isset($role) && $role != null && $role != "") {

            $roles = $this->mongo->role->find(['role' => $role])->toArray();

            if (isset($roles[0])) {
                // left it blank because if the role is already present. It doesn't have to do anything.
            } else {
                $this->mongo->role->insertOne(['role' => $role]);
            }


            /**
             * we can even using "...->find()->toArray();"
             * then parse it in "foreach" loop, then
             * foreach ($roles as $r) {
             *      print_r($r['role']);    // check if  " $r['role'] " exists or not
             * }
             */
        }
    }

    public function addComponentAction()
    {
        // echo "inside add component!";

        $component = $this->request->getPost();
        $controller = trim($component['controller']);
        $action = trim($component['action']);
        
        $componentArrs = ['controller' => $controller, 'action' => [$action]];

        if ($controller != '' && $controller != null && $action != '' && $action != null) {
            // echo "<pre>adding components ...\n";
            // print_r($component);
            $conExists = $this->mongo->component->find(['component.controller' => $controller])->toArray();
            // $actExists = $this->mongo->component->find(['component.action' => $action])->toArray();
            $actExists = $this->mongo->component->findOne(['component.action' => $action]);
            // $actExistsId = $actExists[0]['_id'];
            $conExistsId = $conExists[0]['_id'];
            // echo "<pre>$conExistsId";
            // print_r($actExists);

            // die;


            if (isset($conExists[0])) {
                if (isset($actExists['_id'])) {
                    $actExistsId = $actExists['_id'];
                }
                // echo $actExistsId;
                // echo $actExists['component']['controller'];
                // echo $actExists['component']['action'][0];
            // $conExistsId = $conExists[0]['id']
                if (isset($actExists)) {
                    echo "<script>alert('action in the existing controller already exists')</script>";
                    die;
                } else {
                    // $this->mongo->component->

                    $this->mongo->component->updateOne(['_id'=> new ObjectId($conExistsId)], ['$push' => ['component.action' => $action]]);
                    // $this->mongo->component->updateOne(['_id'=> new ObjectId($conExistsId)], ['component' => ['$push' => ['action' => $action]]]);
                    echo "<script>alert('action is adding on the existing controller')</script>";

                    // die;
                }

        
                // print_r($conExists);
            } else {
                $this->mongo->component->insertOne(['component' => $componentArrs]);
                echo "<script>alert('new action on a new controller is adding')</script>";
            }

            





            
            // $controller = $this->mongo->component->find(['controller' => $controller]);


            

        }
    }
}
