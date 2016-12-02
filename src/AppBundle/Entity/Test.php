<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Test
 *
 * @ORM\Table(name="tests")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\TestRepository")
 */
class Test
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
     * @ORM\Column(name="category", type="string", length=255)
     */
    private $category;

    /**
     * @var string
     *
     * @ORM\Column(name="correct", type="string", length=255)
     */
    private $correct;

    /**
     * @var string
     *
     * @ORM\Column(name="incorrect", type="string", length=255)
     */
    private $incorrect;

    /**
     * @var int
     *
     * @ORM\Column(name="time_spent", type="integer")
     */
    private $timeSpent;

    /**
     * @var int
     * @ORM\ManyToOne(targetEntity="User", inversedBy="tests")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $userId;

    /**
     * @var \DateTime
     * @ORM\Column(name="finised_at", type="datetime")
     */
    private $finisedAt;

    public function __construct($user, $time, $answers, $category = 'Random')
    {
        $this->timeSpent = $time->s + $time->i * 60 + $time->h * 3600;
        $this->category = $category;
        $this->correct = $this->incorrect = 0;
        $this->userId = $user;
        $this->finisedAt = new \DateTime();

        foreach ($answers as $answer)
        {
            if($answer == true){
                $this->correct++;
            }

            else{
                $this->incorrect++;
            }
        }
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
     * Set category
     *
     * @param string $category
     *
     * @return Test
     */
    public function setCategory($category)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getFinisedAt(): \DateTime
    {
        return $this->finisedAt;
    }

    /**
     * @param \DateTime $finisedAt
     * @return Test
     */
    public function setFinisedAt(\DateTime $finisedAt): Test
    {
        $this->finisedAt = $finisedAt;
        return $this;
    }


    /**
     * Get category
     *
     * @return string
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Set correct
     *
     * @param string $correct
     *
     * @return Test
     */
    public function setCorrect($correct)
    {
        $this->correct = $correct;

        return $this;
    }

    /**
     * Get correct
     *
     * @return string
     */
    public function getCorrect()
    {
        return $this->correct;
    }

    /**
     * Set incorrect
     *
     * @param string $incorrect
     *
     * @return Test
     */
    public function setIncorrect($incorrect)
    {
        $this->incorrect = $incorrect;

        return $this;
    }

    /**
     * Get incorrect
     *
     * @return string
     */
    public function getIncorrect()
    {
        return $this->incorrect;
    }

    /**
     * Set timeSpent
     *
     * @param integer $timeSpent
     *
     * @return Test
     */
    public function setTimeSpent($timeSpent)
    {
        $this->timeSpent = $timeSpent;

        return $this;
    }

    /**
     * Get timeSpent
     *
     * @return int
     */
    public function getTimeSpent()
    {
        return $this->timeSpent;
    }

    /**
     * Set userId
     *
     * @param integer $userId
     *
     * @return Test
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     * Get userId
     *
     * @return int
     */
    public function getUserId()
    {
        return $this->userId;
    }

    public function __toString()
    {
        return $this->category;
    }
}

