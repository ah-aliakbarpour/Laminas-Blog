<?php

namespace Blog\Controller;

use Blog\Form\CommentForm;
use Blog\Model\Entity\PostEntity;
use Blog\Service\CommentService;
use Blog\Service\PostService;
use Doctrine\ORM\EntityManagerInterface;
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

    /**
     * Doctrine entity manager.
     * @var EntityManagerInterface
     */
    public $entityManager;

    public function __construct(CommentService $commentService, PostService $postService)
    {
        $this->commentService = $commentService;
        $this->postService = $postService;
    }

    public function addAction()
    {
        $postId = $this->params()->fromRoute('id', -1);

        $access = $this->postService->access(false, $postId, true);
        if (!$access['done']) {
            $this->flashMessenger()->addErrorMessage($access['data']['errorMessage']);
            return $this->redirect()->toRoute($access['data']['redirectToRoute']);
        }

        /**
         * @var $post PostEntity
         */
        $post = $access['items'];
        $form = new CommentForm();

        if($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();
            $form->setData($data);
            if($form->isValid()) {
                $data = $form->getData();

                $save = $this->commentService->save(['postId' => $postId] + $data);

                if ($save['done'])
                    $this->flashMessenger()->addSuccessMessage('New comment added successfully!');
                else
                    $this->flashMessenger()->addErrorMessage($save['content']);

                return $this->redirect()->toRoute('post/view', ['id' => $postId]);
            }
        }

        return (new ViewModel([
            'form' => $form,
            'post' => $post,
        ]))->setTemplate('blog/post/view');
    }
}