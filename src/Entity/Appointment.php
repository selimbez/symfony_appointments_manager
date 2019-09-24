<?php

namespace App\Entity;

use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AppointmentRepository")
 */
class Appointment
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @var string
     * @ORM\Column(type="string", length=255)
     */
    private $customer;

    /**
     * @var DateTimeInterface
     * @Assert\DateTime()
     * @ORM\Column(type="datetime")
     */
    private $startDatetime;

    /**
     * @var DateTimeInterface
     * @Assert\DateTime()
     * @Assert\GreaterThan(propertyPath="startDatetime")
     * @ORM\Column(type="datetime")
     */
    private $endDatetime;

    /**
     * @var User
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="appointments")
     * @ORM\JoinColumn(nullable=false)
     */
    private $assignee;

    /**
     * @ORM\Column(type="boolean")
     */
    private $complete;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getCustomer(): ?string
    {
        return $this->customer;
    }

    public function setCustomer(string $customer): self
    {
        $this->customer = $customer;

        return $this;
    }

    public function getStartDatetime(): ?DateTimeInterface
    {
        return $this->startDatetime;
    }

    public function setStartDatetime(DateTimeInterface $startDatetime): self
    {
        $this->startDatetime = $startDatetime;

        return $this;
    }

    public function getEndDatetime(): ?DateTimeInterface
    {
        return $this->endDatetime;
    }

    public function setEndDatetime(DateTimeInterface $endDatetime): self
    {
        $this->endDatetime = $endDatetime;

        return $this;
    }

    public function getAssignee(): ?User
    {
        return $this->assignee;
    }

    public function setAssignee(?User $assignee): self
    {
        $this->assignee = $assignee;

        return $this;
    }

    public function getComplete(): ?bool
    {
        return $this->complete;
    }

    public function setComplete(bool $complete): self
    {
        $this->complete = $complete;

        return $this;
    }
}
