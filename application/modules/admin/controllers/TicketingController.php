<?php

class Admin_TicketingController extends Zend_Controller_Action {

    public function init() {
        /* Initialize action controller here */
        if (!Zend_Auth::getInstance()->hasIdentity()) {
            $this->_helper->redirector('index', 'login');
        }
    }

    public function indexAction() {
        $ticketingModel = new Admin_Model_Ticketing();
        $this->view->result = $ticketingModel->getAll();
    }

    public function addAction() {
        $form = new Admin_Form_TicketingForm();
         $id = $this->_getParam("id");
        if ($id) {
            $medicalmodel = new Admin_Model_Medical();
        $option = $medicalmodel->getStampingApprovedById($id);
            $form->candidate_id->setValue($option);
        }
        $this->view->form = $form;
        if ($this->getRequest()->isPost()) {
            $formData = $this->getRequest()->getPost();
            if ($form->isValid($formData)) {
                unset($formData['submit']);
                unset($formData["ticketing_id"]);
                try {
                    $ticketingModel = new Admin_Model_Ticketing();
                    $ticketingModel->add($formData);
                    $this->_helper->FlashMessenger->addMessage(array("success" => "Successfully Ticketing added"));
                    $this->_helper->redirector("index","index");
                } catch (Exception $e) {
                    $this->_helper->FlashMessenger->addMessage(array("error" => $e->getMessage()));
                }
            }
        }
    }

    public function editAction() {

        $form = new Admin_Form_TicketingForm();
        $form->submit->setLabel("Save");
        $stampingModel = new Admin_Model_Stamping();
        $id = $this->_getParam('id', 0);
        $data = $stampingModel->getDetailById($id);
        $form->populate($data);
        $this->view->form = $form;
        try {
            if ($this->getRequest()->isPost()) {
                if ($form->Valid($this->getRequest()->getPost())) {
                    $formData = $this->getRequest()->getPost();
                    $id = $formData['stamping_id'];
                    unset($formData['stamping_id']);
                    unset($formData['submit']);

                    $stampingModel->update($formData, $id);
                    $this->_helper->FlashMessenger->addMessage(array("success" => "Successfully Stamping edited"));
                    $this->_helper->redirector('list');
                }
            }
        } catch (Exception $e) {
            $this->_helper->FlashMessenger->addMessage(array("error" => $e->getMessage()));
        }
    }

    public function deleteAction() {
        $id = $this->_getParam('id', 0);
        $stampingModel = new Admin_Model_Stamping();
        $this->view->id = $id;
        if ($this->getRequest()->isPost()) {
            try {
                $delete = $this->_getParam('delete');
                if ('Yes' == $delete) {
                    $stampingModel->delete($id);
                }$this->_helper->redirector("list");
            } catch (Exception $e) {
                $this->view->message = $e->getMessage();
            }
        }
    }

    public function listAction() {
        $config = new Zend_Config_Ini(BASE_PATH . DIRECTORY_SEPARATOR . "configs" . DIRECTORY_SEPARATOR . "grid.ini", 'production');
        $grid = Bvb_Grid::factory('Table', $config);
        $data = $this->_listdata();
        $source = new Bvb_Grid_Source_Array($data);
        $baseUrl = Zend_Controller_Front::getInstance()->getBaseUrl();
        $grid->setSource($source);
        $grid->setImagesUrl("$baseUrl/grid/");
        $furtherProcessingColumn = new Bvb_Grid_Extra_Column();
        $furtherProcessingColumn->setPosition('right')->setName('Processing')->setDecorator("<a href=\"$baseUrl/admin/stamping/stamping-test/id/{{candidate_id}}\">Processing</a><input class=\"address-id\" name=\"address_id[]\" type=\"hidden\" value=\"{{candidate_id}}\"/>");
        $deleteColumn = new Bvb_Grid_Extra_Column();
        $deleteColumn->setPosition('right')->setName('Delete')->setDecorator("<a class=\"delete-data\" href=\"$baseUrl/admin/stamping/delete/id/{{stamping_id}}\">Delete</a>");
        $grid->addExtraColumns($furtherProcessingColumn, $deleteColumn);
        $grid->updateColumn('stamping_id', array('hidden' => true));
        $grid->updateColumn('candidate_id', array('hidden' => true));
        $grid->updateColumn('del', array('hidden' => true));
        $grid->setRecordsPerPage(20);
        $grid->setPaginationInterval(array(
            5 => 5,
            10 => 10,
            20 => 20,
            30 => 30,
            40 => 40,
            50 => 50,
            100 => 100
        ));
        $grid->setExport(array('print', 'word', 'csv', 'excel', 'pdf'));
        $this->view->grid = $grid->deploy();
    }

    public function _listdata() {
        $i = 1;
        $menus = array();
        $menuModel = new Admin_Model_Stamping();
        $allMenus = $menuModel->listAll();

        foreach ($allMenus as $menu):
            $data = array();
            $data['SNo'] = $i++;
            $data["stamping_id"] = $menu['stamping_id'];
            $data["candidate_id"] = $menu['candidate_id'];
            $data['candidate_name'] = $menu['candidate_name'];
            $data['mofa_no'] = $menu['mofa_no'];
            $data['gamca_no'] = $menu['gamca_no'];
            $data['medical_center'] = $menu['medical_center'];
            $data['visa_no'] = $menu['visa_no'];
            $data['id_no'] = $menu['id_no'];
            $menus[] = $data;
        endforeach;
        return $menus;
    }

    
}

