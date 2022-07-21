<?php

namespace User\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use User\Form\LoginForm;
use User\Form\RegisterForm;
use User\Service\AuthService;

class AuthController extends AbstractActionController
{
    /**
     * @var AuthService
     */
    private $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
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
                    return $this->redirect()->toRoute('login');
                }
                else {
                    $this->flashMessenger()->addErrorMessage($register['content']);
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
        if ($this->authService->hasIdentity())
            return $this->redirect()->toRoute('home');

        $request = $this->getRequest();
        $form = new LoginForm();

        if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) {
                // Get validated data
                $data = $form->getData();

                $login = $this->authService->Login($data);
                if ($login['done']) {
                    $this->flashMessenger()->addSuccessMessage('Login was successful!');
                    return $this->redirect()->toRoute('profile');
                }
                else {
                    $this->flashMessenger()->addErrorMessage($login['content']);
                    return $this->redirect()->refresh();
                }
            }
        }

        return new ViewModel([
            'form' => $form,
        ]);
    }

    public function logoutAction()
    {
        if (!$this->authService->hasIdentity())
            return $this->redirect()->toRoute('home');

        $logout = $this->authService->logout();

        if ($logout['done']) {
            $this->flashMessenger()->addSuccessMessage('Logout was successful!');
            return $this->redirect()->toRoute('profile');
        }
        else {
            $this->flashMessenger()->addErrorMessage($logout['content']);
            return $this->redirect()->refresh();
        }
    }

    public function profileAction()
    {
        if (!$this->authService->hasIdentity())
            return $this->redirect()->toRoute('home');

        $user = $this->authService->getUser();

        return new ViewModel([
            'user' => $user,
        ]);
    }
}