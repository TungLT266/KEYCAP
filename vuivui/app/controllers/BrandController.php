<?php

class BrandController extends \Phalcon\Mvc\Controller
{

    public function indexAction()
    {
        $this->view->setTemplateAfter('main');
        $brandId = $this->dispatcher->getParam("id");

        $brandModel = new Brand();
        $brandModel->find();

//        $this->view->brandId = $brandId;
    }

}

