<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class HomeController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {

        $user = $this->getUser();
        if ($user) {
            /**
             * @var User $user
             */
            $session = $this->get('session');
            if (!$session->has('platform_id')) {
                $session->set('platform_id', $user->getPlatforms()[0]->getId());
            }
        }

        return $this->render('pages/home.html.twig', [
            'user' => $user
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
}
