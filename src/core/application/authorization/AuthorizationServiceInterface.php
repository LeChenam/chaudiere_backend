<?php

namespace chaudiere\core\application\authorization;
interface AuthorizationServiceInterface
{
    const PERMISSION_UPDATE_EVENT = 'update_event';
    const PERMISSION_DELETE_EVENT = 'delete_event';
    const PERMISSION_CREATE_EVENT = 'create_event';
    const PERMISSION_CREATE_USER = 'create_user';


    public function isAuthorized(string $userId, string $action, ?string $resourceId=null): bool;
    public function isSuperAdmin(string $userId): bool;
}