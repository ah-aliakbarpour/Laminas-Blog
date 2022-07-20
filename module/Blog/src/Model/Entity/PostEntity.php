<?php

namespace Blog\Model\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use User\Model\Entity\UserEntity;
use Doctrine\ORM\Mapping as ORM;

/**
 * This class represents a single post in a blog.
 * @ORM\Entity
 * @ORM\Table(name="post")
 */
class PostEntity
{
    public function __construct()
    {
        $this->comments = new ArrayCollection();
    }

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(name="id")
     */
    protected $id;

    /**
     * @ORM\Column(name="title")
     */
    protected $title;

    /**
     * @ORM\Column(name="context")
     */
    protected $context;

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

    public function getTitle()
    {
        return $this->title;
    }

    public function setTitle($title): void
    {
        $this->title = $title;
    }

    public function getContext()
    {
        return $this->context;
    }

    public function setContext($context): void
    {
        $this->context = $context;
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
        return $this->createdAt;
    }

    public function setUpdatedAt($createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @ORM\OneToMany(targetEntity="\Blog\Model\Entity\CommentEntity", mappedBy="post")
     * @ORM\JoinColumn(name="id", referencedColumnName="post_id")
     */
    protected $comments;

    /**
     * Returns comments for this post.
     */
    public function getComments(): ArrayCollection
    {
        return $this->comments;
    }

    /**
     * Adds a new comment to this post.
     */
    public function addComment(CommentEntity $comment): void
    {
        $this->comments[] = $comment;
    }

    /**
     * @ORM\ManyToOne(targetEntity="\User\Model\Entity\UserEntity", inversedBy="posts")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    protected $user;

    /**
     * Returns associated user.
     */
    public function getUser(): UserEntity
    {
        return $this->user;
    }

    /**
     * Sets associated user.
     */
    public function setUser(UserEntity $user): void
    {
        $this->user = $user;
        $user->addPost($this);
    }
}