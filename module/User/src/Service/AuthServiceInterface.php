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
}