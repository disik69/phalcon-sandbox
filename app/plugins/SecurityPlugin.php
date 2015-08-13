<?php

use Phalcon\Acl;
use Phalcon\Acl\Role;
use Phalcon\Acl\Resource;
use Phalcon\Events\Event;
use Phalcon\Mvc\User\Plugin;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Acl\Adapter\Memory as AclList;

/**
 * SecurityPlugin
 *
 * This is the security plugin which controls that users only have access to the modules they're assigned to
 */
class SecurityPlugin extends Plugin
{

    /**
     * Returns an existing or new access control list
     *
     * @returns AclList
     */
    private function getAcl()
    {
        if (!isset($this->persistent->acl)) {

            $acl = new AclList();

            $acl->setDefaultAction(Acl::DENY);

            //Register roles
            $roles = array(
                'user' => new Role('User'),
                'guest' => new Role('Guest'),
            );
            foreach ($roles as $role) {
                $acl->addRole($role);
            }

            //Private area resources
            $privateResources = array(
                'index' => array('index'),
            );
            foreach ($privateResources as $resource => $actions) {
                $acl->addResource(new Resource($resource), $actions);
            }

            //Public area resources
            $publicResources = array(
                'sign' => array('getIn', 'postIn'),
                'error' => array('notFound'),
            );
            foreach ($publicResources as $resource => $actions) {
                $acl->addResource(new Resource($resource), $actions);
            }

            //Grant access to public areas to both users and guests
            foreach ($roles as $role) {
                foreach ($publicResources as $resource => $actions) {
                    foreach ($actions as $action) {
                        $acl->allow($role->getName(), $resource, $action);
                    }
                }
            }

            //Grant acess to private area to role Users
            foreach ($privateResources as $resource => $actions) {
                foreach ($actions as $action) {
                    $acl->allow('User', $resource, $action);
                }
            }

            //The acl is stored in session, APC would be useful here too
            $this->persistent->acl = $acl;
        }

        return $this->persistent->acl;
    }

    /**
     * This action is executed before execute any action in the application
     *
     * @param Event $event
     * @param Dispatcher $dispatcher
     */
    public function beforeDispatch(Event $event, Dispatcher $dispatcher)
    {
        if (! $this->session->get('user')) {
            $role = 'Guest';
        } else {
            $role = 'User';
        }

        $controller = $dispatcher->getControllerName();
        $action = $dispatcher->getActionName();

        $acl = $this->getAcl();

        $allowed = $acl->isAllowed($role, $controller, $action);
        
        if ($allowed != Acl::ALLOW) {
            $dispatcher->forward(array(
                'controller' => 'sign',
                'action' => 'getIn',
            ));
            
            $this->session->destroy();
            
            return false;
        }
    }

}
