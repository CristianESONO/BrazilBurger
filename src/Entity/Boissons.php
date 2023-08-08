<?php

namespace App\Entity;

use App\Repository\BoissonsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BoissonsRepository::class)]
class Boissons extends Complement
{
    #[ORM\OneToMany(mappedBy: 'boissons', targetEntity: Menu::class)]
    private Collection $menu;

    public function __construct()
    {
        parent::__construct();
        $this->menu = new ArrayCollection();
    }

    /**
     * @return Collection<int, Menu>
     */
    public function getMenu(): Collection
    {
        return $this->menu;
    }

    public function addMenu(Menu $menu): self
    {
        if (!$this->menu->contains($menu)) {
            $this->menu->add($menu);
            $menu->setBoissons($this);
        }

        return $this;
    }

    public function removeMenu(Menu $menu): self
    {
        if ($this->menu->removeElement($menu)) {
            // set the owning side to null (unless already changed)
            if ($menu->getBoissons() === $this) {
                $menu->setBoissons(null);
            }
        }

        return $this;
    }
}
