<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\BooleanFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\RangeFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use App\Repository\FantasyUserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 *     normalizationContext={"groups": {"fantasyUser:read"}},
 *     denormalizationContext={"groups": {"fantasyUser:write"}},
 *     collectionOperations={"get"},
 *     itemOperations={"get"}
 * )
 *
 * @ORM\Entity(repositoryClass=FantasyUserRepository::class)
 */
class FantasyUser implements UserInterface
{
    /**
     * @ApiFilter(OrderFilter::class)
     *
     * @Groups({"fantasyUser:read"})
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ApiFilter(OrderFilter::class)
     * @ApiFilter(SearchFilter::class, strategy="partial")
     *
     * @Groups({"fantasyUser:read"})
     *
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $username;

    /**
     * @ApiFilter(OrderFilter::class)
     * @ApiFilter(SearchFilter::class, strategy="exact")
     *
     * @Groups({"fantasyUser:read"})
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    private $ttflId;

    /**
     * @ApiFilter(OrderFilter::class, properties={"fantasyTeam.name"})
     * @ApiFilter(SearchFilter::class, properties={
     *     "fantasyTeam": "exact",
     *     "fantasyTeam.name": "partial"
     * })
     *
     * @Groups({"fantasyUser:read"})
     *
     * @ORM\ManyToOne(targetEntity=FantasyTeam::class, inversedBy="fantasyUsers")
     */
    private $fantasyTeam;

    /**
     * @ApiFilter(OrderFilter::class)
     * @ApiFilter(BooleanFilter::class)
     *
     * @Groups({"fantasyUser:read"})
     *
     * @ORM\Column(type="boolean")
     */
    private $isExoticUser;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $password;

    /**
     * Plain password used before encryption. Not persisted in database.
     */
    private $plainPassword;

    /**
     * @ApiFilter(OrderFilter::class)
     * @ApiFilter(RangeFilter::class)
     *
     * @Groups({"fantasyUser:read"})
     *
     * @ORM\Column(type="integer")
     */
    private $fantasyPoints;

    /**
     * @ApiFilter(OrderFilter::class)
     * @ApiFilter(RangeFilter::class)
     *
     * @Groups({"fantasyUser:read"})
     *
     * @ORM\Column(type="integer")
     */
    private $fantasyRank;

    /**
     * @ORM\OneToMany(targetEntity=FantasyPick::class, mappedBy="fantasyUser", orphanRemoval=true)
     * @ORM\OrderBy({"pickedAt": "ASC"})
     */
    private $fantasyPicks;

    /**
     * @ORM\OneToMany(targetEntity=FantasyUserRanking::class, mappedBy="fantasyUser", orphanRemoval=true)
     * @ORM\OrderBy({"rankingAt": "ASC"})
     */
    private $fantasyUserRankings;

    /**
     * @ORM\Column(type="datetime")
     */
    private $registeredAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $lastLoginAt;

    public function __construct()
    {
        $this->fantasyPicks = new ArrayCollection();
        $this->fantasyUserRankings = new ArrayCollection();
    }

    public function __toString(): string
    {
        return (string) $this->username;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): string
    {
        return (string) $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getTtflId(): ?int
    {
        return $this->ttflId;
    }

    public function setTtflId(int $ttflId): self
    {
        $this->ttflId = $ttflId;

        return $this;
    }

    public function getFantasyTeam(): ?FantasyTeam
    {
        return $this->fantasyTeam;
    }

    public function setFantasyTeam(?FantasyTeam $fantasyTeam): self
    {
        $this->fantasyTeam = $fantasyTeam;

        return $this;
    }

    public function getIsExoticUser(): bool
    {
        return $this->isExoticUser;
    }

    public function setIsExoticUser(bool $isExoticUser): self
    {
        $this->isExoticUser = $isExoticUser;

        return $this;
    }

    public function getRoles(): array
    {
        $roles = $this->roles;
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    public function getPassword(): ?string
    {
        return (string) $this->password;
    }

    public function setPassword(?string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getPlainPassword(): ?string
    {
        return (string) $this->plainPassword;
    }

    public function setPlainPassword(?string $plainPassword): self
    {
        $this->plainPassword = $plainPassword;

        return $this;
    }

    public function getFantasyPoints(): ?int
    {
        return $this->fantasyPoints;
    }

    public function setFantasyPoints(int $fantasyPoints): self
    {
        $this->fantasyPoints = $fantasyPoints;

        return $this;
    }

    public function getFantasyRank(): ?int
    {
        return $this->fantasyRank;
    }

    public function setFantasyRank(int $fantasyRank): self
    {
        $this->fantasyRank = $fantasyRank;

        return $this;
    }

    /**
     * @return Collection|FantasyPick[]
     */
    public function getFantasyPicks(): Collection
    {
        return $this->fantasyPicks;
    }

    public function addFantasyPick(FantasyPick $fantasyPick): self
    {
        if (!$this->fantasyPicks->contains($fantasyPick)) {
            $this->fantasyPicks[] = $fantasyPick;
            $fantasyPick->setFantasyUser($this);
        }

        return $this;
    }

    public function removeFantasyPick(FantasyPick $fantasyPick): self
    {
        if ($this->fantasyPicks->contains($fantasyPick)) {
            $this->fantasyPicks->removeElement($fantasyPick);
            if ($fantasyPick->getFantasyUser() === $this) {
                $fantasyPick->setFantasyUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|FantasyUserRanking[]
     */
    public function getFantasyUserRankings(): Collection
    {
        return $this->fantasyUserRankings;
    }

    public function addFantasyUserRanking(FantasyUserRanking $fantasyUserRanking): self
    {
        if (!$this->fantasyUserRankings->contains($fantasyUserRanking)) {
            $this->fantasyUserRankings[] = $fantasyUserRanking;
            $fantasyUserRanking->setFantasyUser($this);
        }

        return $this;
    }

    public function removeFantasyUserRanking(FantasyUserRanking $fantasyUserRanking): self
    {
        if ($this->fantasyUserRankings->contains($fantasyUserRanking)) {
            $this->fantasyUserRankings->removeElement($fantasyUserRanking);
            if ($fantasyUserRanking->getFantasyUser() === $this) {
                $fantasyUserRanking->setFantasyUser(null);
            }
        }

        return $this;
    }

    public function getRegisteredAt(): ?\DateTimeInterface
    {
        return $this->registeredAt;
    }

    public function setRegisteredAt(\DateTimeInterface $registeredAt): self
    {
        $this->registeredAt = $registeredAt;

        return $this;
    }

    public function getLastLoginAt(): ?\DateTimeInterface
    {
        return $this->lastLoginAt;
    }

    public function setLastLoginAt(?\DateTimeInterface $lastLoginAt): self
    {
        $this->lastLoginAt = $lastLoginAt;

        return $this;
    }

    public function getSalt(): void
    {
        // Not needed when using the "bcrypt" algorithm.
    }

    public function eraseCredentials(): void
    {
        // If any sensitive data on the user is stored temporary, clear it here.
    }

    /**
     * @Groups({"fantasyUser:read"})
     */
    public function getLastFantasyPick(): ?FantasyPick
    {
        return ($this->fantasyPicks->count()) ? $this->fantasyPicks->last() : null;
    }
}
