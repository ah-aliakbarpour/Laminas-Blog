<?php

namespace Blog\Controller;

use Blog\Form\CommentForm;
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
        $posts = $this->postService->getAll();

        return new ViewModel([
            'posts' => $posts,
        ]);
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

    public function addAction()
    {
        if (!$this->authService->hasIdentity()) {
            $this->flashMessenger()->addErrorMessage('To do this action you should login!');
            return $this->redirect()->toRoute('login');
        }

        $form = new PostForm();

        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();
            $form->setData($data);
            if ($form->isValid()) {
                $data = $form->getData();

                $add = $this->postService->add($data);

                if ($add['done'])
                    $this->flashMessenger()->addSuccessMessage('New Post added successfully!');
                else
                    $this->flashMessenger()->addErrorMessage($add['content']);

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

        $form = new PostForm();

        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();
            $form->setData($data);
            if ($form->isValid()) {

                $data = $form->getData();

                $edit = $this->postService->edit($post, $data);

                if ($edit['done'])
                    $this->flashMessenger()->addSuccessMessage('Post edited successfully!');
                else
                    $this->flashMessenger()->addErrorMessage($edit['content']);

                return $this->redirect()->toRoute('post');
            }
        }
        else {
            // Set form data
            $data = [
                'title' => $post->getTitle(),
                'context' => $post->getContext(),
            ];
            $form->setData($data);
        }

        return new ViewModel([
            'form' => $form,
            'post' => $post,
        ]);
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