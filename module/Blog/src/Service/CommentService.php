<?php

namespace Blog\Service;

use Blog\Model\Entity\CommentEntity;
use Blog\Model\Entity\PostEntity;
use Blog\Model\Repository\CommentRepository;
use Blog\Model\Service\BlogModelService;

class CommentService implements CommentServiceInterface
{
    /**
     * @var CommentRepository
     */
    public $commentRepository;

    public function __construct(BlogModelService $blogModelService)
    {
        $this->commentRepository = $blogModelService->commentRepository();
    }

    public function add(PostEntity $post, array $data): array
    {
        $comment = new CommentEntity();
        $comment->setPost($post);
        $comment->setAuthor($data['author']);
        $comment->setContext($data['context']);

        $this->commentRepository->save($comment);

        return [
            'done' => true,
        ];
    }
}