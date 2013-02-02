<?php

class Admin_Form_TicketingForm extends Zend_Form {

    public function init() {
        $ticketingid = new Zend_Form_Element_Hidden("ticketing_id");

        $medicalmodel = new Admin_Model_Medical();
        $option = $medicalmodel->getStampingApproved();

        $candidate_name = new Zend_Form_Element_Select("candidate_id");
        $candidate_name->setLabel("Candidate Name")
                ->addMultiOptions($option)
                ->setAttribs(array('class' => 'form-select'));
        $arrival_date = new Zend_Form_Element_Text("arrival_date");
        $arrival_date->setLabel("Arrival Date")
                ->setAttribs(array('class' => 'form-text'));
        $departure_date = new Zend_Form_Element_Text("departure_date");
        $departure_date->setLabel("Departure Date")
                ->setAttribs(array('class' => 'form-text'));
        $air_lines = new Zend_Form_Element_Text("air_lines");
        $air_lines->setLabel("Air Lines Name")
                ->setAttribs(array('class' => 'form-text'));
        $submit = new Zend_Form_Element_Submit("submit");
        $submit->setLabel("Submit");

        $this->addElements(array(
            $ticketingid,
            $candidate_name,
            $arrival_date,
            $departure_date,
            $air_lines,
            $submit));
    }

}

