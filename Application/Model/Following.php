<?php
namespace EgcTweet\Entity;

class Following extends Base
{

    /**
     * @var int Id of the following entry
     */
    protected $id;

    /**
     * @var int Id of the user who owns this following
     */
    protected $userId;

    /**
     * @var string Twitter display name which is followed
     */
    protected $followingName;
    
    /**
     * @var int Id on Twitter for display name which is followed
     */
    protected $followingId;

    public function getFollowingName()
    {
    	return $this->followingName;
    }

    public function getFollowingId()
    {
    	return $this->followingId;
    }

    public function getId()
    {
    	return $this->id;
    }

    public function getUserId()
    {
    	return $this->userId;
    }
}
