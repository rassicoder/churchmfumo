<?php

return [
    'levels' => [
        'association',
        'church',
    ],
    'statuses' => [
        'active',
        'inactive',
        'suspended',
    ],
    'default_status' => 'active',
    'expiring_within_days' => 30,
    'term_expiry_reminder_days_before' => [30, 7, 3],
];
