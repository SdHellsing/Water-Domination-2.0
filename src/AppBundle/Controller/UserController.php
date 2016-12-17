<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Platform;
use AppBundle\Entity\User;
use AppBundle\Form\UserType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;


/**
 * Class UserController
 * @package AppBundle\Controller
 * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
 */

class UserController extends Controller
{

    /*
     * Definition for rectangle type of map
     */
    const MIN_X = 0;
    const MAX_X = 1000;
    const MIN_Y = 0;
    const MAX_Y = 500;

    const MAX_PLATFORMS = 3;

    /**
     * @Security("is_granted('IS_AUTHENTICATED_ANONYMOUSLY')")
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/register/", name="register_user")
     * @Method("GET")
     */

    public function register()
    {

        $form = $this->createForm(UserType::class);

        return $this->render('user/register.html.twig', [
            'form' => $form->createView()
        ]);

    }

    /**
     * @Security("is_granted('IS_AUTHENTICATED_ANONYMOUSLY')")
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/register/", name="register_user_post")
     * @Method("POST")
     */

    public function registerPost(Request $request)
    {

        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            /*
             * Encrypting & setting the password
             */
            $password = $this->get('security.password_encoder')
                ->encodePassword($user, $user->getPassword());

            $user->setPassword($password);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();


            /*
           * Randomizing the coordinates for the 3 platforms generated initially for the user
           * Checking if the randomized coordinates already exist
           */

            $platformRepository = $this->getDoctrine()->getRepository(Platform::class);

            for ($i = 0; $i < self::MAX_PLATFORMS; $i++) {
                $x = -1;
                $y = -1;
                do {
                    $x = rand(self::MIN_X, self::MAX_X);
                    $y = rand(self::MIN_Y, self::MAX_Y);
                    $usedPlatform = $platformRepository->findOneBy([
                        'x' => $x,
                        'y' => $y
                    ]);
                } while ($usedPlatform !== null);

                $platform = new Platform();
                $platform->setX($x);
                $platform->setY($y);
                $platform->setName($user->getName() . '_' . ($i + 1));
                $platform->setUser($user);

                $entityManager->persist($platform);
                $entityManager->flush();

            }

            return $this->redirectToRoute('security_login');

        } else {
            return $this->redirectToRoute('register_user');
        }

    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/dashboard", name="dashboard_action")
     */

    public function dashboardAction()
    {

        /**
         * @var User $user
         */
        $user = $this->getUser();
        $session = $this->get('session');
        $currentPlatform = $session->get('platform_id');


        return $this->render('pages/dashboard.html.twig', [
            'user' => $user,
            'platformID' => $currentPlatform
        ]);
    }

    /**
     * @Route("/player/change_platform/{id}", name="change_platform")
     * @param $id
     */

    public function changePlatform($id)
    {

        /** @var User $user */
        $user = $this->getUser();
        $pr = $this->getDoctrine()->getRepository(Platform::class);
        $platform = $pr->findOneBy([

            'id' => $id,
            'user' => $user
        ]);

        if ($platform === null) {
            return $this->redirectToRoute('security_logout');
        }

        $this->get('session')->set('platform_id', $id);
        return $this->redirectToRoute('dashboard_action');


    }

}
