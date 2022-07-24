<?php

namespace User\Service;

use Laminas\Authentication\AuthenticationService;
use Laminas\Db\Adapter\Adapter;
use User\Model\Service\UserModelService;

interface AuthServiceInterface
{
    public function __construct(
        UserModelService $userModelService,
        AuthenticationService $authenticationService,
        Adapter $dbAdapter
    );

    // Check if user logged in or not
    public function hasIdentity(): bool;

    // Register
    public function register($data): array;

    // Login
    public function login($data): array;

    // Logout
    public function logout();

    // Get current user
    public function getUser();
}