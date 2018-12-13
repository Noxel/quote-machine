<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ScoreRepository")
 */
class Score
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="scores")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Quote", inversedBy="scores")
     * @ORM\JoinColumn(nullable=false)
     */
    private $Quote;

    /**
     * @ORM\Column(type="integer")
     */
    private $score = 0;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getQuote(): ?Quote
    {
        return $this->Quote;
    }

    public function setQuote(?Quote $Quote): self
    {
        $this->Quote = $Quote;

        return $this;
    }

    public function getScore(): ?int
    {
        return $this->score;
    }

    public function setScore(int $score): self
    {
        $this->score = $score;

        return $this;
    }
    public function up(){
        if($this->score < 1){
            $this->score++;
        }

    }
    public function down(){
        if($this->score > -1){
            $this->score--;
        }
    }
}
