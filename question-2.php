<?php

class Post{
    public $id, $description, $image, $created_at;

    public function mergePosts($posts_array)
    {
        $posts = [];
        $post_ids = [];
        foreach ($posts_array as $key => $post) {
            if(!in_array($post->id, $post_ids)){
                if(array_key_exists($post->created_at, $posts)){
                    if($post->id > $posts[$post->created_at]->id){
                        $previous_in_position = $posts[$post->created_at];
                        $posts[$post->created_at + 1] = $post;
                        $posts[$post->created_at] = $previous_in_position;
                        array_push($post_ids, $post->id);
                    }
                }else{
                    $posts[$post->created_at] = $post;
                    array_push($post_ids, $post->id);
                }
            }
        }
        krsort($posts);
        return print_r($posts);
    }
}

$array = [
    [
        "id"=> 1,
        "desciption"=> "loremakdndkalsnkldnklasndklnask",
        "image"=> "https://google.com",
        "created_at"=> 2139123
    ],
    [
        "id"=> 4,
        "desciption"=> "loremakdndkalsnkldnklasndklnask",
        "image"=> "https://google.com",
        "created_at"=> 2139124
    ],
    [
        "id"=> 7,
        "desciption"=> "loremakdndkalsnkldnklasndklnask",
        "image"=> "https://google.com",
        "created_at"=> 2139123
    ],
    [
        "id"=> 9,
        "desciption"=> "loremakdndkalsnkldnklasndklnask",
        "image"=> "https://google.com",
        "created_at"=> 213912389
    ],
    [
        "id"=> 10,
        "desciption"=> "loremakdndkalsnkldnklasndklnask",
        "image"=> "https://google.com",
        "created_at"=> 2139123343
    ],
    [
        "id"=> 5,
        "desciption"=> "loremakdndkalsnkldnklasndklnask",
        "image"=> "https://google.com",
        "created_at"=> 21391238324
    ],
    [
        "id"=> 14,
        "desciption"=> "loremakdndkalsnkldnklasndklnask",
        "image"=> "https://google.com",
        "created_at"=> 2139123234234
    ],
];

function test($array){
    $post_to_test = [];

    foreach ($array as $key => $value) {
        $post = new Post();
        $post->id = $value['id'];
        $post->description = $value['desciption'];
        $post->image = $value['image'];
        $post->created_at = $value['created_at'];
        array_push($post_to_test, $post);        
    }
    $posts = new Post();

    return $posts->mergePosts($post_to_test);
}

return test($array);