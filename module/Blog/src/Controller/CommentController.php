<?php

namespace Blog\Controller;

use Blog\Form\CommentForm;
use Blog\Service\CommentService;
use Blog\Service\PostService;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;

class CommentController extends AbstractActionController
{
    /**
     * @var CommentService
     */
    private $commentService;

    /**
     * @var PostService
     */
    private $postService;

    public function __construct(CommentService $commentService, PostService $postService)
    {
        $this->commentService = $commentService;
        $this->postService = $postService;
    }

    public function addAction()
    {
        $postId = $this->params()->fromRoute('id', -1);

        $post = $this->postService->find($postId);
        // If there is no post or request method was not post
        if ($post == null || !$this->getRequest()->isPost()) {
            $this->getResponse()->setStatusCode(404);
            return false;
        }

        $form = new CommentForm();

        if($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();
            $form->setData($data);
            if($form->isValid()) {
                $data = $form->getData();

                $add = $this->commentService->add($post, $data);

                if ($add['done'])
                    $this->flashMessenger()->addSuccessMessage('New comment added successfully!');
                else
                    $this->flashMessenger()->addErrorMessage($add['content']);

                return $this->redirect()->toRoute('post/view', ['id' => $postId]);
            }
        }

        return (new ViewModel([
            'form' => $form,
            'post' => $post,
        ]))->setTemplate('blog/post/view');
    }
}