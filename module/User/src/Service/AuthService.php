<?php

namespace User\Service;

use Laminas\Authentication\Adapter\DbTable\CredentialTreatmentAdapter;
use Laminas\Authentication\AuthenticationService;
use Laminas\Authentication\Result;
use Laminas\Crypt\Password\Bcrypt;
use Laminas\Db\Adapter\Adapter;
use User\Model\Entity\UserEntity;
use User\Model\Repository\UserRepository;
use User\Model\Service\UserModelService;

class AuthService implements AuthServiceInterface
{
    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var AuthenticationService
     */
    private $authenticationService;

    /**
     * @var Adapter
     */
    private $dbAdapter;

    public function __construct(
        UserModelService $userModelService,
        AuthenticationService $authenticationService,
        Adapter $dbAdapter
    )
    {
        $this->userRepository = $userModelService->userRepository();
        $this->authenticationService = $authenticationService;
        $this->dbAdapter = $dbAdapter;
    }

    public function hasIdentity(): bool
    {
        return $this->authenticationService->hasIdentity();
    }

    public function register($data): array
    {
        if ($this->userRepository->findOneByEmail($data['email']))
            return [
                'done' => false,
                'content' => 'Email has already been taken!'
            ];

        $user = new UserEntity();
        $user->setName($data['name']);
        $user->setEmail($data['email']);
        $user->setPassword((new Bcrypt())->create($data['password']));

        $this->userRepository->save($user);

        return [
            'done' => true,
        ];
    }

    public function login($data): array
    {
        $authAdapter = new CredentialTreatmentAdapter(
            $this->dbAdapter,
            'user',
            'email',
            'password',
        );

        $authAdapter->setIdentity($data['email']);

        $user = $this->userRepository->findOneByEmail($data['email']);

        if (!$user)
            return [
                'done' => false,
                'content' => 'There is no user with this email!',
            ];

        if(!(new Bcrypt())->verify($data['password'], $user->getPassword()))
            return [
                'done' => false,
                'content' => 'Password is incorrect!',
            ];

        $authAdapter->setCredential($user->getPassword());

        $authResult = $this->authenticationService->authenticate($authAdapter);

        if ($authResult->getCode() == Result::SUCCESS)
            return ['done' => true];

        return [
            'done' => false,
            'content' => 'Email or password is incorrect!'
        ];
    }

    public function logout()
    {
        $this->authenticationService->clearIdentity();

        return [
            'done' => true,
        ];
    }

    public function getUser()
    {
        $userEmail = $this->authenticationService->getIdentity();

        $user = $this->userRepository->findOneByEmail($userEmail);

        return $user;
    }
}