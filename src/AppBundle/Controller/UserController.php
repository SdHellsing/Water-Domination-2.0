<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Building;
use AppBundle\Entity\GameResource;
use AppBundle\Entity\Platform;
use AppBundle\Entity\PlatformBuilding;
use AppBundle\Entity\PlatformResource;
use AppBundle\Entity\User;
use AppBundle\Form\PasswordChange;
use AppBundle\Form\UserEditProfile;
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

class UserController extends PlatformAwareController
{

    /*
     * Definition for rectangle type of map
     */
    const MIN_X = 0;
    const MAX_X = 1000;
    const MIN_Y = 0;
    const MAX_Y = 500;

    const MAX_PLATFORMS = 3;
    const INITIAL_RESOURCES = 10000;

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


                //resource initialization

                $resourceRepository = $this->getDoctrine()->getRepository(GameResource::class);
                $resourceTypes=$resourceRepository->findAll();

                foreach ($resourceTypes as $resourceType){
                    $platformResource = new PlatformResource();
                    $platformResource->setResource($resourceType);
                    $platformResource->setPlatform($platform);
                    $platformResource->setAmount(self::INITIAL_RESOURCES);
                    $entityManager->persist($platformResource);
                    $entityManager->flush();

                }

                $buildingRepository = $this->getDoctrine()->getRepository(Building::class);
                $buildingTypes=$buildingRepository->findAll();

                foreach ($buildingTypes as $buildingType){
                    $platformBuilding = new PlatformBuilding();
                    $platformBuilding->setPlatform($platform);
                    $platformBuilding->setBuilding($buildingType);
                    $platformBuilding->setLevel(0);
                    $entityManager->persist($platformBuilding);
                    $entityManager->flush();
                }

            }

            return $this->redirectToRoute('security_login');

        } else {
            return $this->redirectToRoute('register_user');
        }

    }

    /**
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @Route("/edit/profile/{id}", name="profile_edit_action_post")
     *
     * @param Request $request
     * @param $id
     * @Method("POST")
     */

    public function editProfilePost(Request $request, $id){

        /** @var User $user */

        $user = $this->getUser();
        $userEditForm = $this->createForm(UserEditProfile::class, $user);
        $userEditForm->handleRequest($request);

        if ($userEditForm->isSubmitted() && $userEditForm->isValid()) {

            $entityManager = $this->getDoctrine()->getManager();
            $userEntry = $entityManager->getRepository(User::class)->findOneBy([
                'id' => $id
            ]);

            if (!$userEntry) {
                return $this->redirectToRoute('profile_edit_action');
            }

            $entityManager->merge($user);
            $entityManager->flush();
        }
        return $this->redirectToRoute('profile_edit_action', [
            'id' => $id
        ]);

    }

    /**
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @Route("/change/password/", name="password_change_action_post")
     *
     * @param Request $request
     * @Method("POST")
     */

    public function changePasswordPost(Request $request){


        /** @var User $user */

        $user = $this->getUser();
        $userID = $user->getId();
        $changePasswordForm = $this->createForm(PasswordChange::class, $user);
        $changePasswordForm->handleRequest($request);

        $passwordToEdit = $user->getPassword();

        if ($passwordToEdit) {
            $password = $this->get('security.password_encoder')
                ->encodePassword($user, $passwordToEdit);
            $user->setPassword($password);
        }

        if ($changePasswordForm->isSubmitted() && $changePasswordForm->isValid()) {

            $entityManager = $this->getDoctrine()->getManager();
            $userEntry = $entityManager->getRepository(User::class)->findOneBy([
                'id' => $userID
            ]);

            if (!$userEntry) {
                return $this->redirectToRoute('password_change_action');
            }

            $entityManager->merge($user);
            $entityManager->flush();
        }
        return $this->redirectToRoute('password_change_action');

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
