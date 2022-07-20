<?php

namespace User\Service;

use Laminas\Authentication\AuthenticationService;
use User\Model\Service\UserModelService;

interface AuthServiceInterface
{
    public function __construct(UserModelService $userModelService, AuthenticationService $authenticationService);
}