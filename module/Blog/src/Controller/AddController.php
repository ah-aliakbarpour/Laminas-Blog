<?php

namespace Blog\Controller;

use Blog\Form\PostForm;
use Blog\Service\PostService;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use User\Service\AuthService;

class AddController extends AbstractActionController
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


}