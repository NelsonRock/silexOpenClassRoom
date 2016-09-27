<?php

namespace silex\DAO;

use Doctrine\DBAL\Connection;
use silex\Domain\Comment;

class CommentDAO
{
    /**
    * Database Connection
    * @var \Doctrine\DBAL\Connection
    */
    private $db;

    /**
    * Constructor
    * @param \Doctrine\DBAL\Connection the database connection object
    */
    public function __construct(Connection $db)
    {
        $this->db = $db;
    }

    /**
     * Return a list of all Comments, sorted by date (most recent first).
     *
     * @return array A list of all Comments.
     */
    public function findAll() {
        $sql = "select * from t_comment order by art_id desc";
        $result = $this->db->fetchAll($sql);
        
        // Convert query result to an array of domain objects
        $comments = array();
        foreach ($result as $row) {
            $commentId = $row['art_id'];
            $comments[$commentId] = $this->buildComment($row);
        }
        return $comments;
    }

    /**
     * Create an Comment object based on a DB row.
     *
     * @param array $row The DB row containing Comment data.
     * @return \MicroCMS\Domain\Comment
     */
    private function buildComment(array $row) {
        $comment = new comment();
        $comment->setId($row['art_id']);
        $comment->setTitle($row['art_title']);
        $comment->setContent($row['art_content']);
        return $comment;
    }

}