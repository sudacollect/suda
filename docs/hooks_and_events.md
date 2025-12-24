# Suda11 Hook 与事件系统分析

## 1. Hook 的实现方式
Suda11 主要通过 Laravel 的 **Event (事件)** 系统来实现 Hook 机制。这允许扩展在系统运行的关键节点注入逻辑。

## 2. 核心路由 Hook
在 `suda_route.php` 和 `admin_route.php` 中，系统定义了多个路由事件：
- `Gtd\Suda\Events\Routing`: 路由加载前触发。
- `Gtd\Suda\Events\RoutingAfter`: 路由加载后触发。
- `Gtd\Suda\Events\RoutingAdmin`: 后台路由加载前触发。
- `Gtd\Suda\Events\RoutingAdminAfter`: 后台路由加载后触发。

扩展可以通过监听这些事件，动态注册额外的路由或修改现有路由行为。

## 3. 菜单 Hook
扩展通过 `menu.php` 定义菜单项。`ExtensionService` 在渲染后台界面时，会合并系统菜单和各扩展定义的菜单。
- 支持 `mng_menu` (管理菜单)
- 支持 `user_menu` (用户菜单)
- 支持 `setting_menu` (设置菜单)

## 4. UI Hook (Widgets)
`Sudacore::widget($name, $config)` 是 UI 层面的主要 Hook 方式。
- 系统在视图中预留 Widget 调用点。
- 扩展可以提供符合接口的 Widget 类。
- 通过配置文件或数据库配置，决定哪些 Widget 显示在哪些区域。

## 5. 自动化加载
- **Composer 加载**: 只要包类型为 `suda-extension`，系统会自动识别并触发相关的引导逻辑。
- **App 目录加载**: 放置在 `app/Extensions` 下的目录会被自动扫描。
