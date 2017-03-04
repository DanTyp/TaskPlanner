<?php

namespace TaskPlannerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Task
 *
 * @ORM\Table(name="task")
 * @ORM\Entity(repositoryClass="TaskPlannerBundle\Repository\TaskRepository")
 */
class Task
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
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255)
     */
    private $description;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="date")
     */
    private $date;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="deadline", type="date")
     */
    private $deadline;

    /**
     * @var int
     *
     * @ORM\Column(name="priority", type="integer")
     */
    private $priority;

    /**
     * @var string
     *
     * @ORM\Column(name="attach", type="string", length=255, nullable=true)
     */
    private $attach;
    
    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="tasks")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     * @var type 
     */
    private $user;
    
    /**
     * @ORM\OneToMany(targetEntity="Commentary", mappedBy="task")
     * @var type 
     */
    private $commentaries;
    
    /**
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="tasks")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id")
     * @var type 
     */
    private $category;
    
    /**
     * @ORM\Column(name="task_status", type="integer", nullable=true)
     * @var type 
     */
    private $status;


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
     * @return Task
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
     * Set description
     *
     * @param string $description
     * @return Task
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     * @return Task
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime 
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set deadline
     *
     * @param \DateTime $deadline
     * @return Task
     */
    public function setDeadline($deadline)
    {
        $this->deadline = $deadline;

        return $this;
    }

    /**
     * Get deadline
     *
     * @return \DateTime 
     */
    public function getDeadline()
    {
        return $this->deadline;
    }

    /**
     * Set priority
     *
     * @param integer $priority
     * @return Task
     */
    public function setPriority($priority)
    {
        $this->priority = $priority;

        return $this;
    }

    /**
     * Get priority
     *
     * @return integer 
     */
    public function getPriority()
    {
        return $this->priority;
    }

    /**
     * Set attach
     *
     * @param string $attach
     * @return Task
     */
    public function setAttach($attach)
    {
        $this->attach = $attach;

        return $this;
    }

    /**
     * Get attach
     *
     * @return string 
     */
    public function getAttach()
    {
        return $this->attach;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->commentaries = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set user
     *
     * @param \TaskPlannerBundle\Entity\User $user
     * @return Task
     */
    public function setUser(\TaskPlannerBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \TaskPlannerBundle\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Add commentaries
     *
     * @param \TaskPlannerBundle\Entity\Commentary $commentaries
     * @return Task
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

    /**
     * Set category
     *
     * @param \TaskPlannerBundle\Entity\Category $category
     * @return Task
     */
    public function setCategory(\TaskPlannerBundle\Entity\Category $category = null)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return \TaskPlannerBundle\Entity\Category 
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Set status
     *
     * @param integer $status
     * @return Task
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return integer 
     */
    public function getStatus()
    {
        return $this->status;
    }
}
