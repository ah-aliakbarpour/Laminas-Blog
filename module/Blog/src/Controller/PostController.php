<?php

namespace Blog\Controller;

use Blog\Form\PostForm;
use Blog\Service\PostService;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use User\Service\AuthService;

class PostController extends AbstractActionController
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

    public function indexAction()
    {
        $posts = $this->postService->getPosts();

        return new ViewModel([
            'posts' => $posts,
        ]);
    }

    public function viewAction()
    {
        //
    }

    public function addAction()
    {
        if (!$this->authService->hasIdentity()) {
            $this->flashMessenger()->addErrorMessage('To do this action you should login!');
            return $this->redirect()->toRoute('login');
        }

        $form = new PostForm();
        $request = $this->getRequest();

        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $data = $form->getData();

                $this->postService->addPost($data);

                $this->flashMessenger()->addSuccessMessage('New Post added successfully!');
                return $this->redirect()->toRoute('post');
            }
        }

        return new ViewModel([
            'form' => $form
        ]);
    }

    public function editAction()
    {
        if (!$this->authService->hasIdentity()) {
            $this->flashMessenger()->addErrorMessage('To do this action you should login!');
            return $this->redirect()->toRoute('login');
        }

    }

    public function deleteAction()
    {
        if (!$this->authService->hasIdentity()) {
            $this->flashMessenger()->addErrorMessage('To do this action you should login!');
            return $this->redirect()->toRoute('login');
        }

    }

}