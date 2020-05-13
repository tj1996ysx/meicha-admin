<?php

namespace App\Models;

use \Backpack\PermissionManager\app\Models\Permission as BasePermission;

class Permission extends BasePermission
{
    const SHOPPER_MANAGEMENT = 'shopper-management';
    const VIEW_DASHBOARD = 'view-dashboard';
    const ORDER_MANAGEMENT = 'order-management';
    const SYSTEM_MANAGEMENT = 'system-management';
    const RESERVE_MANAGEMENT = 'reserve-management';
    const DATA_MANAGEMENT = 'data-management';
}
