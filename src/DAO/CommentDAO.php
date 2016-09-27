<?php

namespace silex\DAO;

use silex\Domain\Comment;

class CommentDAO extends DAO
{
    private $articleDAO;

    public function setArticleDAO(ArticleDAO $articleDAO)
    {
        $this->articleDAO = $articleDAO;
    }
    /**
     * Return a list of all Comments, sorted by date (most recent first).
     *
     * @return array A list of all Comments.
     */
    public function findAllByArticle($articleId) {
        $article = $this->articleDAO->find($articleId);
        $sql = "select com_id, com_author, com_content from t_comment where art_id=? order by com_id";
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

    /**
     * Create an Comment object based on a DB row.
     *
     * @param array $row The DB row containing Comment data.
     * @return \MicroCMS\Domain\Comment
     */
    protected function buildDomainObject(array $row) {
        $comment = new Comment();
        $comment->setId($row['com_id']);
        $comment->setContent($row['com_content']);
        $comment->setAuthor($row['com_author']);
        
        if (array_key_exists('art_id', $row)) {
            $articleId = $row['art_id'];
            $article = $this->articleDAO->find($articleId);
            $comment->setArticle($article);
        }
        return $comment;
    }

}