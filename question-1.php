<?php
class Connection{
    private $host = 'localhost';
    private $user = 'root';
    private $pwd = '';
    private $db = 'scropay';

    protected function conn(){
        $dsn = 'mysql:host='.$this->host.';dbname='.$this->db;
        $pdo = new PDO($dsn, $this->user, $this->pwd);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        return $pdo;
    }
}
class User extends Connection{
    private $id, $username, $full_name, $profile_picture, $followed;
     
    public function getUser($user_id, $auth_user_id)
    {
        $sql = "SELECT * FROM users WHERE id = ?";  
        $stmt = $this->conn()->prepare($sql);
        $stmt->execute([$user_id]);
        $user = $stmt->fetch();
        $this->id = $user['id'];
        $this->username = $user['username'];
        $this->full_name = $user['full_name'];
        $this->profile_picture = $user['profile_picture'];
        $this->followed = $this->isFollowed($auth_user_id, $user_id);
        return $this;
    }

    public function isFollowed($auth_user_id, $user_id)
    {
        $sql =  "SELECT * FROM followers WHERE follower_id = ? AND following_id =  ?";
        $stmt = $this->conn()->prepare($sql);
        $stmt->execute([$auth_user_id, $user_id]);
        $result = $stmt->fetchAll();
        if(count($result) > 0){
            return true;
        }else{
            return false;
        }
    }
}

class Post extends Connection{
    private $id, $description, $owner, $image, $created_at, $liked;

    public function getPost($user_id, $posts_ids)
    {
        $posts = array();
        $sql = "SELECT * FROM posts WHERE id IN (".implode(',', $posts_ids).")";
        $stmt = $this->conn()->query($sql);
        $result = $stmt->fetchAll();
        foreach ($result as $key => $post) {
            $this->id = $post['id'];
            $this->description = $post['description'];
            $owner = new User();
            $this->owner = $owner->getUser($post['user_id'], $user_id);
            $this->image = $post['image'];
            $this->created_at = $post['created_at'];
            $this->liked = $this->isLiked($user_id, $post['id']);
            array_push($posts, $this);
        }
        return $posts;
    }

    public function isLiked($user_id, $post_id)
    {
        $sql =  "SELECT * FROM likes WHERE user_id = ? AND post_id = ?";
        $stmt = $this->conn()->prepare($sql);
        $stmt->execute([$user_id, $post_id]);
        $result = $stmt->fetchAll();
        if(count($result) > 0){
            return true;
        }else{
            return false;
        }
    }
}

$post = new Post();
return json_encode(print_r($post->getPost(1, [1,3,4])));