<?php

namespace App\Models;

use \Backpack\PermissionManager\app\Models\Role as BaseRole;

class Role extends BaseRole
{
    const ROLE_ADMIN = 'admin';
    const ROLE_CUSTOMER_SERVICE = 'customer-service';
}
