<?php

namespace App\Entity;

use App\Repository\AdminRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AdminRepository::class)]
class Admin extends User
{
    #[ORM\Column(type: 'integer')]
    private $badge_num;

    public function getBadgeNum(): ?int
    {
        return $this->badge_num;
    }

    public function __toString()
    {
        return $this->getFirstname() . ' ' . $this->getLastname();
    }


    public function setBadgeNum(int $badge_num): self
    {
        $this->badge_num = $badge_num;

        return $this;
    }
}
