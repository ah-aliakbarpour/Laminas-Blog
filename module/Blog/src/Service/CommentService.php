<?php

namespace Blog\Service;

use Blog\Model\Entity\CommentEntity;
use Blog\Model\Entity\PostEntity;
use Blog\Model\Repository\CommentRepository;
use Blog\Model\Service\BlogModelService;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Interface: Blog\Service\CommentServiceInterface
 */
class CommentService implements CommentServiceInterface
{
    /**
     * @var CommentRepository
     */
    public $commentRepository;

    /**
     * @var PostService
     */
    private $postService;

    /**
     * Doctrine entity manager.
     * @var EntityManagerInterface
     */
    public $entityManager;

    /**
     * @param BlogModelService $blogModelService
     * @param PostService $postService
     */
    public function __construct(BlogModelService $blogModelService, PostService $postService)
    {
        $this->commentRepository = $blogModelService->getCommentRepository();
        $this->entityManager = $this->commentRepository->entityManager;
        $this->postService = $postService;
    }

    /**
     * @param array $data
     * @return array
     */
    public function save(array $data): array
    {
        $find = $this->postService->find($data['postId']);
        if (!$find['done'])
            return [
                'done' => false,
                'content' => 'Post not found!',
            ];

        /**
         * @var $post PostEntity
         */
        $post = $find['items'];

        $comment = new CommentEntity();
        $comment->setPost($post);
        $comment->setAuthor($data['author']);
        $comment->setContext($data['context']);
        $comment->setCreatedAt(date('Y-m-d H:i:s'));

        $this->entityManager->persist($comment);
        $this->entityManager->flush();

        return [
            'done' => true,
        ];
    }
}