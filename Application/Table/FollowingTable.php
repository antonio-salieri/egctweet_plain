<?php
namespace Application\Table;

use Egc\Mvc\Application;
use Application\Model\Following;
use Application\Collection\FollowingCollection;
class FollowingTable
{

    const TABLE_NAME = 'following';

    /**
     * @var PDO
     */
    protected $dbAdapter;

    public function __construct()
    {
        $this->dbAdapter = Application::getDbAdapter();
    }

    public function getAllFollowings()
    {
        $query = sprintf("SELECT * FROM %s;", self::TABLE_NAME);
        $rowset = $this->dbAdapter->prepareExecuteAndFetch($query);

        $collection = new FollowingCollection();
        foreach ($rowset as $row) {
            $collection->add(new Following($row));
        }

        return $collection;
    }

    public function getUserFollowings($userId)
    {
        $id = (int) $userId;
        $query = sprintf("SELECT * FROM %s WHERE userId = ?;", self::TABLE_NAME);
        $rowset = $this->dbAdapter->prepareExecuteAndFetch($query, array($id));

        $collection = new FollowingCollection();
        foreach ($rowset as $row) {
            $collection->add(new Following($row));
        }

        return $collection;
    }

    public function getUserFollowing($id, $user_id)
    {
        $id = (int) $id;
        $userId = (int) $user_id;
        $query = sprintf("SELECT * FROM %s WHERE id = ? AND userId = ?;", self::TABLE_NAME);
        $rowset = $this->dbAdapter->prepareExecuteAndFetch($query, array($id, $userId));

        $following = null;
        if (count($rowset) > 0) {
            $following = new Following($rowset[0]);
        }


        return $following;
    }

    public function saveFollowing(Following $following, $user_id)
    {
        $id = (int) $following->getId();

        if (!$id)
        {
        	$following->setUserId($user_id);
        }
        $data = $following->getData();
        if ($id == 0) {
            $query = sprintf("
                INSERT INTO `%s`
                (`userId`, `followingName`, `followingId`)
                VALUES (:user_id, :following_name, :following_id)", self::TABLE_NAME);
            $stmt = $this->dbAdapter->prepare($query);
            $stmt->bindValue(':user_id', $data['userId'], \PDO::PARAM_INT);
            $stmt->bindValue(':following_name', $data['followingName'], \PDO::PARAM_STR);
            $stmt->bindValue(':following_id', $data['followingId'], \PDO::PARAM_INT);
            $stmt->execute();
        } else {
            if ($this->getUserFollowing($id, $user_id)) {
                $query = sprintf("
                UPDATE `%s`
                SET userId = :user_id,
                    followingName = :following_name,
                    followingId = :following_id
                WHERE id = :id", self::TABLE_NAME);
                $stmt = $this->dbAdapter->prepare($query);
                $stmt->bindValue(':user_id', $data['userId'], \PDO::PARAM_INT);
                $stmt->bindValue(':following_name', $data['followingName'], \PDO::PARAM_STR);
                $stmt->bindValue(':following_id', $data['followingId'], \PDO::PARAM_INT);
                $stmt->bindValue(':id', $id, \PDO::PARAM_INT);
                $stmt->execute();
            } else {
                throw new \Exception('Following does not exist');
            }
        }
    }

    public function deleteFollowing($id, $user_id)
    {

        $query = sprintf("
                DELETE FROM `%s`
                WHERE id = :id AND userId = :user_id", self::TABLE_NAME);

        $stmt = $this->dbAdapter->prepare($query);
        $stmt->bindValue(':user_id', $user_id, \PDO::PARAM_INT);
        $stmt->bindValue(':id', $id, \PDO::PARAM_INT);
        $stmt->execute();
    }
}
