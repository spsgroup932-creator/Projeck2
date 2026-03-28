<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Sidebar Menus Configuration
    |--------------------------------------------------------------------------
    */

    'branches' => [
        'label' => 'Master Rental',
        'route' => 'branches.index',
        'icon' => 'bi bi-building-gear',
        'role' => 'super admin', // Hanya super admin kawan
    ],

    'users' => [
        'label' => 'Manajemen User',
        'route' => 'users.index',
        'icon' => 'bi bi-people-fill',
    ],

    'master_data' => [
        'label' => 'Master Data',
        'icon' => 'bi bi-database-fill-gear',
        'is_dropdown' => true,
        'sub' => [
            'unit' => [
                'label' => 'Data Unit',
                'route' => 'units.index',
                'icon' => 'bi bi-truck-front-fill',
            ],
            'customer' => [
                'label' => 'Data Customer',
                'route' => 'customers.index',
                'icon' => 'bi bi-person-vcard-fill',
            ],
            'driver' => [
                'label' => 'Data Driver',
                'route' => 'drivers.index',
                'icon' => 'bi bi-steering-wheel',
            ],
        ],
    ],
    'job_order' => [
        'label' => 'Job Order',
        'icon' => 'bi bi-file-earmark-text-fill',
        'is_dropdown' => true,
        'sub' => [
            'spj' => [
                'label' => 'Job Order (SPJ)',
                'route' => 'job-orders.index',
                'icon' => 'bi bi-file-earmark-text',
            ],
            'closing' => [
                'label' => 'Closing Data',
                'route' => 'job-orders.closed',
                'icon' => 'bi bi-file-earmark-check',
            ],
            'laporan_spj' => [
                'label' => 'Rekapan SPJ',
                'route' => 'job-orders.report',
                'icon' => 'bi bi-file-earmark-bar-graph',
            ],
        ],
    ],

    'pembayaran' => [
        'label' => 'Pembayaran kawan',
        'route' => 'payments.index',
        'icon' => 'bi bi-cash-coin',
    ],
    'payments_recap' => [
        'label' => 'Rekap Transaksi kawan',
        'route' => 'payments.transactions',
        'icon' => 'bi bi-cash-stack',
    ],
    'payments_settled' => [
        'label' => 'Laporan Lunas kawan',
        'route' => 'payments.settled_report',
        'icon' => 'bi bi-check-all',
    ],
    'claim_kerusakan' => [
        'label' => 'Claim Kerusakan kawan',
        'route' => 'payments.claims',
        'icon' => 'bi bi-shield-exclamation',
    ],
    'maintenance' => [
        'label' => 'Log Maintenance',
        'route' => 'maintenance.index',
        'icon' => 'bi bi-wrench-adjustable-circle-fill',
    ],
];
