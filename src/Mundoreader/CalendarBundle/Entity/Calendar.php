<?php

namespace Mundoreader\CalendarBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Calendar
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Mundoreader\CalendarBundle\Entity\CalendarRepository")
 */
class Calendar
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
     * @ORM\Column(name="calendarId", type="string", length=32)
     */
    private $calendarId;


    /**
     * @ORM\OneToMany(targetEntity="Day", mappedBy="calendar")
     */
    protected $days;

    /**
     * @param mixed $users
     */
    public function setUsers($users)
    {
        $this->users = $users;
    }

    /**
     * @return mixed
     */
    public function getUsers()
    {
        return $this->users;
    }


    /**
     * @ORM\OneToMany(targetEntity="User", mappedBy="calendar")
     */
    protected $users;


    public function __construct(){
        $this->days = new ArrayCollection();
        $this->users = new ArrayCollection();
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
     * Set calendarId
     *
     * @param string $calendarId
     * @return Calendar
     */
    public function setCalendarId($calendarId)
    {
        $this->calendarId = $calendarId;

        return $this;
    }

    /**
     * Get calendarId
     *
     * @return string 
     */
    public function getCalendarId()
    {
        return $this->calendarId;
    }

    /**
     * Add days
     *
     * @param \Mundoreader\CalendarBundle\Entity\Day $days
     * @return Calendar
     */
    public function addDay(\Mundoreader\CalendarBundle\Entity\Day $days)
    {
        $this->days[] = $days;

        return $this;
    }

    /**
     * Remove days
     *
     * @param \Mundoreader\CalendarBundle\Entity\Day $days
     */
    public function removeDay(\Mundoreader\CalendarBundle\Entity\Day $days)
    {
        $this->days->removeElement($days);
    }

    /**
     * Get days
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getDays()
    {
        return $this->days;
    }
}
