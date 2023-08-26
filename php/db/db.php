<?php
include_once('/config.php');
class DB
{
    public $pdo = null;

    public function INIT()
    {
        $this->Connect();
    }

    private function Connect()
    {
        try {
            $this->pdo = new PDO("mysql:host=".DB_HOST.";dbname=".DB_DATABASE, DB_USER, DB_PASSWORD, [
                PDO::ATTR_EMULATE_PREPARES => false,
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
                PDO::ATTR_PERSISTENT => true
            ]);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
        }
    }

    public function addUserToDatabase($userdata)
    {
        $sql = "INSERT INTO users (discord_id,discord_avatar,discord_global_name,discord_email) VALUES 
                                      (:discord_id,:discord_avatar,:discord_global_name,:discord_email)";
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                'discord_id' => $userdata['id'],
                'discord_global_name' => $userdata['global_name'],
                'discord_avatar' => $userdata['avatar'],
                'discord_email' => $userdata['email']
            ]);
        } catch (Exception $e) {
            echo $e;
        }
    }

    public function addBoardTaskToDatabase($data)
    {
        $sql = "INSERT INTO board (item_id,description,location,creator_id,users_collect,amount,active) VALUES 
                                  (:item_id,:description,:location,:creator_id,:users_collect,:amount,:active)";
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                'item_id' => $data['item_id'],
                'description' => $data['description'],
                'location' => $data['location'],
                'creator_id' => $data['creator_id'],
                'users_collect' => true,
                'amount' => $data['amount'],
                'active' => true
            ]);
        } catch (Exception $e) {
            echo $e;
        }
    }

    public function addItemToDatabase($name, $file, $profession)
    {
        $sql = "INSERT INTO items (profession_id,name,image,status) VALUES 
                                      (:profession_id,:name,:image,:status)";
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                'profession_id' => $profession,
                'name' => $name,
                'image' => $file,
                'status' => false
            ]);
        } catch (Exception $e) {
            echo $e;
        }
    }

    public function addRequestToDatabase($requester, $itemid, $amount, $profession)
    {
        $sql = "INSERT INTO requests (item_id,amount,requester_id,status_id,profession_id) VALUES 
                                      (:item_id,:amount,:requester_id,:status_id,:profession_id)";
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                'item_id' => $itemid,
                'amount' => $amount,
                'requester_id' => $requester,
                'status_id' => 1,
                'profession_id' => $profession
            ]);
        } catch (Exception $e) {
            echo $e;
        }
    }

    public function checIfUserExist($id)
    {
        $sql = "SELECT * FROM users WHERE discord_id=:discord_id ";
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                'discord_id' => $id
            ]);

            $user = $stmt->fetchAll();
            if (count($user) > 0) {
                return true;
            } else {
                return false;
            }
        } catch (Exception $e) {
            echo $e;
            return true;
        }
    }

    public function getAllItems()
    {
        $sql = "SELECT * FROM items";
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();

            return $stmt->fetchAll();
        } catch (Exception $e) {
            echo $e;
            return true;
        }
    }

    public function setRequestStatus($status, $id){
        $sql = "UPDATE requests SET status_id=? WHERE id=?";
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$status, $id]);
            return true;
        } catch (Exception $e) {
            echo $e;
            return false;
        }
    }

    public function setRequestCrafter($crafter, $id){
        $sql = "UPDATE requests SET crafter_id=? WHERE id=?";
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$crafter, $id]);
            return true;
        } catch (Exception $e) {
            echo $e;
            return false;
        }
    }

    public function getAllRequests()
    {
        $sql = "SELECT * FROM requests";
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();

            return $stmt->fetchAll();
        } catch (Exception $e) {
            echo $e;
            return true;
        }
    }

    public function getAllBoardTasks()
    {
        $sql = "SELECT * FROM board";
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();

            return $stmt->fetchAll();
        } catch (Exception $e) {
            echo $e;
            return true;
        }
    }

    public function getAllUserRequests($id)
    {
        $sql = "SELECT * FROM requests WHERE requester_id=:requester_id ";
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                'requester_id' => $id
            ]);

            return $stmt->fetchAll();
        } catch (Exception $e) {
            echo $e;
            return true;
        }
    }

    public function getRequests($id)
    {
        $sql = "SELECT * FROM requests WHERE id=:id ";
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                'id' => $id
            ]);

            return $stmt->fetch();
        } catch (Exception $e) {
            echo $e;
            return true;
        }
    }

    public function getBoardTask($id)
    {
        $sql = "SELECT * FROM board WHERE id=:id ";
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                'id' => $id
            ]);

            return $stmt->fetch();
        } catch (Exception $e) {
            echo $e;
            return true;
        }
    }


    public function getProfessions($id)
    {
        $sql = "SELECT professions FROM users WHERE discord_id=:discord_id ";
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                'discord_id' => $id
            ]);

            $user = $stmt->fetch();
            if (count($user) > 0) {
                return $user['professions'];
            }
        } catch (Exception $e) {
            echo $e;
            return true;
        }
    }

    public function getUser($id)
    {
        $sql = "SELECT * FROM users WHERE discord_id=:discord_id ";
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                'discord_id' => $id
            ]);

            $user = $stmt->fetch();
            return $user;
        } catch (Exception $e) {
            echo $e;
            return true;
        }
    }

    public function getUserById($id)
    {
        $sql = "SELECT * FROM users WHERE id=:id ";
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                'id' => $id
            ]);

            $user = $stmt->fetch();
            return $user;
        } catch (Exception $e) {
            echo $e;
            return true;
        }
    }

    public function getStatus($id)
    {
        $sql = "SELECT * FROM status WHERE id=:id ";
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                'id' => $id
            ]);

            $user = $stmt->fetch();
            return $user;
        } catch (Exception $e) {
            echo $e;
            return true;
        }
    }


    public function getItem($id)
    {
        $sql = "SELECT * FROM items WHERE id=:id ";
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                'id' => $id
            ]);

            $user = $stmt->fetch();
            return $user;
        } catch (Exception $e) {
            echo $e;
            return true;
        }
    }

    public function getAllProfessions()
    {
        $sql = "SELECT * FROM profession";
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();

            return $stmt->fetchAll();
        } catch (Exception $e) {
            echo $e;
            return true;
        }
    }

    public function getProfession($id)
    {
        $sql = "SELECT * FROM profession WHERE id=:id";
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                'id' => $id
            ]);

            return $stmt->fetch();
        } catch (Exception $e) {
            echo $e;
            return true;
        }
    }

    public function getProfessionId($name)
    {
        $sql = "SELECT * FROM profession WHERE name=:name";
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                'name' => $name
            ]);

            return $stmt->fetch()['id'];
        } catch (Exception $e) {
            echo $e;
            return true;
        }
    }

    public function getAllUsers()
    {
        $sql = "SELECT * FROM users";
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();

            return $stmt->fetchAll();
        } catch (Exception $e) {
            echo $e;
            return true;
        }
    }

    public function setProfessions($data, $id)
    {
        $sql = "UPDATE users SET professions=? WHERE id=?";
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$data, $id]);
            return true;
        } catch (Exception $e) {
            echo $e;
            return false;
        }
    }


    public function setVerify($data, $id)
    {
        $sql = "UPDATE users SET verify=? WHERE id=?";
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$data, $id]);
            return true;
        } catch (Exception $e) {
            echo $e;
            return false;
        }
    }

    public function setTaskUsers($data, $id)
    {
        $sql = "UPDATE board SET users_list=? WHERE id=?";
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$data, $id]);
            return true;
        } catch (Exception $e) {
            echo $e;
            return false;
        }
    }

    public function setTaskAprUsers($data, $id)
    {
        $sql = "UPDATE board SET users_apr=? WHERE id=?";
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$data, $id]);
            return true;
        } catch (Exception $e) {
            echo $e;
            return false;
        }
    }

    public function setFinishDate($id)
    {
        $sql = "UPDATE requests SET finished_at=? WHERE id=?";
        $date = date('Y-m-d H:i:s');
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$date,$id]);
            return true;
        } catch (Exception $e) {
            echo $e;
            return false;
        }
    }

    public function setItemStatus($status, $id)
    {
        $sql = "UPDATE items SET status=? WHERE id=?";
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$status, $id]);
            return true;
        } catch (Exception $e) {
            echo $e;
            return false;
        }
    }

    public function closeDBConnection()
    {
        $pdo = null;
    }
}
