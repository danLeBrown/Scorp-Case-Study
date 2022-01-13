<?php
class Connection{
    private $host = 'localhost';
    private $user = 'root';
    private $pwd = '';
    private $db = 'scropay';

    protected function conn(){
        $dsn = 'mysql:host='.$this->host.';dbname='.$this->db;
        $pdo = new PDO($dsn, $this->user, $this->pwd);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
        return $pdo;
    }
}
class User extends Connection{     
    public function getUser($user_id, $auth_user_id)
    {
        $sql = "SELECT * FROM users WHERE id = ?";  
        $stmt = $this->conn()->prepare($sql);
        $stmt->execute([$user_id]);
        $user = $stmt->fetch();
        $user->followed = $this->isFollowed($auth_user_id, $user_id);
        return $user;
    }

    public function isFollowed($auth_user_id, $user_id)
    {
        $sql =  "SELECT * FROM followers WHERE follower_id = ? AND following_id =  ?";
        $stmt = $this->conn()->prepare($sql);
        $stmt->execute([$auth_user_id, $user_id]);
        $result = $stmt->fetch();
        if(count($result) > 0){
            return "true";
        }else{
            return "false";
        }
    }
}

class Post extends Connection{
    public function getPost($user_id, $posts_ids)
    {
        $posts = array();
        $sql = "SELECT * FROM posts WHERE id IN (".implode(',', $posts_ids).")";
        $stmt = $this->conn()->query($sql);
        $result = $stmt->fetchAll();
        foreach ($result as $key => $post) {
            $owner = new User();
            $post->owner = $owner->getUser($post->user_id, $user_id);
            $post->liked = $this->isLiked($user_id, $post->id);
            array_push($posts, $post);
        }
        return $posts;
    }

    public function isLiked($user_id, $post_id)
    {
        $sql =  "SELECT * FROM likes WHERE user_id = ? AND post_id = ?";
        $stmt = $this->conn()->prepare($sql);
        $stmt->execute([$user_id, $post_id]);
        $result = $stmt->fetch();
        if(count($result) > 0){
            return "true";
        }else{
            return "false";
        }
    }
}

$post = new Post();
return json_encode(print_r($post->getPost(1, [1,3,4])));