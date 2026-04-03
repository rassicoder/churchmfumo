<?php

return [
    'roles' => [
        'Super Admin',
        'Association Executive',
        'Church Admin',
        'Department Leader',
        'Treasurer',
        'Secretary',
    ],

    'default_registration_role' => 'Secretary',

    'elevated_role_assigners' => [
        'Super Admin',
        'Association Executive',
    ],

    'permissions' => [
        'users.view',
        'users.create',
        'users.update',
        'users.delete',
        'roles.assign',
        'roles.manage',

        'associations.view',
        'associations.create',
        'associations.update',
        'associations.delete',

        'churches.view',
        'churches.create',
        'churches.update',
        'churches.delete',

        'departments.view',
        'departments.create',
        'departments.update',
        'departments.delete',
        'projects.view',
        'projects.create',
        'projects.update',
        'projects.delete',

        'members.view',
        'members.create',
        'members.update',
        'members.delete',

        'activities.view',
        'activities.create',
        'activities.update',
        'activities.delete',

        'finance.view',
        'finance.manage',

        'reports.view',
        'reports.generate',

        'minutes.view',
        'minutes.manage',
    ],

    'role_permissions' => [
        'Super Admin' => ['*'],

        'Association Executive' => [
            'users.view',
            'users.create',
            'users.update',
            'roles.assign',
            'associations.view',
            'associations.create',
            'associations.update',
            'churches.view',
            'departments.view',
            'departments.create',
            'departments.update',
            'projects.view',
            'projects.create',
            'projects.update',
            'members.view',
            'members.create',
            'members.update',
            'activities.view',
            'activities.create',
            'activities.update',
            'reports.view',
            'reports.generate',
        ],

        'Church Admin' => [
            'churches.view',
            'departments.view',
            'departments.create',
            'departments.update',
            'projects.view',
            'projects.create',
            'projects.update',
            'members.view',
            'members.create',
            'members.update',
            'activities.view',
            'activities.create',
            'activities.update',
            'minutes.view',
            'minutes.manage',
            'reports.view',
        ],

        'Department Leader' => [
            'departments.view',
            'departments.update',
            'projects.view',
            'projects.update',
            'members.view',
            'activities.view',
            'activities.create',
            'activities.update',
            'minutes.view',
        ],

        'Treasurer' => [
            'finance.view',
            'finance.manage',
            'reports.view',
            'reports.generate',
            'activities.view',
            'projects.view',
            'projects.update',
        ],

        'Secretary' => [
            'members.view',
            'members.create',
            'members.update',
            'minutes.view',
            'minutes.manage',
            'activities.view',
            'projects.view',
        ],
    ],
];
