<?php

class Destiny_Controller_Plugin_Acl extends Zend_Controller_Plugin_Abstract {

    public function preDispatch(Zend_Controller_Request_Abstract $request) {
        $acl = Zend_Registry::get('acl');
        if (Zend_Auth::getInstance()->hasIdentity()) {
            $role = Zend_Auth::getInstance()->getIdentity()->role;
        } else {
            $role = "guest";
        }
        $privilageName = $request->getActionName();
        $action = $request->getActionName();
        $controller = $request->getControllerName();
        $module = $request->getModuleName();
        $privilageName = "$module,$controller,$action";
        $isAllowed = false;
        if (!$isAllowed) {
            $privilageName = "module,$module";
            $isAllowed = $acl->isAllowed($role, null, $privilageName);
        }
        if (!$isAllowed) {
            $privilageName = "action,$action";
            $isAllowed = $acl->isAllowed($role, null, $privilageName);
        }
        if (!$isAllowed) {
            $privilageName = "all,$module,$controller,$action";
            $isAllowed = $acl->isAllowed($role, null, $privilageName);
        }
        if (!$isAllowed) {
            $request->setModuleName('default');
            if ($module == 'admin') {
                if ($role != "guest") {
                    $request->setModuleName("admin");
                }
                $request->setControllerName('index');
                $request->setActionName('index');
            }
        }
    }

}

?>