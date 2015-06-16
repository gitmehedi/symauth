<?php

namespace Acme\DemoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Tag
 *
 * @ORM\Table("tag")
 * @ORM\Entity(repositoryClass="Acme\DemoBundle\Entity\TagRepository")
 */
class Tag
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
     * @ORM\Column(name="name", type="string", length=255)
     */
    public $name;

    /**
     * @ORM\ManyToOne(targetEntity="Task", inversedBy="tags")
     */
    private $task;

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
     * @return Tag
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
     * Set task
     *
     * @param \Acme\DemoBundle\Entity\Task $task
     * @return Tag
     */
    public function setTask(\Acme\DemoBundle\Entity\Task $task = null)
    {
        $this->task = $task;

        return $this;
    }

    /**
     * Get task
     *
     * @return \Acme\DemoBundle\Entity\Task 
     */
    public function getTask()
    {
        return $this->task;
    }

}
