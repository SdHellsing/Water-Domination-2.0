<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Building
 *
 * @ORM\Table(name="building")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\BuildingRepository")
 */
class Building
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
     * @var BuildingCostResource[]
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\BuildingCostResource", mappedBy="building")
     */

    private $costs;

    /**
     * @var BuildingCostTime[]
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\BuildingCostTime", mappedBy="building")
     */

    private $timeCosts;


    /**
     * @var PlatformBuilding[]
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\PlatformBuilding", mappedBy="building")
     */

    private $platformBuildings;


    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, unique=true)
     */
    private $name;

    /**
     * @var int
     *
     * @ORM\Column(name="base_income", type="integer", length=255, unique=false)
     */
    private $baseIncome;

    public  function __construct()
    {
        $this->costs = new ArrayCollection();
        $this->platformBuildings = new ArrayCollection();
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
     * @return Building
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
     * @return BuildingCostResource[]
     */
    public function getCosts()
    {
        return $this->costs;
    }

    /**
     * @param BuildingCostResource[] $costs
     */
    public function setCosts($costs)
    {
        $this->costs = $costs;
    }

    /**
     * @return BuildingCostTime
     */
    public function getTimeCosts()
    {
        return $this->timeCosts;
    }

    /**
     * @param BuildingCostTime $timeCosts
     */
    public function setTimeCosts($timeCosts)
    {
        $this->timeCosts = $timeCosts;
    }

    /**
     * @return PlatformBuilding[]
     */
    public function getPlatformBuildings()
    {
        return $this->platformBuildings;
    }

    /**
     * @param PlatformBuilding[] $platformBuildings
     */
    public function setPlatformBuildings($platformBuildings)
    {
        $this->platformBuildings = $platformBuildings;
    }

    /**
     * @return int
     */
    public function getBaseIncome()
    {
        return $this->baseIncome;
    }

    /**
     * @param int $baseIncome
     */
    public function setBaseIncome($baseIncome)
    {
        $this->baseIncome = $baseIncome;
    }



}

