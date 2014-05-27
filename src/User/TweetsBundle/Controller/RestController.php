<?php

namespace User\TweetsBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\View\RouteRedirectView;
use FOS\RestBundle\View\View;

class RestController extends FOSRestController {

    public function deleteTweetsAction($entry_id) {

        try {

            $em = $this->getDoctrine()->getManager();
            $repository = $em->getRepository('UserTweetsBundle:Entry');
            $entries = $repository->getEntry($entry_id);

            $entry = array_pop($entries);

            $user = $this->getUser();
            if (is_null($user)) {
                $view = View::create(null, 401);
            } else if (is_null($entry)) {
                $view = View::create(null, 404);
            } else {
                if ($entry->getUserLink()->getId() != $user->getId()) {
                    $view = View::create(null, 401);
                } else {
                    $view = View::Create(array(
                                'id' => $entry->getId(),
                                    ), 200);
                    $em->remove($entry);
                    $em->flush();
                }
            }
        } catch (Exception $exc) {
            $view = View::create(array('message' => $exc->getMessage()), 400);
        }

        return $this->handleView($view);
    }

    public function patchTweetsAction($entry_id, Request $request) {

        $user = $this->getUser();

        try {
            if (is_null($user)) {
                $view = View::create(null, 401);
            } else {

                $rentry = $this->get('request')->getContent();

                $em = $this->getDoctrine()->getManager();
                $repository = $em->getRepository('UserTweetsBundle:Entry');
                $entries = $repository->getEntry($entry_id);

                $entry = array_pop($entries);

                if (is_null($entry)) {
                    $view = View::create(null, 404);
                } else {

                    if ($entry->getUserLink()->getId() == $user->getId()) {

                        $entry->setEntry($rentry);
                        $em->persist($entry);
                        $em->flush();

                        $view = View::create(array(
                                    'id' => $entry->getId(),
                                    'user_id' => $entry->getUserLink()->getId(),
                                    'username' => $entry->getUsername(),
                                    'entry' => $entry->getEntry(),
                                    'created' => $entry->getCreated(),
                                    'updated' => $entry->getUpdated(),
                                    'name' => $entry->getName(),
                                        ), 200);
                    } else {
                        $view = View::create(null, 401);
                    }
                }
            }
        } catch (\Exception $exc) {
            $view = View::create(array('message' => $exc->getMessage()), 400);
        }

        return $this->handleView($view);
    }

    public function postTweetsAction(Request $request) {

        $user = $this->getUser();

        try {
            if (is_null($user)) {
                $view = View::create(null, 401);
            } else {
                $rentry = $this->get('request')->getContent();
                $entry = new \User\TweetsBundle\Entity\Entry();

                $entry->setEntry($rentry);
                $entry->setUserLink($this->getUser());
                $entry->setUsername($this->getUser()->getUsername());
                $entry->setName($this->getUser()->getFirstName() . " " . $this->getUser()->getLastName());

                $em = $this->getDoctrine()
                        ->getEntityManager();
                $em->persist($entry);
                $em->flush();

                $view = View::create(array(
                            'id' => $entry->getId(),
                            'user_id' => $user->getId(),
                            'username' => $entry->getUsername(),
                            'entry' => $entry->getEntry(),
                            'created' => $entry->getCreated(),
                            'updated' => $entry->getUpdated(),
                            'name' => $entry->getName(),
                                ), 200);
            }
        } catch (\Exception $exc) {
            $view = View::create(array('message' => $exc->getMessage()), 400);
        }

        return $this->handleView($view);
    }

    public function getTweetsEntryHtmlAction($entry_id) {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('UserTweetsBundle:Entry');
        $entries = $repository->getEntry($entry_id);

        $entry = array_pop($entries);

        if (is_null($entry)) {
            $view = View::create(null, 404);
        } else {
            $view = View::create($entry, 200)
                    ->setTemplate("UserTweetsBundle:Main:entry.html.twig")
                    ->setTemplateVar('entry')
                    ->setFormat('html');
        }
        return $this->handleView($view);
    }

    public function getTweetsEntryAction($entry_id) {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('UserTweetsBundle:Entry');
        $entries = $repository->getEntry($entry_id);

        $entry = array_pop($entries);

        if (is_null($entry)) {
            $view = View::create(null, 404);
        } else {
            $entry = array(
                'id' => $entry->getId(),
                'user_id' => $entry->getUserLink()->getId(),
                'username' => $entry->getUsername(),
                'entry' => $entry->getEntry(),
                'created' => $entry->getCreated(),
                'updated' => $entry->getUpdated(),
                'name' => $entry->getName(),
            );
            $view = $this->view($entry, 200);
        }
        return $this->handleView($view);
    }

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
