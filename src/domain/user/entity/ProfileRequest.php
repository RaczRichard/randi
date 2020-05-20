<?php


namespace Randi\domain\user\entity;


class ProfileRequest
{
    /** @var string */
    private $id;

    /** @var string */
    private $username;

    /** @var string */
    private $gender;

    /** @var string */
    private $address;

    /** @var int */
    private $height;

    /** @var string */
    private $physique;

    /** @var int */
    private $age;

    /** @var string */
    private $child;

    /** @var string */
    private $job;

    /** @var string */
    private $live;

    /** @var string */
    private $looking;

    /** @var string */
    private $school;

    /** @var int */
    private $status;

    /** @var string */
    private $picture;

    /**
     * @return string
     */
    public function getUsername(): string
    {

        return $this->username;
    }

    /**
     * @param string $username
     */
    public function setUsername(string $username): void
    {
        $this->username = $username;
    }

    /**
     * @return string
     */
    public function getAddress(): string
    {
        return $this->address;
    }

    /**
     * @param string $address
     */
    public function setAddress(string $address): void
    {
        $this->address = $address;
    }

    /**
     * @return int
     */
    public function getHeight(): int
    {
        return $this->height;
    }

    /**
     * @param int $height
     */
    public function setHeight(int $height): void
    {
        $this->height = $height;
    }

    /**
     * @return int
     */
    public function getAge(): int
    {
        return $this->age;
    }

    /**
     * @param int $age
     */
    public function setAge(int $age): void
    {
        $this->age = $age;
    }

    /**
     * @return string
     */
    public function getChild(): string
    {
        return $this->child;
    }

    /**
     * @param string $child
     */
    public function setChild(string $child): void
    {
        $this->child = $child;
    }

    /**
     * @return string
     */
    public function getJob(): string
    {
        return $this->job;
    }

    /**
     * @param string $job
     */
    public function setJob(string $job): void
    {
        $this->job = $job;
    }

    /**
     * @return string
     */
    public function getLive(): string
    {
        return $this->live;
    }

    /**
     * @param string $live
     */
    public function setLive(string $live): void
    {
        $this->live = $live;
    }

    /**
     * @return string
     */
    public function getLooking(): string
    {
        return $this->looking;
    }

    /**
     * @param string $looking
     */
    public function setLooking(string $looking): void
    {
        $this->looking = $looking;
    }

    /**
     * @return string
     */
    public function getSchool(): string
    {
        return $this->school;
    }

    /**
     * @param string $school
     */
    public function setSchool(string $school): void
    {
        $this->school = $school;
    }

    /**
     * @return int
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * @param int $status
     */
    public function setStatus(int $status): void
    {
        $this->status = $status;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @param string $id
     */
    public function setId(string $id): void
    {
        $this->id = $id;
    }


    /**
     * @return string
     */
    public function getGender(): string
    {
        return $this->gender;
    }

    /**
     * @param string $gender
     */
    public function setGender(string $gender): void
    {
        $this->gender = $gender;
    }

    /**
     * @return string
     */
    public function getPhysique(): string
    {
        return $this->physique;
    }

    /**
     * @param string $physique
     */
    public function setPhysique(string $physique): void
    {
        $this->physique = $physique;
    }

    /**
     * @return string
     */
    public function getPicture(): string
    {
        return $this->picture;
    }

    /**
     * @param string $picture
     */
    public function setPicture(string $picture): void
    {
        $this->picture = $picture;
    }

}