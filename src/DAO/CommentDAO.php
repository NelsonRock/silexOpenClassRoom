<?php

namespace silex\DAO;

use silex\Domain\Comment;

class CommentDAO extends DAO
{
    private $articleDAO;

    private $userDAO;
    
    public function setArticleDAO(ArticleDAO $articleDAO)
    {
        $this->articleDAO = $articleDAO;
    }

    public function setUserDAO(UserDAO $userDAO)
    {
        $this->userDAO = $userDAO;
    }

    /**
     * Return a list of all Comments, sorted b)y date (most recent first).
     *
     * @return array A list of all Comments.
     */
     public function findAll()
     {
         $sql = "select * from t_comment order by com_id desc";
         $result = $this->getDb()->fetchAll($sql);
         $entities = array();
         foreach($result as $row) {
             $id = $row['com_id'];
             $entities[$id] = $this->buildDomainObject($row);
         }
         return $entities;
     }

    
    /**
     * Return a list of all Articles, sorted b)y date (most recent first).
     *
     * @return array A list of all Articles.
     */
    public function findAllByArticle($articleId) {
        $article = $this->articleDAO->find($articleId);
        $sql = "select com_id, com_content, usr_id from t_comment where art_id=? order by com_id";
        $result = $this->getDb()->fetchAll($sql, array($articleId));
        // Convert query result to an array of domain objects
        $comments = array();
        foreach ($result as $row) {
            $comId = $row['com_id'];
            $comment = $this->buildDomainObject($row);
            $comment->setArticle($article);
            $comments[$comId] = $comment;
        }
        return $comments;
    }

    public function save(Comment $comment)
    {
        $commentData = array(
            'art_id' => $comment->getArticle()->getId(),
            'usr_id' => $comment->getAuthor()->getid(),
            'com_content' => $comment->getContent()
        );
        // update comment
        if ($comment->getId()) {
            $this->getDb()->update('t_comment', $commentData, array('com_id' => $comment->getId()));
        }
        else {
            // insert for first time comment 
            $this->getDb()->insert('t_comment', $commentData);
            //set the id in the entity 
            $id = $this->getDb()->lastInsertId();
            $comment->setId($id);
        }
    }

    /**
     * Create an Comment object based on a DB row.
     *
     * @param array $row The DB row containing Comment data.
     * @return \silex\Domain\Comment
     */
    protected function buildDomainObject(array $row) {
        $comment = new Comment();
        $comment->setId($row['com_id']);
        $comment->setContent($row['com_content']);
        
        if (array_key_exists('art_id', $row)) {
            $articleId = $row['art_id'];
            $article = $this->articleDAO->find($articleId);
            $comment->setArticle($article);
        }

        if (array_key_exists('usr_id', $row)) {
            $userId = $row['usr_id'];
            $user = $this->userDAO->find($userId);
            $comment->setAuthor($user);
        }
        return $comment;
    }

}