<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use AppBundle\Form\UserType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {

        // replace this example code with whatever you need
        return $this->render('pages/home.html.twig', [
            'message' => 'Welcome!'
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

            $password = $this->get('security.password_encoder')
                ->encodePassword($user, $user->getPassword());

            $user->setPassword($password);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('security_login');

        } else {
            return $this->redirectToRoute('register_user');
        }

    }

}
