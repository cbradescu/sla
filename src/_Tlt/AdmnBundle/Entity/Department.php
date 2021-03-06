<?php

namespace Tlt\AdmnBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Department
 *
 * @ORM\Table(name="departments")
 * @ORM\Entity
 */
class Department
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

	
    /**
     * @ORM\OneToMany(targetEntity="Service", mappedBy="services")
     */
    public $services;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->services = new ArrayCollection();
    }
	
    /**
     * Set id
     *
     * @param integer $id
	 * @return Department
     */
    public function setId($id)
    {
        $this->id = $id;
		
		return $this;
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }
	
    /**
     * Set name
     *
     * @param string $name
     * @return Department
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
     * Add service
     *
     * @param \Tlt\AdmnBundle\Entity\Service $service
     * @return Department
     */
    public function addService(\Tlt\AdmnBundle\Entity\Service $service)
    {
        $this->services[] = $service;

        return $this;
    }

    /**
     * Remove service
     *
     * @param \Tlt\AdmnBundle\Entity\Service $service
     */
    public function removeService(\Tlt\AdmnBundle\Entity\Service $service)
    {
        $this->services->removeElement($service);
    }

    /**
     * Get services
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getServices()
    {
        return $this->services;
    }

	public function __toString()
	{
		return $this->getName();
	}
}
