<?php

namespace App\Services;

class MenuService
{
    /**
     * Get the full menu structure, separated by sections that can be filtered.
     */
    public function getMenuItems(): array
    {
        $user = auth()->user();
        if (!$user) return [];

        $menu = [];

        // USER SECTION
        $menu = array_merge($menu, $this->getUserMenu());

        // ADMIN SECTION (Conditional)
        if ($user->hasRole('admin')) {
            $menu = array_merge($menu, $this->getAdminMenu());
        }

        return $this->filterMenu($menu);
    }

    /**
     * User specific menu items.
     */
    protected function getUserMenu(): array
    {
        return [
            [
                'name'    => __('menu.main'),
                'is_label' => true,
            ],
            [
                'url'     => route('dashboard'),
                'name'    => __('menu.dashboard'),
                'icon'    => 'house-door',
                'i18n'    => 'dashboard',
                'access'  => 'view dashboard',
                'slug'    => 'dashboard',
            ],
            [
                'url'     => route('orders.index'),
                'name'    => __('menu.quick_order'),
                'icon'    => 'lightning-fill',
                'i18n'    => 'quick_order',
                'access'  => 'buy numbers',
                'slug'    => 'orders.index',
            ],
            [
                'url'     => route('rentals.index'),
                'name'    => __('menu.long_term'),
                'icon'    => 'calendar-event',
                'i18n'    => 'long_term',
                'access'  => 'rent numbers',
                'slug'    => 'rentals.index',
            ],
            [
                'name'    => __('menu.balance'),
                'is_label' => true,
                'access'  => 'manage wallet',
            ],
            [
                'url'     => route('wallet.deposit'),
                'name'    => __('menu.deposit'),
                'icon'    => 'credit-card',
                'i18n'    => 'deposit',
                'access'  => 'manage wallet',
                'slug'    => 'wallet.deposit',
            ],
            [
                'name'    => __('menu.business'),
                'is_label' => true,
                'access'  => 'manage subaccounts',
            ],
            [
                'url'     => route('subaccounts.index'),
                'name'    => __('menu.sub_accounts'),
                'icon'    => 'people',
                'i18n'    => 'sub_accounts',
                'access'  => 'manage subaccounts',
                'slug'    => 'subaccounts.index',
            ],
            [
                'name'    => __('menu.contact'),
                'is_label' => true,
            ],
            [
                'url'     => route('support.index'),
                'name'    => __('menu.support'),
                'icon'    => 'headset',
                'i18n'    => 'support',
                'access'  => 'view tickets',
                'slug'    => 'support.index',
            ],
            [
                'name'    => __('menu.affiliates'),
                'is_label' => true,
                'access'  => 'view referrals',
            ],
            [
                'url'     => route('referrals.index'),
                'name'    => __('menu.referrals'),
                'icon'    => 'share',
                'i18n'    => 'referrals',
                'access'  => 'view referrals',
                'slug'    => 'referrals.index',
            ],
        ];
    }

    /**
     * Admin specific menu items.
     */
    protected function getAdminMenu(): array
    {
        return [
            [
                'name'    => __('menu.admin'),
                'is_label' => true,
                'access'  => 'view dashboard|manage tenants|manage users|manage providers|manage pricing|manage settings|view analytics',
            ],
            [
                'url'     => route('admin.dashboard'),
                'name'    => __('menu.dashboard'),
                'icon'    => 'house-door',
                'i18n'    => 'dashboard',
                'access'  => 'view dashboard',
                'slug'    => 'admin.dashboard',
            ],
            [
                'url'     => '',
                'name'    => __('menu.management'),
                'icon'    => 'gear',
                'i18n'    => 'management',
                'slug'    => 'admin',
                'access'  => 'view dashboard|manage tenants|manage users|manage providers|manage pricing|manage settings|view analytics|view earnings report|view usage report|view number performance|view order analytics|export reports|bulk import numbers|bulk export orders|bulk cancel orders|bulk refund',
                'submenu' => [
                    [
                        'url'    => route('admin.tenants.index'),
                        'slug'   => 'admin.tenants.index',
                        'name'   => __('menu.tenants'),
                        'i18n'   => 'tenants',
                        'access' => 'manage tenants',
                        'icon'   => 'building',
                    ],
                    [
                        'url'    => route('admin.users.index'),
                        'slug'   => 'admin.users.index',
                        'name'   => __('menu.users'),
                        'i18n'   => 'users',
                        'access' => 'manage users',
                        'icon'   => 'people',
                    ],
                    [
                        'url'    => route('admin.providers.index'),
                        'slug'   => 'admin.providers.index',
                        'name'   => __('menu.providers'),
                        'i18n'   => 'providers',
                        'access' => 'manage providers',
                        'icon'   => 'hdd-network',
                    ],
                    [
                        'url'    => route('admin.pricing.index'),
                        'slug'   => 'admin.pricing.index',
                        'name'   => __('menu.pricing'),
                        'i18n'   => 'pricing',
                        'access' => 'manage pricing',
                        'icon'   => 'currency-dollar',
                    ],
                    [
                        'url'    => route('admin.settings.index'),
                        'slug'   => 'admin.settings.index',
                        'name'   => __('menu.settings'),
                        'i18n'   => 'settings',
                        'access' => 'manage settings',
                        'icon'   => 'sliders',
                    ],
                    [
                        'url'    => route('admin.analytics.index'),
                        'slug'   => 'admin.analytics.index',
                        'name'   => __('menu.analytics'),
                        'i18n'   => 'analytics',
                        'access' => 'view analytics',
                        'icon'   => 'chart-line',
                    ],
                    [
                        'url'     => '',
                        'name'    => __('menu.reports'),
                        'icon'    => 'chart-bar',
                        'i18n'    => 'reports',
                        'slug'    => 'reports',
                        'access'  => 'view earnings report|view usage report|view number performance|view order analytics|export reports',
                        'submenu' => [
                            [
                                'url'    => route('admin.reports.earnings'),
                                'slug'   => 'admin.reports.earnings',
                                'name'   => __('menu.earnings_report'),
                                'i18n'   => 'earnings_report',
                                'access' => 'view earnings report',
                                'icon'   => 'chart-line',
                            ],
                            [
                                'url'    => route('admin.reports.usage'),
                                'slug'   => 'admin.reports.usage',
                                'name'   => __('menu.usage_report'),
                                'i18n'   => 'usage_report',
                                'access' => 'view usage report',
                                'icon'   => 'activity',
                            ],
                            [
                                'url'    => route('admin.reports.number-performance'),
                                'slug'   => 'admin.reports.number-performance',
                                'name'   => __('menu.number_performance'),
                                'i18n'   => 'number_performance',
                                'access' => 'view number performance',
                                'icon'   => 'phone',
                            ],
                            [
                                'url'    => route('admin.reports.order-analytics'),
                                'slug'   => 'admin.reports.order-analytics',
                                'name'   => __('menu.order_analytics'),
                                'i18n'   => 'order_analytics',
                                'access' => 'view order analytics',
                                'icon'   => 'shopping-cart',
                            ],
                            [
                                'url'    => route('admin.reports.export'),
                                'slug'   => 'admin.reports.export',
                                'name'   => __('menu.export_reports'),
                                'i18n'   => 'export_reports',
                                'access' => 'export reports',
                                'icon'   => 'download',
                            ],
                        ],
                    ],
                    [
                        'url'     => '',
                        'name'    => __('menu.bulk_operations'),
                        'icon'    => 'layers',
                        'i18n'    => 'bulk_operations',
                        'slug'    => 'bulk',
                        'access'  => 'bulk import numbers|bulk export orders|bulk cancel orders|bulk refund',
                        'submenu' => [
                            [
                                'url'    => route('admin.bulk.import-numbers'),
                                'slug'   => 'admin.bulk.import-numbers',
                                'name'   => __('menu.import_numbers'),
                                'i18n'   => 'import_numbers',
                                'access' => 'bulk import numbers',
                                'icon'   => 'upload',
                            ],
                            [
                                'url'    => route('admin.bulk.export-orders'),
                                'slug'   => 'admin.bulk.export-orders',
                                'name'   => __('menu.export_orders'),
                                'i18n'   => 'export_orders',
                                'access' => 'bulk export orders',
                                'icon'   => 'file-export',
                            ],
                            [
                                'url'    => route('admin.bulk.cancel-orders'),
                                'slug'   => 'admin.bulk.cancel-orders',
                                'name'   => __('menu.cancel_orders'),
                                'i18n'   => 'cancel_orders',
                                'access' => 'bulk cancel orders',
                                'icon'   => 'x-circle',
                            ],
                            [
                                'url'    => route('admin.bulk.refund'),
                                'slug'   => 'admin.bulk.refund',
                                'name'   => __('menu.bulk_refund'),
                                'i18n'   => 'bulk_refund',
                                'access' => 'bulk refund',
                                'icon'   => 'currency-dollar',
                            ],
                        ],
                    ],
                ],
            ],
        ];
    }

    /**
     * Filter menu items based on user permissions.
     * Handles nested submenus recursively.
     */
    protected function filterMenu(array $items): array
    {
        $user = auth()->user();
        if (!$user) return [];

        $filtered = [];

        foreach ($items as $item) {
            // Check access for current item
            if (isset($item['access'])) {
                $permissions = explode('|', $item['access']);
                $hasAccess = false;
                foreach ($permissions as $permission) {
                    if ($user->can(trim($permission))) {
                        $hasAccess = true;
                        break;
                    }
                }
                if (!$hasAccess) {
                    continue;
                }
            }

            // Handle submenu recursively
            if (isset($item['submenu']) && is_array($item['submenu'])) {
                $filteredSubmenu = $this->filterMenu($item['submenu']);
                
                // Only keep the parent item if it has visible submenu items
                if (!empty($filteredSubmenu)) {
                    $item['submenu'] = $filteredSubmenu;
                    $filtered[] = $item;
                }
                // Skip parent if no submenu items are accessible
                continue;
            }

            // Add items without submenu or labels
            $filtered[] = $item;
        }

        return $filtered;
    }
}