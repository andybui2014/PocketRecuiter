<?php

class CandidateController extends Application_Controller_Action {

    public function init(){
        parent::init();
        //$this->_helper->_layout->setLayout('login');
    }
    public function indexAction(){

    }
    public function watchListAction(){

    }
    public function educationAction(){
        $this->render('detail/education');
    }
    public function employmentAction(){
        $this->render('detail/employment');
    }
    public function overviewAction(){
        $this->render('detail/overview');
    }
    public function portfolioAction(){
        $this->render('detail/portofolio');
    }
    public function skillsAction(){
        $this->render('detail/skills');
    }
}
?>