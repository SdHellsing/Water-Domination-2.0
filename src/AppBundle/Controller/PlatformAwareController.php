<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Building;
use AppBundle\Entity\BuildingBaseIncome;
use AppBundle\Entity\GameResource;
use AppBundle\Entity\Platform;
use AppBundle\Entity\PlatformBuilding;
use AppBundle\Entity\PlatformResource;
use AppBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class PlatformAwareController extends Controller
{

    const MINUTES_TO_INCOME = 2;

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
        $session = $this->get('session');
        $currentTime = time();
        $passedTime = 0;
        $timeOfLastIncomeUpdate = $session->get('lastresourceupdate');
        $entityManager = $this->getDoctrine()->getManager();

        if ($timeOfLastIncomeUpdate) {

            if ($currentTime > $timeOfLastIncomeUpdate) {
                //how much minutes passed after the last resource update
                $difference = intval(($currentTime - $timeOfLastIncomeUpdate));
                $passedTime = intval($difference / 60); //min
            }
            if ($passedTime >= 2) {

                $updateMultiplier = intval($passedTime / (self::MINUTES_TO_INCOME));
                $resourceRepository = $this->getDoctrine()->getRepository(PlatformResource::class);
                $platformResources = $resourceRepository->findBy([
                    'platform' => $platform
                ]);

                foreach ($platformResources as $platformResource) {
                    $platformBuildings = $this->getDoctrine()->getRepository(PlatformBuilding::class)
                        ->findBy([
                            'platform' => $platform,
                        ]);

                    foreach ($platformBuildings as $platformBuilding) {
                        $newResourceValue = 0;
                        $level = $platformBuilding->getLevel();
                        $id = $platformBuilding->getBuilding()->getId();
                        $baseIncome = $this->getDoctrine()->getRepository(Building::class)
                            ->findOneBy([
                                'id' => $id
                            ])->getBaseIncome();

                        $newResourceValue = ($platformResource->getAmount()) + ($updateMultiplier * ((($level) * $baseIncome) / 30));


                        $platformResource->setAmount(intval($newResourceValue));
                        $entityManager->persist($platformResource);
                        $entityManager->flush();

                    }
                }
                $session->set('lastresourceupdate', $currentTime);
            }


        }
        return $this->render('platforms/partials/resources.html.twig', ['platform' => $platform]
        );

    }

}
