<?php

namespace User\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use User\Form\RegisterForm;
use User\Service\AuthService;

class AuthController extends AbstractActionController
{
    /**
     * @var AuthService
     */
    private $authService;


    public function __construct(AuthService $userService)
    {
        $this->authService = $userService;
    }

    public function registerAction()
    {
        if ($this->authService->hasIdentity())
            return $this->redirect()->toRoute('home');

        $request = $this->getRequest();
        $form = new RegisterForm();

        if ($request->isPost()) {
            $form->setData($request->getPost());

            if ($form->isValid()) {
                // Get validated data
                $data = $form->getData();

                $register = $this->authService->register($data);

                if ($register['done']) {
                    $this->flashMessenger()->addSuccessMessage('Account successfully created.');
                    return $this->redirect()->toRoute('home');
                }
                else {
                    $this->flashMessenger()->addErrorMessage('Error');
                    return $this->redirect()->refresh();
                }
            }
        }

        return new ViewModel([
            'form' => $form,
        ]);
    }

    public function loginAction()
    {
        //$this->authService->register();
    }

    public function logoutAction()
    {
        //$this->authService->register();
    }
}