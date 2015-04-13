<?php

namespace SystemUsersBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use SystemUsersBundle\Controller\AppController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\Security\Core\SecurityContextInterface;
use SystemUsersBundle\Entity\User;

class LoginController extends AppController
{

    public function loginAction(Request $request)
    {
        $session = $request->getSession();

        // get the login error if there is one
        if ($request->attributes->has(SecurityContextInterface::AUTHENTICATION_ERROR)) {
            $error = $request->attributes->get(
                    SecurityContextInterface::AUTHENTICATION_ERROR
            );
        } elseif (null !== $session && $session->has(SecurityContextInterface::AUTHENTICATION_ERROR)) {
            $error = $session->get(SecurityContextInterface::AUTHENTICATION_ERROR);
            $session->remove(SecurityContextInterface::AUTHENTICATION_ERROR);
        } else {
            $error = '';
        }

//        if ($this->container->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY')) {
//        return $this->redirect($this->generateUrl('new_user_register'));
//        }
        // last username entered by the user
        $lastUsername = (null === $session) ? '' : $session->get(SecurityContextInterface::LAST_USERNAME);
//        if (strlen($error) > 1) {
        return $this->render(
                        'SporshoUserBundle:Login:login.html.twig', array(
                    // last username entered by the user
                    'last_username' => $lastUsername,
                    'error'         => $error,
                        )
        );
//        } else {
//            return $this->redirect($this->generateUrl('agent_create'));
//        }
    }

    public function loginCheckAction()
    {
        
    }

    public function logoutAction()
    {
        
    }

}
