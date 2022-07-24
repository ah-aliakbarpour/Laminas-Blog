<?php

namespace Blog\Controller;

use Blog\Form\CommentForm;
use Blog\Form\PostForm;
use Blog\Form\PostSearchForm;
use Blog\Service\PostService;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use User\Service\AuthService;

class ViewController extends AbstractActionController
{
    /**
     * @var PostService
     */
    private $postService;

    public function __construct(PostService $postService)
    {
        $this->postService = $postService;
    }

    public function viewAction()
    {
        $postId = $this->params()->fromRoute('id', -1);

        $post = $this->postService->find($postId);
        if ($post == null) {
            $this->getResponse()->setStatusCode(404);
            return false;
        }

        $form = new CommentForm();

        return new ViewModel([
            'form' => $form,
            'post' => $post,
        ]);
    }
}