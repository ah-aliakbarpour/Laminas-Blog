<?php

namespace Blog\Controller;

use Blog\Form\CommentForm;
use Blog\Form\PostForm;
use Blog\Form\PostSearchForm;
use Blog\Service\PostService;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use User\Service\AuthService;

class IndexController extends AbstractActionController
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

        $currentPage = intval($data['page'] ?? 1);
        if ($currentPage < 1)
            $currentPage = 1;

        $ITEMS_PER_PAGE = 5;

        $paginate = $this->postService->paginate(
            $data['search'] ?? '',
            $currentPage,
            $ITEMS_PER_PAGE
        );

        return new ViewModel([
            'form' => $form,
            'posts' => $paginate['items'],
            'search' => $data['search'],
            'paginateData' => $paginate['data'],
        ]);
    }
}