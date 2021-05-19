<?php
  // Headers
  header('Access-Control-Allow-Origin: *');
  header("Content-Type: application/json");

include_once "../../config/Database.php";
include_once "../../models/Post.php";

$database = new Database();
$db = $database->connect();

# blog post
$post = new Post($db);

$result = $post->read();
$num = $result->rowCount();

# check if any post
if ($num > 0) {
    # Post array
    $post_array = array();
    $post_array['data']=array();
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        extract($row);

        $post_item = [
      "id"=>$id,
      "title"=>$title,
      "body"=>html_entity_decode($body),
      "author" => $author,
      "category_id"=>$category_id,
      "category_name"=>$category_name
    ];

        // Push to "data"
        array_push($post_array['data'], $post_item);
    }

    // turn to json & output

    echo json_encode($post_array);
} else {
    // No posts
    echo json_encode(
        array('message'=>"no Posts found")
    );
}
