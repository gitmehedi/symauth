<?php

namespace SystemUsersBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Resources
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="SystemUsersBundle\Entity\ResourcesRepository")
 */
class Resources
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
     *
     * @ORM\Column(name="controller_name", type="string", length=255)
     */
    private $controllerName;

    /**
     * @var string
     *
     * @ORM\Column(name="action_name", type="string", length=255)
     */
    private $actionName;

    /**
     * @var string
     *
     * @ORM\Column(name="route_name", type="string", length=255, nullable=true)
     */
    private $routeName;

    /**
     * @var boolean
     *
     * @ORM\Column(name="status", type="boolean", nullable=true)
     */
    private $status;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime")
     */
    private $updatedAt;


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
     * Set controllerName
     *
     * @param string $controllerName
     * @return Resources
     */
    public function setControllerName($controllerName)
    {
        $this->controllerName = $controllerName;

        return $this;
    }

    /**
     * Get controllerName
     *
     * @return string 
     */
    public function getControllerName()
    {
        return $this->controllerName;
    }

    /**
     * Set actionName
     *
     * @param string $actionName
     * @return Resources
     */
    public function setActionName($actionName)
    {
        $this->actionName = $actionName;

        return $this;
    }

    /**
     * Get actionName
     *
     * @return string 
     */
    public function getActionName()
    {
        return $this->actionName;
    }

    /**
     * Set routeName
     *
     * @param string $routeName
     * @return Resources
     */
    public function setRouteName($routeName)
    {
        $this->routeName = $routeName;

        return $this;
    }

    /**
     * Get routeName
     *
     * @return string 
     */
    public function getRouteName()
    {
        return $this->routeName;
    }

    /**
     * Set status
     *
     * @param boolean $status
     * @return Resources
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return boolean 
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return Resources
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime 
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     * @return Resources
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime 
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }
}
