<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\DateFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\RangeFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use App\Repository\FantasyUserRankingRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 *     normalizationContext={"groups": {"fantasyUserRanking:read"}},
 *     denormalizationContext={"groups": {"fantasyUserRanking:write"}},
 *     collectionOperations={"get"},
 *     itemOperations={"get"}
 * )
 *
 * @ORM\Entity(repositoryClass=FantasyUserRankingRepository::class)
 */
class FantasyUserRanking
{
    /**
     * @ApiFilter(OrderFilter::class)
     *
     * @Groups({"fantasyUserRanking:read", "fantasyUser:read"})
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ApiFilter(OrderFilter::class, properties={"fantasyUser.username"})
     * @ApiFilter(SearchFilter::class, properties={
     *     "fantasyUser": "exact",
     *     "fantasyUser.username": "partial"
     * })
     *
     * @Groups({"fantasyUserRanking:read"})
     *
     * @ORM\ManyToOne(targetEntity=FantasyUser::class, inversedBy="fantasyUserRankings")
     * @ORM\JoinColumn(nullable=false)
     */
    private $fantasyUser;

    /**
     * @ApiFilter(OrderFilter::class)
     * @ApiFilter(SearchFilter::class, strategy="exact")
     *
     * @Groups({"fantasyUserRanking:read"})
     *
     * @ORM\Column(type="integer")
     */
    private $season;

    /**
     * @ApiFilter(OrderFilter::class)
     * @ApiFilter(SearchFilter::class, strategy="exact")
     *
     * @Groups({"fantasyUserRanking:read"})
     *
     * @ORM\Column(type="boolean")
     */
    private $isPlayoffs;

    /**
     * @ApiFilter(OrderFilter::class)
     * @ApiFilter(RangeFilter::class)
     *
     * @Groups({"fantasyUserRanking:read", "fantasyUser:read"})
     *
     * @ORM\Column(type="integer")
     */
    private $fantasyPoints;

    /**
     * @ApiFilter(OrderFilter::class)
     * @ApiFilter(RangeFilter::class)
     *
     * @Groups({"fantasyUserRanking:read", "fantasyUser:read"})
     *
     * @ORM\Column(type="integer")
     */
    private $fantasyRank;

    /**
     * @ApiFilter(OrderFilter::class)
     * @ApiFilter(DateFilter::class)
     *
     * @Groups({"fantasyUserRanking:read", "fantasyUser:read"})
     *
     * @ORM\Column(type="date")
     */
    private $rankingAt;

    /**
     * @ApiFilter(OrderFilter::class)
     *
     * @Groups({"fantasyUserRanking:read"})
     *
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;

    public function __construct()
    {
        $this->season = (int) $_ENV['NBA_YEAR'];
        $this->isPlayoffs = (bool) $_ENV['NBA_PLAYOFFS'];
    }

    public function __toString(): string
    {
        return (string) ($this->fantasyUser.' - '.$this->rankingAt->format('d/m/Y').' - '.$this->fantasyPoints.'pts - '.$this->fantasyRank.'th');
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSeason(): int
    {
        return $this->season;
    }

    public function setSeason(int $season): self
    {
        $this->season = $season;

        return $this;
    }

    public function getIsPlayoffs(): bool
    {
        return $this->isPlayoffs;
    }

    public function setIsPlayoffs(bool $isPlayoffs): self
    {
        $this->isPlayoffs = $isPlayoffs;

        return $this;
    }

    public function getFantasyUser(): ?FantasyUser
    {
        return $this->fantasyUser;
    }

    public function setFantasyUser(?FantasyUser $fantasyUser): self
    {
        $this->fantasyUser = $fantasyUser;

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

    public function getRankingAt(): ?\DateTimeInterface
    {
        return $this->rankingAt;
    }

    public function setRankingAt(\DateTimeInterface $rankingAt): self
    {
        $this->rankingAt = $rankingAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
}
