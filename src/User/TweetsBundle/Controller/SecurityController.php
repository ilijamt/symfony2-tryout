<?php

namespace User\TweetsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\SecurityContext;
use User\TweetsBundle\Entity\User;
use User\TweetsBundle\Form\UserType;

class SecurityController extends Controller
{

    public function loginAction()
    {
        $request = $this->getRequest();
        $session = $request->getSession();

        // get the login error if there is one
        if ($request->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) {
            $error = $request->attributes->get(
                SecurityContext::AUTHENTICATION_ERROR
            );
        } else {
            $error = $session->get(SecurityContext::AUTHENTICATION_ERROR);
            $session->remove(SecurityContext::AUTHENTICATION_ERROR);
        }
        
	$form = $this->createForm(new UserType(), new User());
        $formView = $form->createView();

	$formView->vars['form']['username']->vars['full_name'] = "_username";
        $formView->vars['form']['password']->vars['full_name'] = "_password"; 

        return $this->render(
            'UserTweetsBundle:Security:login.html.twig',
            array(
                // last username entered by the user
                'last_username' => $session->get(SecurityContext::LAST_USERNAME),
                'error'         => $error,
                'form'          => $formView
            )
        );
    }

    public function dumpStringAction()
    {
      return $this->render('UserTweetsBundle:Security:dumpString.html.twig', array());
    }

}
