<?php

namespace AppBundle\Entity;

use Doctrine\Common\Annotations\Annotation\Enum;
use Doctrine\ORM\Mapping as ORM;

/**
 * QuestionReport
 *
 * @ORM\Table(name="question_reports")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\QuestionReportRepository")
 */
class QuestionReport
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
     * @var int
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Question", inversedBy="reports")
     * @ORM\JoinColumn(name="question_id", referencedColumnName="id")
     */
    private $questionId;

    /**
     * @var int
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User", inversedBy="reports")
     * @ORM\JoinColumn(name="created_by", referencedColumnName="id")
     */
    private $created_by;

    /**
     * @var string
     *
     * @ORM\Column(name="reason", type="string", length=255)
     */
    private $reason;

    /**
     * @ORM\Column(type="string", columnDefinition="ENUM('Submitted', 'Approved', 'Denied')")
     */
    private $status;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $created_at;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime")
     */
    private $updated_at;

    public function __construct()
    {
        $this->status = 'Submitted';
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
     * Set questionId
     *
     * @param integer $questionId
     *
     * @return QuestionReport
     */
    public function setQuestionId($questionId)
    {
        $this->questionId = $questionId;

        return $this;
    }

    /**
     * Get questionId
     *
     * @return int
     */
    public function getQuestionId()
    {
        return $this->questionId;
    }

    /**
     * @return int
     */
    public function getCreatedBy()
    {
        return $this->created_by;
    }

    /**
     * @param User $created_by
     * @return QuestionReport
     */
    public function setCreatedBy($created_by): QuestionReport
    {
        $this->created_by = $created_by;
        return $this;
    }


    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param mixed $status
     * @return QuestionReport
     */
    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }

    /**
     * @return \DateTime|null
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * @param \DateTime $created_at
     * @return QuestionReport
     */
    public function setCreatedAt(\DateTime $created_at): QuestionReport
    {
        $this->created_at = $created_at;
        return $this;
    }

    /**
     * @return \DateTime|null
     */
    public function getUpdatedAt()
    {
        return $this->updated_at;
    }

    /**
     * @param \DateTime $updated_at
     * @return QuestionReport
     */
    public function setUpdatedAt(\DateTime $updated_at): QuestionReport
    {
        $this->updated_at = $updated_at;
        return $this;
    }

    /**
     * Set reason
     *
     * @param string $reason
     *
     * @return QuestionReport
     */
    public function setReason($reason)
    {
        $this->reason = $reason;

        return $this;
    }

    /**
     * Get reason
     *
     * @return string
     */
    public function getReason()
    {
        return $this->reason;
    }

    public function __toString()
    {
        return $this->reason;
    }
}

