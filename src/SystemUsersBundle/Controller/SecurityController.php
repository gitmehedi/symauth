<?php

namespace SystemUsersBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Symfony\Component\HttpFoundation\Request;

class SecurityController extends Controller
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

        if ($this->container->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->redirect($this->generateUrl('users'));
        }
        // last username entered by the user
        $lastUsername = (null === $session) ? '' : $session->get(SecurityContextInterface::LAST_USERNAME);
//        if (strlen($error) > 1) {
        return $this->render(
                        'SystemUsersBundle:Security:login.html.twig', array(
                    // last username entered by the user
                    'last_username' => $lastUsername,
                    'error'         => $error,
                        )
        );
//        } else {
//            return $this->redirect($this->generateUrl('agent_create'));
//        }
    }

    /**
     *  Login Check action handles user registered  data 
     */
    public function loginCheckAction()
    {
        
    }

    /**
     * Logout action handle logout functionality of the system
     */
    public function logoutAction()
    {
        
    }

    /**
     * Register and un-registered users home page
     * After reidrection always shows this page
     * 
     * @return type
     */
    public function secureDashboardAction()
    {
        return $this->render('SystemUsersBundle:Login:userDashboard.html.twig');
    }

}
