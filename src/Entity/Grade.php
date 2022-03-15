<?php

namespace App\Entity;

use App\Repository\GradeRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GradeRepository::class)]
#[ORM\UniqueConstraint(name: "UK_grade_exam_user", columns: ['grade', 'user', 'exam'])]
class Grade
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'integer')]
    private $grade;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'grades')]
    #[ORM\JoinColumn(nullable: false, name: 'user')]
    private $user;

    #[ORM\ManyToOne(targetEntity: Exam::class, inversedBy: 'grades')]
    #[ORM\JoinColumn(nullable: false, name: 'exam')]
    private $exam;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getGrade(): ?int
    {
        return $this->grade;
    }

    public function setGrade(int $grade): self
    {
        $this->grade = $grade;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getExam(): ?Exam
    {
        return $this->exam;
    }

    public function setExam(?Exam $exam): self
    {
        $this->exam = $exam;

        return $this;
    }
}
