<?php

class Destiny_Controller_Helper_Acl {

    public $acl;

    public function __construct() {
        $this->acl = new Zend_Acl();
    }

    public function setRoles() {
        $this->acl->addRole(new Zend_Acl_Role('guest'))
                ->addRole(new Zend_Acl_Role('admin'))
                ->addRole(new Zend_Acl_Role('staff'))
                ->addRole(new Zend_Acl_Role('account'))
                ->addRole(new Zend_Acl_Role('foreign'))
                ->addRole(new Zend_Acl_Role('front'));
    }

    public function setResources() {
        /*
         * assign resources in three forms 
         * 1. action,$actionName 2. module, $moduleName, 3. all,$moduleName,$controllerName,$actionName
         */
        $this->acl->add(new Zend_Acl_Resource("module,admin"));
        $this->acl->add(new Zend_Acl_Resource("module,default"));
        $this->acl->add(new Zend_Acl_Resource('action,list'));
        $this->acl->add(new Zend_Acl_Resource('action,add'));
        $this->acl->add(new Zend_Acl_Resource('action,edit'));
        $this->acl->add(new Zend_Acl_Resource('action,delete'));
    }

    public function setPrivilages() {
        /*
         * set privilages in the following forms
         * 1. role,null, resources from setResources
         */
        $this->acl->allow(array('guest', 'front'), null, 'module,default');
        $this->acl->deny("guest", null, "module,admin");
        $this->acl->allow(array('staff'), null, array('action,add', 'action,edit', 'action,list', 'all,admin,candidate,processing', 'all,admin,candidate,interview','all,admin,medical,medical-test'));
        $this->acl->allow('front', null, 'all,admin,candidate,add');
        $this->acl->allow('foreign', null, array('action,list','all,admin,stamping,stamping-test'));
        $this->acl->allow('account', null, array('all,admin,candidate,add', 'all,admin,candidate,list', 'all,admin,candidate,edit', 'all,admin,agent,add', 'all,admin,agent,list', 'all,admin,agent,account', 'all,admin,candidate,edit-account'));
        $this->acl->allow('admin');
        $this->acl->allow(array("front", "staff", "account", "foreign"), null, array("all,admin,login,logout", "all,admin,index,index"));
        $this->acl->allow(array("guest"), null, "all,admin,login,index");
    }

    public function setAcl() {
        Zend_Registry::set('acl', $this->acl);
    }

}

?>