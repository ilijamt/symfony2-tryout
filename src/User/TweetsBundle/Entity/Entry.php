<?php

namespace User\TweetsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Entry
 */
class Entry {

    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $username;

    /**
     * @var string
     */
    private $entry;

    /**
     * @var \DateTime
     */
    private $created;

    /**
     * @var \DateTime
     */
    private $updated;

    /**
     * @var \User\TweetsBundle\Entity\User
     */
    private $user_link;

    /**
     * Constructor
     */
    public function __construct() {
        $this->setUpdated(new \DateTime());
        $this->setCreated(new \DateTime());
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set username
     *
     * @param string $username
     * @return Entry
     */
    public function setUsername($username) {
        $this->username = $username;

        return $this;
    }

    /**
     * Get username
     *
     * @return string 
     */
    public function getUsername() {
        return $this->username;
    }

    /**
     * Set entry
     *
     * @param string $entry
     * @return Entry
     */
    public function setEntry($entry) {
        $this->entry = $entry;

        return $this;
    }

    /**
     * Get entry
     *
     * @return string 
     */
    public function getEntry() {
        return $this->entry;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     * @return Entry
     */
    public function setCreated($created) {
        $this->created = $created;

        return $this;
    }

    /**
     * Get created
     *
     * @return \DateTime 
     */
    public function getCreated() {
        return $this->created;
    }

    /**
     * Set updated
     *
     * @param \DateTime $updated
     * @return Entry
     */
    public function setUpdated($updated) {
        $this->updated = $updated;

        return $this;
    }

    /**
     * Get updated
     *
     * @return \DateTime 
     */
    public function getUpdated() {
        return $this->updated;
    }

    /**
     * Set user_link
     *
     * @param \User\TweetsBundle\Entity\User $userLink
     * @return Entry
     */
    public function setUserLink(\User\TweetsBundle\Entity\User $userLink = null) {
        $this->user_link = $userLink;

        return $this;
    }

    /**
     * Get user_link
     *
     * @return \User\TweetsBundle\Entity\User 
     */
    public function getUserLink() {
        return $this->user_link;
    }

    /**
     * @ORM\PreUpdate
     */
    public function setUpdatedValue() {
        $this->setUpdated(new \DateTime());
    }

    /**
     * @var string
     */
    private $name;


    /**
     * Set name
     *
     * @param string $name
     * @return Entry
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }
}
