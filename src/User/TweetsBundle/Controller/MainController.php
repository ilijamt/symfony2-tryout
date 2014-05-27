<?php

namespace User\TweetsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class MainController extends Controller {

    public function profileAction() {
        return $this->render('UserTweetsBundle:Main:profile.html.twig', array());
    }

    public function indexAction() {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('UserTweetsBundle:Entry');
        $entries = $repository->getEntries();

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate($entries, $this->get('request')->query->get('page', 1), 10);

        return $this->render('UserTweetsBundle:Main:index.html.twig', array('pagination' => $pagination));
    }

    public function myTweetsAction() {

        $user = $this->get('security.context')->getToken()->getUser();

        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('UserTweetsBundle:Entry');
        $entries = $repository->getEntries($user->getId());

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate($entries, $this->get('request')->query->get('page', 1), 10);

        return $this->render('UserTweetsBundle:Main:myTweets.html.twig', array('pagination' => $pagination));
    }

}
