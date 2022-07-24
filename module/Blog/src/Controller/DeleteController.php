<?php

namespace Blog\Controller;

use Blog\Service\PostService;
use Laminas\Mvc\Controller\AbstractActionController;
use User\Service\AuthService;

class DeleteController extends AbstractActionController
{
    /**
     * @var PostService
     */
    private $postService;

    /**
     * @var AuthService
     */
    private $authService;

    public function __construct(PostService $postService, AuthService $authService)
    {
        $this->postService = $postService;
        $this->authService = $authService;
    }

    public function deleteAction()
    {
        if (!$this->authService->hasIdentity()) {
            $this->flashMessenger()->addErrorMessage('To do this action you should login!');
            return $this->redirect()->toRoute('login');
        }

        $postId = $this->params()->fromRoute('id', -1);

        // Find existing post in the database.
        $post = $this->postService->find($postId);
        if ($post == null) {
            $this->getResponse()->setStatusCode(404);
            return false;
        }

        if (!$this->postService->userHasAccess($post)) {
            $this->flashMessenger()->addErrorMessage('You you don\'t have access to this action!');
            return $this->redirect()->toRoute('post');
        }

        $delete = $this->postService->delete($post);

        if ($delete['done'])
            $this->flashMessenger()->addSuccessMessage('Post deleted successfully!');
        else
            $this->flashMessenger()->addErrorMessage($delete['content']);

        return $this->redirect()->toRoute('post');
    }
}