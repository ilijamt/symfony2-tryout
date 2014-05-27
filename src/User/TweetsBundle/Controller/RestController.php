<?php

namespace User\TweetsBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;

class RestController extends FOSRestController {

    public function getTweetsAction() {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('UserTweetsBundle:Entry');
        $entries = $repository->getEntries();

        $view = $this->view($entries, 200);
        return $this->handleView($view);
    }

    public function getTweetsUserAction($user_id) {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('UserTweetsBundle:Entry');
        $entries = $repository->getEntries($user_id);

        $view = $this->view($entries, 200);
        return $this->handleView($view);
    }

}
