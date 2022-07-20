<?php

namespace User\Service;

use Laminas\Authentication\AuthenticationService;
use Laminas\Crypt\Password\Bcrypt;
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

    public function __construct(UserModelService $userModelService, AuthenticationService $authenticationService)
    {
        $this->userRepository = $userModelService->userRepository();
        $this->authenticationService = $authenticationService;
    }

    public function hasIdentity(): bool
    {
        return $this->authenticationService->hasIdentity();
    }

    public function register($data): array
    {
        $user = new UserEntity();
        $user->setName($data['name']);
        $user->setEmail($data['email']);
        $user->setPassword((new Bcrypt())->create($data['password']));

        $this->userRepository->save($user);

        return [
            'done' => true,
        ];
    }

    public function login()
    {
        //
    }

    public function logout()
    {
        //
    }
}