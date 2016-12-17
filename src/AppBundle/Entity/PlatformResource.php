<?php

namespace AppBundle\Entity;

use AppBundle\Repository\ResourceRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * PlatformResource
 *
 * @ORM\Table(name="platform_resource")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PlatformResourceRepository")
 */
class PlatformResource
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
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Platform", inversedBy="resources")
     * @ORM\JoinColumn(name="platform_id")
     */

    private $platform;

    /**
     * @var GameResource
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\GameResource", inversedBy="platformResources")
     * @ORM\JoinColumn(name="resource_id")
     */

    private $resource;

    /**
     * @var int
     *
     * @ORM\Column(name="amount", type="integer")
     */
    private $amount;


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
     * Set amount
     *
     * @param integer $amount
     *
     * @return PlatformResource
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * Get amount
     *
     * @return int
     */
    public function getAmount()
    {
        return $this->amount;
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
     * @return GameResource
     */
    public function getResource()
    {
        return $this->resource;
    }

    /**
     * @param GameResource $resource
     */
    public function setResource($resource)
    {
        $this->resource = $resource;
    }


}

