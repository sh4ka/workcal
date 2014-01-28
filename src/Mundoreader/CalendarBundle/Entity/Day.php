<?php

namespace Mundoreader\CalendarBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Day
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Mundoreader\CalendarBundle\Entity\DayRepository")
 */
class Day
{

    /**
     * @var integer
     *
     * @ORM\Id @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @param mixed $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @ORM\OneToOne(targetEntity="User", inversedBy="day", cascade="persist")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    protected $user;

    /**
     * @ORM\ManyToOne(targetEntity="Calendar", inversedBy="days")
     * @ORM\JoinColumn(name="calendar_id", referencedColumnName="id")
     */
    protected $calendar;

    /**
     * @ORM\OneToOne(targetEntity="Gift", mappedBy="day", cascade="persist")
     * @ORM\JoinColumn(name="gift_id", referencedColumnName="id")
     */
    protected $gift;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="date")
     */
    private $date;


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
     * Set date
     *
     * @param \DateTime $date
     * @return Day
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
     * Set calendar
     *
     * @param \Mundoreader\CalendarBundle\Entity\Calendar $calendar
     * @return Day
     */
    public function setCalendar(\Mundoreader\CalendarBundle\Entity\Calendar $calendar = null)
    {
        $this->calendar = $calendar;

        return $this;
    }

    /**
     * Get calendar
     *
     * @return \Mundoreader\CalendarBundle\Entity\Calendar 
     */
    public function getCalendar()
    {
        return $this->calendar;
    }

    /**
     * Set gift
     *
     * @param \Mundoreader\CalendarBundle\Entity\Gift $gift
     * @return Day
     */
    public function setGift(\Mundoreader\CalendarBundle\Entity\Gift $gift = null)
    {
        $this->gift = $gift;

        return $this;
    }

    /**
     * Get gift
     *
     * @return \Mundoreader\CalendarBundle\Entity\Gift 
     */
    public function getGift()
    {
        return $this->gift;
    }

    /**
     * Set id
     *
     * @param integer $id
     * @return Day
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    public function __toString(){
        return $this->getDate()->format('d-m-Y');
    }
}
