<?php

return [
    'users' =>[
        //The name of the super administrator role
        'super_admin_role' => 'Super_administrator',

        //The name of the administrator role    
        'admin_role' => 'Administrator',

        //The name of the Eexcutive role
        'executive_role' => 'Executive',

        //The name of the Member role (default role for all newly registered users)
        'member_role' => 'Member',
    ],
    
    /* permission name for admin, member and executive */

    'admin_permissions' => [
        'access_role_management',
        'access_permission_management',
        'write_user',
    ],  

    'executive_permissions' => [
        'view_backend',
        'access_logs',
        'read_users',
        'impersonate_users',
    ],

    'member_permissions' => [
        'view_internal',
        'access_campaigns',
        'access_reports',
    ],
];