<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use AppBundle\Form\PasswordChange;
use AppBundle\Form\UserEditProfile;
use AppBundle\Form\UserType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
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
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @Route("/dashboard", name="dashboard_action")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */

    public function dashboardAction()
    {
        $user = $this->getUser();

        $currentPlatform = $this->getPlatform();
            return $this->render('pages/dashboard.html.twig', [
                'user' => $user,
                'platformID' => $currentPlatform
            ]);
    }

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
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @Route("/edit/profile/{id}", name="profile_edit_action")
     *
     * @param Request $request
     * @param $id
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *  @Method("GET")
     */

    public function profileEditAction(Request $request, $id){

        $user = $this->getUser();
        $profileEditForm = $this->createForm(UserEditProfile::class)->createView();

        return $this->render('user/edit_profile.html.twig', [
            'profileEditForm' => $profileEditForm,
            'user' => $user
        ]);
    }

    /**
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @Route("/change/password/", name="password_change_action")
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     *  @Method("GET")
     */

    public function passwordEditAction(Request $request){

        $user = $this->getUser();
        $passwordChangeForm = $this->createForm(PasswordChange::class)->createView();

        return $this->render('user/password_change.html.twig', [
            'passwordChangeForm' => $passwordChangeForm,
            'user' => $user
        ]);
    }
}
