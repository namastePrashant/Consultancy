<?php

class Default_IndexController extends Zend_Controller_Action {

    public function indexAction() {
        $this->view->hello = $this->view->render("index/define.phtml");
    }

    public function galleryAction() {
        
    }

    public function aboutAction() {
        
    }

    public function serviceAction() {
        
    }

    public function whyusAction() {
        
    }

    public function teamAction() {
        
    }

    public function contactAction() {
        
    }
    
    public function defineAction(){
        
    }
    
    public function menuAction(){
                $this->view->hello = $this->view->render("index/menu.phtml");
        
    }
  

    public function formAction() {
        $form = new Admin_Form_CandidateForm();
        $this->view->form = $form;
        if ($this->getRequest()->isPost()) {
            $formData = $this->getRequest()->getPost();
            if ($form->isValid($formData)) {
                unset($formData['submit']);
                unset($formData["candidate_id"]);
                unset($formData["MAX_FILE_SIZE"]);
                $image = $formData["image_name"] = $form->image_name->getFileName();
                $exp = explode(DIRECTORY_SEPARATOR, $image);
                $originalFilename = $formData['image_name'] = $exp[sizeof($exp) - 1];
                $newFilename = $formData['image_name'] = time() . $formData['image_name'];
                $form->image_name->addFilter('Rename', $newFilename);
                try {
                    $form->image_name->receive();
//upload complete!
                    $file = new Zend_File_Transfer();
//                    echo "<pre>";
//                    print_r($file);exit;
//  $element = new Zend_Form_Element();
                    $file
                            ->setActualFilename($newFilename);
                    $file->save();
                } catch (Exception $e) {
//error: file couldn't be received, or saved (one of the two)
                }
                try {
                    $candidateModel = new Admin_Model_Candidate();
                    $candidateId = $candidateModel->add($formData);
                    $this->_helper->FlashMessenger->addMessage(array("success" => "Successfully Candidate added"));
                    $this->_helper->redirector('edit-account', "candidate", "admin", array('id' => $candidateId));
                } catch (Exception $e) {
                    $this->_helper->FlashMessenger->addMessage(array("error" => $e->getMessage()));
                }
            }
        }
    }

}

?>
