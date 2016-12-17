<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * GameResource
 *
 * @ORM\Table(name="resources")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\GameResourceRepository")
 */
class GameResource
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, unique=true)
     */
    private $name;


    /**
     * @var BuildingCostResource[]
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\BuildingCostResource", mappedBy="resource")
     */

    private $buildingCosts;

    /**
     * @var PlatformResource[]
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\PlatformResource", mappedBy="resource")
     */

    private $platformResources;

    public function __construct()
    {
        $this->platformResources = new ArrayCollection();
        $this->platformResources = new ArrayCollection();
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return GameResource
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return PlatformResource[]
     */
    public function getPlatformResources()
    {
        return $this->platformResources;
    }

    /**
     * @param PlatformResource[] $platformResources
     */
    public function setPlatformResources(array $platformResources)
    {
        $this->platformResources = $platformResources;
    }

    /**
     * @return BuildingCostResource[]
     */
    public function getBuildingCosts()
    {
        return $this->buildingCosts;
    }

    /**
     * @param BuildingCostResource[] $buildingCosts
     */
    public function setBuildingCosts($buildingCosts)
    {
        $this->buildingCosts = $buildingCosts;
    }


}

