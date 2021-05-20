<?php
  header('Access-Control-Allow-Origin: *');
  header("Content-Type: application/json");

include_once "../../config/Database.php";
include_once "../../models/Post.php";

$database = new Database();
$db = $database->connect();

# blog post
$post = new Post($db);

# Get ID
$post->id = isset($_GET['id']) ? $_GET['id'] : die();

# Get post
$post->read_single();

$post_array = array('id'=>$post->id,'title'=>$post->title,'body'=>$post->body,'author'=>$post->author,'category_id'=>$post->category_id,'category_name'=>$post->category_name);

# make json
print_r(json_encode($post_array));