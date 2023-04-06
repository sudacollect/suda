<?php
namespace Gtd\Suda\Database\Seeds;

use Illuminate\Database\Seeder;
use Gtd\Suda\Models\Menu;
use Gtd\Suda\Models\MenuItem;

class MenuItemsTableSeeder extends Seeder
{
    public function run()
    {
        $menu = Menu::where('name', 'suda')->firstOrFail();
        
        /*
        |--------------------------------------------------------------------------
        | dashboard
        |--------------------------------------------------------------------------
        |
        | description
        |
        */
        

        $menuItem = MenuItem::firstOrNew([
            'menu_id' => $menu->id,
            'title'   => 'suda_lang::press.menu_items.dashboard',
            'slug'    => 'dashboard',
            'url'     => '',
            'route'   => 'sudaroute.admin.dashboard',
        ]);
        if (!$menuItem->exists) {
            $menuItem->fill([
                'target'     => '_self',
                'icon_class' => 'ion-speedometer',
                'parent_id'  => null,
                'order'      => 1,
            ])->save();
        }

        /*
        |--------------------------------------------------------------------------
        | pages
        |--------------------------------------------------------------------------
        |
        | description
        |
        */
        

        $pageMenuItem = MenuItem::firstOrNew([
            'menu_id' => $menu->id,
            'title'   => 'suda_lang::press.menu_items.page',
            'slug'    => 'page',
            'url'     => '',
            'route'   => 'sudaroute.admin.page',
        ]);
        if (!$pageMenuItem->exists) {
            $pageMenuItem->fill([
                'target'     => '_self',
                'icon_class' => 'ion-document-text',
                'parent_id'  => null,
                'order'      => 2,
            ])->save();
        }
        
        $menuItem = MenuItem::firstOrNew([
            'menu_id' => $menu->id,
            'title'   => 'suda_lang::press.menu_items.page_list',
            'slug'    => 'page_list',
            'url'     => '',
            'route'   => 'sudaroute.admin.page_list',
        ]);
        if (!$menuItem->exists) {
            $menuItem->fill([
                'target'     => '_self',
                'icon_class' => 'ion-settings',
                'parent_id'  => $pageMenuItem->id,
                'order'      => 91,
            ])->save();
        }
        
        $menuItem = MenuItem::firstOrNew([
            'menu_id' => $menu->id,
            'title'   => 'suda_lang::press.menu_items.page_new',
            'slug'    => 'page_new',
            'url'     => '',
            'route'   => 'sudaroute.admin.page_new',
        ]);
        if (!$menuItem->exists) {
            $menuItem->fill([
                'target'     => '_self',
                'icon_class' => 'ion-settings',
                'parent_id'  => $pageMenuItem->id,
                'order'      => 91,
            ])->save();
        }
        
        /*
        |--------------------------------------------------------------------------
        | article
        |--------------------------------------------------------------------------
        |
        | description
        |
        */
        
        $articleMenuItem = MenuItem::firstOrNew([
            'menu_id' => $menu->id,
            'title'   => 'suda_lang::press.menu_items.article',
            'slug'    => 'article',
            'url'     => '',
            'route'   => 'sudaroute.admin.article',
        ]);
        if (!$articleMenuItem->exists) {
            $articleMenuItem->fill([
                'target'     => '_self',
                'icon_class' => 'ion-newspaper',
                'parent_id'  => null,
                'order'      => 4,
            ])->save();
        }
        
        $menuItem = MenuItem::firstOrNew([
            'menu_id' => $menu->id,
            'title'   => 'suda_lang::press.menu_items.article_list',
            'slug'    => 'article_list',
            'url'     => '',
            'route'   => 'sudaroute.admin.article_list',
        ]);
        if (!$menuItem->exists) {
            $menuItem->fill([
                'target'     => '_self',
                'icon_class' => 'ion-settings',
                'parent_id'  => $articleMenuItem->id,
                'order'      => 91,
            ])->save();
        }
        
        $menuItem = MenuItem::firstOrNew([
            'menu_id' => $menu->id,
            'title'   => 'suda_lang::press.menu_items.article_new',
            'slug'    => 'article_new',
            'url'     => '',
            'route'   => 'sudaroute.admin.article_new',
        ]);
        if (!$menuItem->exists) {
            $menuItem->fill([
                'target'     => '_self',
                'icon_class' => 'ion-settings',
                'parent_id'  => $articleMenuItem->id,
                'order'      => 91,
            ])->save();
        }
        
        $menuItem = MenuItem::firstOrNew([
            'menu_id' => $menu->id,
            'title'   => 'suda_lang::press.menu_items.category',
            'slug'    => 'article_category',
            'url'     => '',
            'route'   => 'sudaroute.admin.article_category',
        ]);
        if (!$menuItem->exists) {
            $menuItem->fill([
                'target'     => '_self',
                'icon_class' => 'ion-settings',
                'parent_id'  => $articleMenuItem->id,
                'order'      => 91,
            ])->save();
        }
        
        
        $menuItem = MenuItem::firstOrNew([
            'menu_id' => $menu->id,
            'title'   => 'suda_lang::press.menu_items.tag',
            'slug'    => 'article_tag',
            'url'     => '',
            'route'   => 'sudaroute.admin.article_tag',
        ]);
        if (!$menuItem->exists) {
            $menuItem->fill([
                'target'     => '_self',
                'icon_class' => 'ion-settings',
                'parent_id'  => $articleMenuItem->id,
                'order'      => 91,
            ])->save();
        }
        
        /*
        |--------------------------------------------------------------------------
        | media
        |--------------------------------------------------------------------------
        |
        | description
        |
        */
        
        $menuItem = MenuItem::firstOrNew([
            'menu_id' => $menu->id,
            'title'   => 'suda_lang::press.menu_items.media',
            'slug'    => 'media',
            'url'     => '',
            'route'   => 'sudaroute.admin.media',
        ]);
        if (!$menuItem->exists) {
            $menuItem->fill([
                'target'     => '_self',
                'icon_class' => 'ion-image',
                'parent_id'  => null,
                'order'      => 5,
            ])->save();
        }
        
        
        /*
        |--------------------------------------------------------------------------
        | user
        |--------------------------------------------------------------------------
        |
        | description
        |
        */
        
        
        // $userMenuItem = MenuItem::firstOrNew([
        //     'menu_id' => $menu->id,
        //     'title'   => 'suda_lang::press.menu_items.user',
        //     'slug'    => 'user',
        //     'url'     => '',
        //     'route'   => 'sudaroute.admin.user_list',
        // ]);
        // if (!$userMenuItem->exists) {
        //     $userMenuItem->fill([
        //         'target'     => '_self',
        //         'icon_class' => 'ion-people',
        //         'parent_id'  => null,
        //         'order'      => 6,
        //     ])->save();
        // }
        
        // $menuItem = MenuItem::firstOrNew([
        //     'menu_id' => $menu->id,
        //     'title'   => 'suda_lang::press.menu_items.user_list',
        //     'slug'    => 'user_list',
        //     'url'     => '',
        //     'route'   => 'sudaroute.admin.user_list',
        // ]);
        // if (!$menuItem->exists) {
        //     $menuItem->fill([
        //         'target'     => '_self',
        //         'icon_class' => 'ion-settings',
        //         'parent_id'  => $userMenuItem->id,
        //         'order'      => 91,
        //     ])->save();
        // }
        
        // $menuItem = MenuItem::firstOrNew([
        //     'menu_id' => $menu->id,
        //     'title'   => 'suda_lang::press.menu_items.user_register_rule',
        //     'slug'    => 'user_register_rule',
        //     'url'     => '',
        //     'route'   => 'sudaroute.admin.user_register_rule',
        // ]);
        // if (!$menuItem->exists) {
        //     $menuItem->fill([
        //         'target'     => '_self',
        //         'icon_class' => 'ion-settings',
        //         'parent_id'  => $userMenuItem->id,
        //         'order'      => 91,
        //     ])->save();
        // }
                
        /*
        |--------------------------------------------------------------------------
        | appearance
        |--------------------------------------------------------------------------
        |
        | description
        |
        */
        
        
        $appearanceMenuItem = MenuItem::firstOrNew([
            'menu_id' => $menu->id,
            'title'   => 'suda_lang::press.menu_items.appearance',
            'slug'    => 'appearance',
            'url'     => '',
            'route'   => 'sudaroute.admin.appearance_theme',
        ]);
        if (!$appearanceMenuItem->exists) {
            $appearanceMenuItem->fill([
                'target'     => '_self',
                'icon_class' => 'ion-color-palette',
                'parent_id'  => null,
                'order'      => 7,
            ])->save();
        }
        
        $menuItem = MenuItem::firstOrNew([
            'menu_id' => $menu->id,
            'title'   => 'suda_lang::press.menu_items.appearance_theme',
            'slug'    => 'appearance_theme',
            'url'     => '',
            'route'   => 'sudaroute.admin.appearance_theme',
        ]);
        if (!$menuItem->exists) {
            $menuItem->fill([
                'target'     => '_self',
                'icon_class' => 'ion-settings',
                'parent_id'  => $appearanceMenuItem->id,
                'order'      => 91,
            ])->save();
        }
        
        $menuItem = MenuItem::firstOrNew([
            'menu_id' => $menu->id,
            'title'   => 'suda_lang::press.menu_items.appearance_widget',
            'slug'    => 'appearance_widget',
            'url'     => '',
            'route'   => 'sudaroute.admin.appearance_widget',
        ]);
        if (!$menuItem->exists) {
            $menuItem->fill([
                'target'     => '_self',
                'icon_class' => 'ion-settings',
                'parent_id'  => $appearanceMenuItem->id,
                'order'      => 91,
            ])->save();
        }
        
        /*
        |--------------------------------------------------------------------------
        | setting
        |--------------------------------------------------------------------------
        |
        | description
        |
        */
        
        
        $settingMenuItem = MenuItem::firstOrNew([
            'menu_id' => $menu->id,
            'title'   => 'suda_lang::press.menu_items.setting',
            'slug'    => 'setting',
            'url'     => '',
            'route'   => 'sudaroute.admin.setting_system',
        ]);
        if (!$settingMenuItem->exists) {
            $settingMenuItem->fill([
                'target'     => '_self',
                'icon_class' => 'ion-settings',
                'parent_id'  => null,
                'order'      => 8,
            ])->save();
        }
        
        $menuItem = MenuItem::firstOrNew([
            'menu_id' => $menu->id,
            'title'   => 'suda_lang::press.menu_items.setting_system',
            'slug'    => 'setting_system',
            'url'     => '',
            'route'   => 'sudaroute.admin.setting_system',
        ]);
        if (!$menuItem->exists) {
            $menuItem->fill([
                'target'     => '_self',
                'icon_class' => 'ion-settings',
                'parent_id'  => $settingMenuItem->id,
                'order'      => 1,
            ])->save();
        }
        
        $menuItem = MenuItem::firstOrNew([
            'menu_id' => $menu->id,
            'title'   => 'suda_lang::press.menu_items.setting_dashboard',
            'slug'    => 'setting_dashboard',
            'url'     => '',
            'route'   => 'sudaroute.admin.setting_dashboard',
        ]);
        if (!$menuItem->exists) {
            $menuItem->fill([
                'target'     => '_self',
                'icon_class' => 'ion-settings',
                'parent_id'  => $settingMenuItem->id,
                'order'      => 1,
            ])->save();
        }
        
        $menuItem = MenuItem::firstOrNew([
            'menu_id' => $menu->id,
            'title'   => 'suda_lang::press.menu_items.setting_operate',
            'slug'    => 'setting_operate',
            'url'     => '',
            'route'   => 'sudaroute.admin.setting_operate',
        ]);
        if (!$menuItem->exists) {
            $menuItem->fill([
                'target'     => '_self',
                'icon_class' => 'ion-settings',
                'parent_id'  => $settingMenuItem->id,
                'order'      => 1,
            ])->save();
        }
        
        $menuItem = MenuItem::firstOrNew([
            'menu_id' => $menu->id,
            'title'   => 'suda_lang::press.menu_items.setting_operate_role',
            'slug'    => 'setting_operate_role',
            'url'     => '',
            'route'   => 'sudaroute.admin.setting_operate_role',
        ]);
        if (!$menuItem->exists) {
            $menuItem->fill([
                'target'     => '_self',
                'icon_class' => 'ion-settings',
                'parent_id'  => $settingMenuItem->id,
                'order'      => 1,
            ])->save();
        }
        
        $menuItem = MenuItem::firstOrNew([
            'menu_id' => $menu->id,
            'title'   => 'suda_lang::press.menu_items.setting_operate_org',
            'slug'    => 'setting_operate_org',
            'url'     => '',
            'route'   => 'sudaroute.admin.setting_operate_org',
        ]);
        if (!$menuItem->exists) {
            $menuItem->fill([
                'target'     => '_self',
                'icon_class' => 'ion-settings',
                'parent_id'  => $settingMenuItem->id,
                'order'      => 1,
            ])->save();
        }
        
        /*
        |--------------------------------------------------------------------------
        | tool
        |--------------------------------------------------------------------------
        |
        | description
        |
        */
        

        $toolsMenuItem = MenuItem::firstOrNew([
            'menu_id' => $menu->id,
            'title'   => 'suda_lang::press.menu_items.tool',
            'slug'    => 'tool',
            'url'     => '',
            'route'   => 'sudaroute.admin.tool_menu',
        ]);
        if (!$toolsMenuItem->exists) {
            $toolsMenuItem->fill([
                'target'     => '_self',
                'icon_class' => 'ion-help-buoy',
                'parent_id'  => null,
                'order'      => 9,
            ])->save();
        }

        $menuItem = MenuItem::firstOrNew([
            'menu_id' => $menu->id,
            'title'   => 'suda_lang::press.menu_items.tool_extend',
            'slug'    => 'tool_extend',
            'url'     => '',
            'route'   => 'sudaroute.admin.tool_extend',
        ]);
        if (!$menuItem->exists) {
            $menuItem->fill([
                'target'     => '_self',
                'icon_class' => 'ion-grid',
                'parent_id'  => $toolsMenuItem->id,
                'order'      => 91,
            ])->save();
        }

        $menuItem = MenuItem::firstOrNew([
            'menu_id' => $menu->id,
            'title'   => 'suda_lang::press.menu_items.tool_menu',
            'slug'    => 'tool_menu',
            'url'     => '',
            'route'   => 'sudaroute.admin.tool_menu',
        ]);
        if (!$menuItem->exists) {
            $menuItem->fill([
                'target'     => '_self',
                'icon_class' => 'ion-menu',
                'parent_id'  => $toolsMenuItem->id,
                'order'      => 92,
            ])->save();
        }

        
        
        $menuItem = MenuItem::firstOrNew([
            'menu_id' => $menu->id,
            'title'   => 'suda_lang::press.menu_items.tool_compass',
            'slug'    => 'tool_compass',
            'url'     => '',
            'route'   => 'sudaroute.admin.tool_compass',
        ]);
        if (!$menuItem->exists) {
            $menuItem->fill([
                'target'     => '_self',
                'icon_class' => 'ion-grid',
                'parent_id'  => $toolsMenuItem->id,
                'order'      => 95,
            ])->save();
        }
        
        
    }
}
