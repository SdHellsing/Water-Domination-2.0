<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Platform;
use AppBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class PlatformAwareController extends Controller
{
    public function getPlatform()
    {
        $session = $this->get('session');
        /**
         * @var User $user
         */
        $user = $this->getUser();
        $platform = $session->get('platform_id');
        if ($platform == null) {
            $platform = $user->getPlatforms()[0]->getId();
            $session->set('platform_id', $platform);
        }

        return $platform;

    }

    public function resourceAction()
    {
        $id = $this->getPlatform();
        $platform = $this->getDoctrine()->getRepository(Platform::class)->find($id);

        return $this->render('platforms/partials/resources.html.twig', [
                'platform' => $platform
            ]
        );

    }

}
