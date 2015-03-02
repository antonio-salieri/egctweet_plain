<?php
namespace Application\Table;

use Egc\Mvc\Application;
class ProfileTable
{

    const TABLE_NAME = 'following';

    protected $dbAdapter;

    public function __construct()
    {
        $this->dbAdapter = Application::getDbAdapter();
    }

    public function getAllFollowings()
    {
        $sql = "";

        $rowset = array();
        $collection = new FollowingCollection();
        foreach ($rowset as $row) {
            $collection->add($row);
        }

        return $collection;
    }

    public function getThreeRandomFollowings()
    {
        $sql = new Sql($this->dbAdapter->getAdapter());
        $select = $sql->select();
        $select->from(self::TABLE_NAME)
                ->columns(array('*', 'rnd' => new Expression('RANDOM()')))
                ->order('rnd')
                ->limit(3)
                ->group('followingId');

        $rowset = $this->dbAdapter->selectWith($select);

        $collection = new FollowingCollection();
        foreach ($rowset as $row) {
            $collection->add($row);
        }

        return $collection;
    }

    public function getUserFollowings($userId)
    {
        $id = (int) $userId;
        $sql = new Sql($this->dbAdapter->getAdapter());
        $select = $sql->select();
        $select->from(self::TABLE_NAME)
            ->where(array(
                'userId' => $id
            ));

        $rowset = $this->dbAdapter->selectWith($select);

        $collection = new FollowingCollection();
        foreach ($rowset as $row) {
            $collection->add($row);
        }

        return $collection;
    }

    public function getUserFollowing($id, $user_id)
    {
        $sql = new Sql($this->dbAdapter->getAdapter());
        $select = $sql->select();
        $select->from(self::TABLE_NAME)
            ->where(array(
                'id' => $id,
                'userId' => $user_id
            ));

        $rowset = $this->dbAdapter->selectWith($select);

        $row = $rowset->current();
        if (! $row) {
            throw new \Exception("Could not find following");
        }

        return $row;
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
            $this->dbAdapter->insert($data);
        } else {
            if ($this->getUserFollowing($id, $user_id)) {
                $this->dbAdapter->update($data, array(
                    'id' => (int)$id
                ));
            } else {
                throw new \Exception('Following does not exist');
            }
        }
    }

    public function deleteFollowing($id, $user_id)
    {
        $this->dbAdapter->delete(array(
            'id' => (int) $id,
        	'userId' => (int)$user_id
        ));
    }
}
