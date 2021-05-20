<?php
class Post
{
    private $connection;
    private $table = "posts";

    public $id;
    public $category_id;
    public $category_name;
    public $title;
    public $body;
    public $author;
    public $created_at;

    public function __construct($db)
    {
        $this->connection = $db;
    }

    // Get Posts
    public function read()
    {
        $query = "SELECT 
            c.name as category_name,
            p.id,
            p.category_id,
            p.title,
            p.body,
            p.author,
            p.created_at
          FROM 
            myblog.{$this->table} p
          LEFT JOIN
            categories c ON p.category_id = c.id
          ORDER BY
            p.created_at DESC
      ";

        $statement = $this->connection->prepare($query);

        $statement->execute();

        return $statement;
    }


    /**
     * get single post
     *
     * @return void
     */
    public function read_single():void
    {
        $query = "SELECT 
      c.name as category_name,
      p.id,
      p.category_id,
      p.title,
      p.body,
      p.author,
      p.created_at
    FROM 
      myblog.{$this->table} p
    LEFT JOIN
      categories c ON p.category_id = c.id
    WHERE
      p.id = ? 
    LIMIT 0,1";
        # prepare statement
        $statement = $this->connection->prepare($query);

        $statement->bindParam(1, $this->id);

        $statement->execute();

        $row = $statement->fetch(PDO::FETCH_ASSOC);

        // Set properties
        $this->title = $row['title'];
        $this->body= $row['body'];
        $this->author = $row['author'];
        $this->category_id = $row['category_id'];
        $this->category_name = $row['category_name'];
    }

    /**
     * create post
     *
     * @return void
     */
    public function create():bool
    {
        $query = "INSERT INTO myblog." . $this->table ."
        SET 
            title = :title,
            body = :body,
            author = :author,
            category_id = :category_id";

        $statement = $this->connection->prepare($query);

        $this->title = htmlspecialchars(strip_tags($this->title));
        $this->body = htmlspecialchars(strip_tags($this->body));
        $this->author = htmlspecialchars(strip_tags($this->author));
        $this->category_id = htmlspecialchars(strip_tags($this->category_id));
      
        $statement->bindParam(':title', $this->title);
        $statement->bindParam(':body', $this->body);
        $statement->bindParam(':author', $this->author);
        $statement->bindParam(':category_id', $this->category_id);

        if ($statement->execute()) {
            return true;
        }
        # print error if something goes wrong
        printf("Error: %s.\n", $statement->error);
        return false;
    }
}
