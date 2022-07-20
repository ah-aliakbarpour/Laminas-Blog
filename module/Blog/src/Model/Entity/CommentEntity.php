<?php

namespace Blog\Model\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * This class represents a comment related to a post.
 * @ORM\Entity
 * @ORM\Table(name="comment")
 */
class CommentEntity
{
    /**
     * @ORM\Id
     * @ORM\Column(name="id")
     * @ORM\GeneratedValue
     */
    protected $id;

    /**
     * @ORM\Column(name="context")
     */
    protected $context;

    /**
     * @ORM\Column(name="author")
     */
    protected $author;

    /**
     * @ORM\Column(name="created_at")
     */
    protected $createdAt;

    /**
     * @ORM\Column(name="updated_at")
     */
    protected $updatedAt;


    public function getId()
    {
        return $this->id;
    }

    public function setId($id): void
    {
        $this->id = $id;
    }

    public function getContext()
    {
        return $this->context;
    }

    public function setContext($context): void
    {
        $this->context = $context;
    }

    public function getAuthor()
    {
        return $this->author;
    }

    public function setAuthor($author): void
    {
        $this->author = $author;
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    public function setCreatedAt($createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt($updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    /**
     * @ORM\ManyToOne(targetEntity="\Blog\Entity\Post", inversedBy="comments")
     * @ORM\JoinColumn(name="post_id", referencedColumnName="id")
     */
    protected $post;

    /**
     * Returns associated post.
     */
    public function getPost(): PostEntity
    {
        return $this->post;
    }

    /**
     * Sets associated post.
     */
    public function setPost(PostEntity $post): void
    {
        $this->post = $post;
        $post->addComment($this);
    }
}