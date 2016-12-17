<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PlatformBuilding
 *
 * @ORM\Table(name="platform_building")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PlatformBuildingRepository")
 */
class PlatformBuilding
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
     * @var Platform
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Platform", inversedBy="buildings")
     * @ORM\JoinColumn(name="platform_id")
     */

    private $platform;

    /**
     * @var Building
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Building", inversedBy="platformBuildings")
     * @ORM\JoinColumn(name="building_id")
     */

    private $building;

    /**
     * @var int
     *
     * @ORM\Column(name="level", type="integer")
     */
    private $level;

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
     * Set level
     *
     * @param integer $level
     *
     * @return PlatformBuilding
     */
    public function setLevel($level)
    {
        $this->level = $level;

        return $this;
    }

    /**
     * Get level
     *
     * @return int
     */
    public function getLevel()
    {
        return $this->level;
    }

    /**
     * @return Platform
     */
    public function getPlatform()
    {
        return $this->platform;
    }

    /**
     * @param Platform $platform
     */
    public function setPlatform($platform)
    {
        $this->platform = $platform;
    }

    /**
     * @return Building
     */
    public function getBuilding()
    {
        return $this->building;
    }

    /**
     * @param Building $building
     */
    public function setBuilding($building)
    {
        $this->building = $building;
    }


}

