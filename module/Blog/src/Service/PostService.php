<?php

namespace Blog\Service;

use Blog\Model\Entity\PostEntity;
use Blog\Model\Repository\PostRepository;
use Blog\Model\Service\BlogModelService;
use User\Service\AuthService;

class PostService implements PostServiceInterface
{
    /**
     * @var PostRepository
     */
    private $postRepository;

    /**
     * @var AuthService
     */
    private $authService;

    public function __construct(BlogModelService $blogModelService, AuthService $authService)
    {
        $this->postRepository = $blogModelService->postRepository();
        $this->authService = $authService;
    }

    public function addPost($data): array
    {
        $post = new PostEntity();
        $post->setTitle($data['title']);
        $post->setContext($data['context']);

        $user = $this->authService->getUser();
        $post->setUser($user);

        $this->postRepository->save($post);

        return [
            'done' => true,
        ];
    }


    public function getPosts(): array
    {
        $posts = $this->postRepository->findAll();

        return $posts;
    }
}