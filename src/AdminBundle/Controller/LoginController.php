<?php

namespace AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

class LoginController extends Controller
{
    /**@Template()*/
    public function loginAction(Request $request)
    {
        //var_dump($request);
        //var_dump($this->get("security.token_storage"));
        $authenticationUtils = $this->get('security.authentication_utils');

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('AdminBundle:Login:login.html.twig', array(
            'last_username' => $lastUsername,
            'error'         => $error,
        ));
    }

}