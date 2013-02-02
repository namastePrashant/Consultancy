<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap {

    protected function _initAcl() {
        $helper = new Destiny_Controller_Helper_Acl();
        $helper->setRoles();
        $helper->setResources();
        $helper->setPrivilages();
        $helper->setAcl();
        $frontController = Zend_Controller_Front::getInstance();
        $frontController->registerPlugin(new Destiny_Controller_Plugin_Acl());
    }

}

