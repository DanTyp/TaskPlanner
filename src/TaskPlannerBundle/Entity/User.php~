<?php

namespace TaskPlannerBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection; //to musimy wczytać, żeby móc korzystać z ArrayCollection



/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 */
class User extends BaseUser {

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
    public function __construct() {
        parent::__construct();
        $this->tasks = new ArrayCollection(); //jeżeli nie generowaliśmy ten encji, to musimy samo to dopisać
        $this->categories = new ArrayCollection();
        $this->commentaries = new ArrayCollection();
    }    
    
    /**
     * @ORM\OneToMany(targetEntity="Task", mappedBy="user")
     */
    private $tasks;
    
    /**
     * @ORM\OneToMany(targetEntity="Category", mappedBy="user")
     */
    private $categories;
    
    /**
     * @ORM\OneToMany(targetEntity="Commentary", mappedBy="user")
     * @var type 
     */
    private $commentaries;



    /**
     * Add tasks
     *
     * @param \TaskPlannerBundle\Entity\Task $tasks
     * @return User
     */
    public function addTask(\TaskPlannerBundle\Entity\Task $tasks)
    {
        $this->tasks[] = $tasks;

        return $this;
    }

    /**
     * Remove tasks
     *
     * @param \TaskPlannerBundle\Entity\Task $tasks
     */
    public function removeTask(\TaskPlannerBundle\Entity\Task $tasks)
    {
        $this->tasks->removeElement($tasks);
    }

    /**
     * Get tasks
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getTasks()
    {
        return $this->tasks;
    }

    /**
     * Add categories
     *
     * @param \TaskPlannerBundle\Entity\Category $categories
     * @return User
     */
    public function addCategory(\TaskPlannerBundle\Entity\Category $categories)
    {
        $this->categories[] = $categories;

        return $this;
    }

    /**
     * Remove categories
     *
     * @param \TaskPlannerBundle\Entity\Category $categories
     */
    public function removeCategory(\TaskPlannerBundle\Entity\Category $categories)
    {
        $this->categories->removeElement($categories);
    }

    /**
     * Get categories
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCategories()
    {
        return $this->categories;
    }

    /**
     * Add commentaries
     *
     * @param \TaskPlannerBundle\Entity\Commentary $commentaries
     * @return User
     */
    public function addCommentary(\TaskPlannerBundle\Entity\Commentary $commentaries)
    {
        $this->commentaries[] = $commentaries;

        return $this;
    }

    /**
     * Remove commentaries
     *
     * @param \TaskPlannerBundle\Entity\Commentary $commentaries
     */
    public function removeCommentary(\TaskPlannerBundle\Entity\Commentary $commentaries)
    {
        $this->commentaries->removeElement($commentaries);
    }

    /**
     * Get commentaries
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCommentaries()
    {
        return $this->commentaries;
    }
}
