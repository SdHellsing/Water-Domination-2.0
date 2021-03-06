<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Platform
 *
 * @ORM\Table(name="platform")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PlatformRepository")
 */
class Platform
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
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var int
     *
     * @ORM\Column(name="x", type="integer")
     */
    private $x;

    /**
     * @var int
     *
     * @ORM\Column(name="y", type="integer")
     */
    private $y;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User", inversedBy="platforms")
     * @ORM\JoinColumn(name="user_id")
     */

    private $user;

    /**
     * @var PlatformResource[]
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\PlatformResource", mappedBy="platform")
     */

    private $resources;

    /**
     * @var PlatformBuilding[]
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\PlatformBuilding", mappedBy="platform")
     */

    private $buildings;

    public function __construct()
    {
        $this->resources = new ArrayCollection();
        $this->buildings = new ArrayCollection();
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
     * @return Platform
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
     * Set x
     *
     * @param integer $x
     *
     * @return Platform
     */
    public function setX($x)
    {
        $this->x = $x;

        return $this;
    }

    /**
     * Get x
     *
     * @return int
     */
    public function getX()
    {
        return $this->x;
    }

    /**
     * Set y
     *
     * @param integer $y
     *
     * @return Platform
     */
    public function setY($y)
    {
        $this->y = $y;

        return $this;
    }

    /**
     * Get y
     *
     * @return int
     */
    public function getY()
    {
        return $this->y;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param User $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * @return PlatformResource[]
     */
    public function getResources()
    {
        return $this->resources;
    }

    /**
     * @param PlatformResource[] $resources
     */
    public function setResources($resources)
    {
        $this->resources = $resources;
    }

    /**
     * @return PlatformBuilding[]
     */
    public function getBuildings()
    {
        return $this->buildings;
    }

    /**
     * @param PlatformBuilding[] $buildings
     */
    public function setBuildings($buildings)
    {
        $this->buildings = $buildings;
    }





}

