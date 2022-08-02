<?php

namespace Blog\Service;

use Blog\Model\Entity\PostEntity;
use Blog\Model\Repository\PostRepository;
use Blog\Model\Service\BlogModelService;
use Doctrine\ORM\EntityManagerInterface;
use User\Service\AuthService;

/**
 * Interface: Blog\Service\PostServiceInterface
 */
class PostService implements PostServiceInterface
{
    /**
     * @var PostRepository
     */
    public $postRepository;

    /**
     * Doctrine entity manager.
     * @var EntityManagerInterface
     */
    public $entityManager;

    /**
     * @var AuthService
     */
    private $authService;

    /**
     * @param BlogModelService $blogModelService
     * @param AuthService $authService
     */
    public function __construct(BlogModelService $blogModelService, AuthService $authService)
    {
        $this->postRepository = $blogModelService->getPostRepository();
        $this->entityManager = $this->postRepository->entityManager;
        $this->authService = $authService;
    }

    /**
     * @param string $postId
     * @return array
     */
    public function find(string $postId): array
    {
        $posts = $this->postRepository->get('', [], false, $postId);

        if (empty($posts))
            return [
                'done' => false,
                'content' => 'The post not found!',
                'data' => [
                    'postId' => $postId,
                    'total' => 0,
                ],
            ];

        return [
            'done' => true,
            'items' => $this->_prepare($posts)[0],
            'data' => [
                'postId' => $postId,
                'total' => 1,
            ],
        ];
    }

    /**
     * @param array $data
     * @return array
     */
    public function save(array $data): array
    {
        // Validation
        $validationData = $this->_validate($data);
        if (!empty($validationData['errors']))
            return [
                'done' => false,
                'context' => 'Validation Error',
                'data' => $validationData,
            ];
        $data = $validationData['formData'];

        // Edit
        if (array_key_exists('id', $data)) {
            $find = $this->find($data['id']);
            if (!$find['done'])
                return [
                    'done' => false,
                    'content' => 'Post not found!',
                ];

            /**
             * @var $post PostEntity
             */
            $post = $find['items'];
            $post->setCreatedAt(date('Y-m-d H:i:s', strtotime($post->getCreatedAt())));
            $post->setUpdatedAt(date('Y-m-d H:i:s'));
        }
        // Add
        else {
            $post = new PostEntity();
            $this->entityManager->persist($post);

            $post->setUser($this->authService->getUser());
            $post->setCreatedAt(date('Y-m-d H:i:s'));
        }

        $post->setTitle($data['title']);
        $post->setContext($data['context']);

        $this->entityManager->flush();

        return [
            'done' => true,
        ];
    }

    /**
     * @param string $postId
     * @return array
     */
    public function delete(string $postId): array
    {
        $find = $this->find($postId);
        if (!$find['done'])
            return [
                'done' => false,
                'content' => 'Post not found!',
            ];

        /**
         * @var $post PostEntity
         */
        $post = $find['items'];

        // Remove associated comments
        foreach ($post->getComments() as $comment)
            $this->entityManager->remove($comment);

        $this->entityManager->remove($post);

        $this->entityManager->flush();

        return [
            'done' => true,
        ];
    }

    /**
     * @param bool $identity
     * @param $postId
     * @param bool $exists
     * @param bool $access
     * @return array
     */
    public function access(bool $identity, $postId = -1, bool $exists = false, bool $access = false): array
    {
        if ($identity && !$this->authService->hasIdentity())
            return [
                'done' => false,
                'data' => [
                    'errorMessage' => 'To do this action you should login!',
                    'redirectToRoute' => 'login',
                ],
            ];

        if ($exists) {
            $find = $this->find($postId);

            if (!$find['done'])
                return [
                    'done' => false,
                    'data' => [
                        'errorMessage' => 'Post not found!',
                        'redirectToRoute' => 'post',
                    ],
                ];

            if ($access) {
                $postUserId = $find['items']->getUser()->getId();
                $currentUserId = $this->authService->getUser()->getId();

                if ($postUserId != $currentUserId)
                    return [
                        'done' => false,
                        'data' => [
                            'errorMessage' => 'You you don\'t have access to this action!',
                            'redirectToRoute' => 'post',
                        ],
                    ];
            }

            return [
                'done' => true,
                'items' => $find['items'],
                'data' => [
                    'total' => 1,
                ],
            ];
        }

        return [
            'done' => true,
        ];
    }

    /**
     * @param string $search
     * @param $currentPage
     * @param int $itemsPerPage
     * @return array
     */
    public function findAll(string $search, $currentPage, int $itemsPerPage): array
    {
        $total = intval($this->postRepository->get($search, [], true)['total']);
        $currentPage = intval($currentPage ?? 1);
        if ($currentPage < 1)
            $currentPage = 1;
        $start = ($currentPage - 1) * $itemsPerPage + 1;
        $paginationData = [
            'start' => $start - 1,
            'maxResults' => $itemsPerPage,
        ];

        $posts = $this->postRepository->get($search, $paginationData);

        $data = [
            'currentPage' => $currentPage,
            'start' => $start,
            'itemsPerPage' => $itemsPerPage,
            'total' => $total,
        ];

        return [
            'done' => true,
            'data' => $data,
            'items' => $this->_prepare($posts),
        ];
    }

    /**
     * @param array $posts
     * @return array
     */
    private function _prepare(array $posts): array
    {
        /**
         * @var $post PostEntity
         */

        foreach ($posts as $post) {
            // Capitalize post title
            $post->setTitle(ucwords($post->getTitle()));
        }

        return $posts;
    }

    /**
     * @param array $data
     * @return array
     */
    private function _validate(array $data): array
    {
        // Filter data
        foreach ($data as $key => &$value)
            $value = trim($value);

        // Validate data
        $errors = [];

        // Title require
        if ($data['title'] === '')
            $errors[] = 'Title is required!';

        // Context require
        if ($data['context'] === '')
            $errors[] = 'Context is required!';

        // Title must have at least 3 characters
        if (strlen($data['title']) < 3)
            $errors[] = 'Title must have at least 3 characters!';

        return [
            'errors' => $errors,
            'formData' => $data,
        ];

    }
}