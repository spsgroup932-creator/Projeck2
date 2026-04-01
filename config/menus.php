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
        'label' => 'Transaksi',
        'icon' => 'bi bi-file-earmark-text-fill',
        'is_dropdown' => true,
        'sub' => [
            'spj' => [
                'label' => 'Transaksi (SPJ)',
                'route' => 'job-orders.index',
                'icon' => 'bi bi-file-earmark-text',
            ],
            'closing' => [
                'label' => 'Closing Transaksi',
                'route' => 'job-orders.closed',
                'icon' => 'bi bi-file-earmark-check',
            ],
            'laporan_spj' => [
                'label' => 'Rekap Transaksi',
                'route' => 'job-orders.report',
                'icon' => 'bi bi-file-earmark-bar-graph',
            ],
        ],
    ],

    'pembayaran' => [
        'label' => 'Laporan Keuangan',
        'icon' => 'bi bi-cash-coin',
        'is_dropdown' => true,
        'sub' => [
            'payments' => [
                'label' => 'Input Pembayaran',
                'route' => 'payments.index',
                'icon' => 'bi bi-cash-coin',
            ],
            'payments_recap' => [
                'label' => 'Laporan Transaksi',
                'route' => 'payments.transactions',
                'icon' => 'bi bi-cash-stack',
            ],
            'payments_settled' => [
                'label' => 'Laporan Lunas',
                'route' => 'payments.settled_report',
                'icon' => 'bi bi-check-all',
            ],
            'claim_kerusakan' => [
                'label' => 'Claim Kerusakan',
                'route' => 'payments.claims',
                'icon' => 'bi bi-shield-exclamation',
            ],
        ],
    ],
    'maintenance' => [
        'label' => 'Log Maintenance',
        'icon' => 'bi bi-wrench-adjustable-circle-fill',
        'is_dropdown' => true,
        'sub' => [
            'maintenance_log' => [
                'label' => 'Unit Maintenance',
                'route' => 'maintenance.index',
                'icon' => 'bi bi-tools',
            ],
            'checklist_log' => [
                'label' => 'Form Checklist',
                'route' => 'unit-checklists.index',
                'icon' => 'bi bi-clipboard-check',
            ],
            'checklist_history' => [
                'label' => 'Riwayat Checklist',
                'route' => 'unit-checklists.history',
                'icon' => 'bi bi-clock-history',
            ],
        ],
    ],
    'security' => [
        'label' => 'Security & Monitoring',
        'icon' => 'bi bi-shield-lock-fill',
        'is_dropdown' => true,
        'role' => 'super admin',
        'sub' => [
            'audit_log' => [
                'label' => 'Audit Trail Logs',
                'route' => 'security.index',
                'icon' => 'bi bi-activity',
            ],
            'database_backup' => [
                'label' => 'Database Backup',
                'route' => 'backups.index',
                'icon' => 'bi bi-hdd-network',
            ],
        ],
    ],
    'settings' => [
        'label' => 'Pengaturan Rental',
        'route' => 'settings.rental',
        'icon' => 'bi bi-gear-fill',
    ],
    'reports' => [
        'label' => 'Analisis Bisnis',
        'route' => 'reports.index',
        'icon' => 'bi bi-graph-up-arrow',
    ],
    'profile' => [
        'label' => 'Profil Saya',
        'route' => 'profile',
        'icon' => 'bi bi-person-circle',
    ],
];
