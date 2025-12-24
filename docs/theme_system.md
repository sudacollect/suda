# Suda11 模板系统分析

## 1. 模板实现逻辑
Suda11 的模板系统通过 `ThemeService` 实现，支持为不同的应用场景（如 `site`, `admin`）设置不同的主题。

## 2. 模板存储与结构
- **存储路径**: `public/suda/theme/{app}/{theme_name}/`
- **核心文件**:
    - `theme.php`: 模板配置文件，定义名称、作者、版本以及支持的 **Widgets**。
    - `views/`: 模板视图文件。
    - `design/`: 静态资源（CSS, JS, Images）。
    - `screenshot.png`: 模板预览图。

## 3. 渲染流程
系统重写了控制器的 `display` 方法，最终调用 `ThemeService::render`：
1. **确定主题**: 从配置或数据库中获取当前激活的主题。
2. **视图查找优先级**:
    - `public/suda/theme/{app}/{theme}/views/` (最高优先级，允许用户自定义覆盖)
    - `resources/views/{app}/` (项目级覆盖)
    - `suda11/resources/views/{app}/` (系统默认)
3. **命名空间注册**: 动态注册 `view_app`, `view_suda`, `view_path` 等命名空间。

## 4. 模板挂件 (Widgets)
模板可以通过 `theme.php` 定义可用的挂件区域（如 `sidebar`, `content`）。这些区域可以动态插入由扩展或系统提供的 Widget 组件。
使用 `arrilot/laravel-widgets` 实现，支持异步加载。
