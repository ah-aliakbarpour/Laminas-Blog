<?php

namespace Blog\Controller;

use Blog\Form\PostForm;
use Blog\Service\PostService;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use User\Service\AuthService;

class EditController extends AbstractActionController
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
}