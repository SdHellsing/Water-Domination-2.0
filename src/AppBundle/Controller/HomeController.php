<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class HomeController extends PlatformAwareController
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        /**
         * @var User $user
         */
        $user = $this->getUser();

        if($user){
            $message = 'Welcome,' . $user->getUsername();
        }
        else{
           $message = 'You are not logged in!';
        }
        return $this->render('pages/home.html.twig', [
            'message' => $message
        ]);

    }

    /**
     * @Route("/admin", name="adminpage")
     */
    public function adminAction(Request $request)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $role = $user->getRoles();
        $username = $user->getUsername();

        return $this->render('admin/index.html.twig', [
            'message' => 'Welcome!',
            'role' => $role[0],
            'username' => $username
        ]);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/dashboard", name="dashboard_action")
     */

    public function dashboardAction()
    {

        $user = $this->getUser();
        $currentPlatform = $this->getPlatform();
        if ($user) {
            /**
             * @var User $user
             */

            $session = $this->get('session');

            if (!$session->has('platform_id')) {
                $session->set('platform_id', $user->getPlatforms()[0]->getId());
            }

            $currentPlatform = $session->get('platform_id');
        }

            return $this->render('pages/dashboard.html.twig', [
                'user' => $user,
                'platformID' => $currentPlatform
            ]);
    }
}
