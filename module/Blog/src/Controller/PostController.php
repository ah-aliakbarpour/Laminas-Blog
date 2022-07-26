<?php

namespace Blog\Controller;

use Blog\Form\CommentForm;
use Blog\Form\PostForm;
use Blog\Form\PostSearchForm;
use Blog\Model\Entity\PostEntity;
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

    public function __construct(PostService $postService)
    {
        $this->postService = $postService;
    }

    public function indexAction()
    {
        $data = $this->params()->fromQuery();

        $form = new PostSearchForm();
        $form->setData($data);

        $ITEMS_PER_PAGE = 5;

        $paginate = $this->postService->paginate(
            $data['search'] ?? '',
            $data['page'],
            $ITEMS_PER_PAGE
        );

        return new ViewModel([
            'form' => $form,
            'posts' => $paginate['items'],
            'search' => $data['search'],
            'paginateData' => $paginate['data'],
        ]);
    }

    public function viewAction()
    {
        $postId = $this->params()->fromRoute('id', -1);

        $access = $this->access()->view($postId);

        if (!$access['done']) {
            $this->flashMessenger()->addErrorMessage($access['data']['errorMessage']);
            return $this->redirect()->toRoute($access['data']['redirectToRoute']);
        }

        $form = new CommentForm();

        return new ViewModel([
            'form' => $form,
            'post' => $access['items'],
        ]);
    }

    public function addAction()
    {
        $access = $this->access()->add();

        if (!$access['done']) {
            $this->flashMessenger()->addErrorMessage($access['data']['errorMessage']);
            return $this->redirect()->toRoute($access['data']['redirectToRoute']);
        }

        $form = new PostForm();

        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();
            $save = $this->postService->save($data);

            if ($save['done']) {
                $this->flashMessenger()->addSuccessMessage('New Post added successfully!');
                return $this->redirect()->toRoute('post');
            }
            else {
                foreach ($save['data']['errors'] as $error)
                    $this->flashMessenger()->addErrorMessage($error);

                $form->setData($save['data']['formData']);
            }
        }

        return new ViewModel([
            'form' => $form
        ]);
    }

    public function editAction()
    {
        $postId = $this->params()->fromRoute('id', -1);

        $access = $this->access()->edit($postId);

        if (!$access['done']) {
            $this->flashMessenger()->addErrorMessage($access['data']['errorMessage']);
            return $this->redirect()->toRoute($access['data']['redirectToRoute']);
        }

        /**
         * @var $post PostEntity
         */
        $post = $access['items'];
        $form = new PostForm();

        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();
            $save = $this->postService->save(['id' => $postId] + $data);

            if ($save['done']) {
                $this->flashMessenger()->addSuccessMessage('Post edited successfully!');
                return $this->redirect()->toRoute('post');
            }
            else {
                foreach ($save['data']['errors'] as $error)
                    $this->flashMessenger()->addErrorMessage($error);

                $form->setData($save['data']['formData']);
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
        $postId = $this->params()->fromRoute('id', -1);

        $access = $this->access()->delete($postId);

        if (!$access['done']) {
            $this->flashMessenger()->addErrorMessage($access['data']['errorMessage']);
            return $this->redirect()->toRoute($access['data']['redirectToRoute']);
        }

        $delete = $this->postService->delete($postId);

        if ($delete['done'])
            $this->flashMessenger()->addSuccessMessage('Post deleted successfully!');
        else
            $this->flashMessenger()->addErrorMessage($delete['content']);

        return $this->redirect()->toRoute('post');
    }
}