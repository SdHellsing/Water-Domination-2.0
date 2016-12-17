<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Building;
use AppBundle\Entity\GameResource;
use AppBundle\Entity\Platform;
use AppBundle\Entity\PlatformBuilding;
use AppBundle\Entity\PlatformResource;
use AppBundle\Repository\ResourceRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Class BuildingsController
 * @package AppBundle\Controller
 * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
 */
class BuildingsController extends PlatformAwareController
{

    /**
     * @Route("/buildings", name="buildings_list")
     * @return \Symfony\Component\HttpFoundation\Response
     *
     */

    public function indexAction()
    {
        $platform = $this->getDoctrine()->getRepository(Platform::class)->find($this->getPlatform());
        $resources = $this->getDoctrine()->getRepository(GameResource::class)->findAll();
        return $this->render('buildings/index.html.twig', [
            'buildings' => $platform->getBuildings(),
            'resources' => $resources
        ]);
    }

    /**
     * @Route("/buildings/evolve/{id}", name="evolve_building")
     * @param $id
     */

    public function evolve($id){

        //initialize the platform and the building based on the current selected

        $platform = $this->getDoctrine()->getRepository(Platform::class)->find($this->getPlatform());
        $building = $this->getDoctrine()->getRepository(Building::class)->find($id);
        $platformBuilding = $this->getDoctrine()->getRepository(PlatformBuilding::class)
            ->findOneBy([
                'platform'=>$platform,'building'=>$building
            ]);
        $entityManager = $this->getDoctrine()->getManager();

        //Get the level of a building

        $currentLevel = $platformBuilding->getLevel();

        //get the resource type costs for the building e.g. 1000 Metal and 1000 Stone

        $costs = $building->getCosts();



        $allResources = [];

        foreach ($costs as $cost) {
            $resourcesInPlatform = $this->getDoctrine()->getRepository(PlatformResource::class)->findOneBy([
                'resource' => $cost->getResource(),
                'platform' => $platform
            ]);

            //check if we have enough resources to build this building

            if ($resourcesInPlatform->getAmount() >= ($cost->getAmount() * ($currentLevel + 1))) {

                $allResources[$cost->getResource()->getName()] = ($cost->getAmount() * ($currentLevel + 1));

            } else {
                return $this->redirectToRoute("buildings_list");
            }
        }

        /**
         * @var PlatformResource[] $platformResources
         */

        $platformResources = $this->getDoctrine()->getRepository(PlatformResource::class)
            ->findBy([
                'platform' => $platform
            ]);

        foreach ($platformResources as $platformResource) {
            $name = $platformResource->getResource()->getName();
            $cost = $allResources[$name];
            $platformResource->setAmount(
                $platformResource->getAmount() - $cost
            );
            $entityManager->persist($platformResource);
            $entityManager->flush();
        }

        $platformBuilding->setLevel($currentLevel + 1);
        $entityManager->persist($platformBuilding);
        $entityManager->flush();


        return $this->redirectToRoute('buildings_list');


    }


}