# Suda11 升级至 Laravel 12 及技术栈升级计划

## 1. 核心框架升级 (Laravel 12)
- **PHP 版本要求**: 确保环境支持 PHP 8.2+ (Laravel 12 可能要求更高)。
- **依赖更新**: 
    - 更新 `composer.json` 中的 `illuminate/*` 依赖至 `^12.0`。
    - 检查并更新第三方包（如 `intervention/image`, `livewire/livewire`）至兼容 Laravel 12 的版本。
- **代码适配**:
    - 检查 Laravel 12 的 Breaking Changes。
    - 适配新的路由定义方式或中间件变化。

## 2. 构建工具升级 (Mix -> Vite)
- **移除 Laravel Mix**: 删除 `webpack.mix.js` 和相关依赖。
- **引入 Vite**: 
    - 安装 `vite` 和 `laravel-vite-plugin`。
    - 创建 `vite.config.js`。
    - 将视图中的 `mix()` 替换为 `@vite()`。
- **资源整理**: 优化 `resources/assets` 结构，利用 Vite 的原生 ESM 支持。

## 3. 前端框架升级
- **Vue 2 -> Vue 3**: 
    - Suda11 目前大量使用 Vue 2，建议逐步迁移至 Vue 3。
    - 或者，考虑到已经引入了 Livewire 3，可以考虑将部分 Vue 组件替换为 Livewire 组件，减少前端复杂度。
- **移除 jQuery**: 
    - 识别并替换依赖 jQuery 的插件（如 `select2`, `datepicker`）。
    - 转向原生 JS 或基于 Vue 3 / Alpine.js 的轻量级替代方案。

## 4. 扩展系统优化
- **增强 Composer 支持**: 优化 `suda-extension` 的自动发现机制，支持更复杂的依赖关系。
- **配置统一化**: 逐步淘汰 `config.php`，全面转向 `config.yaml` 以获得更好的可读性和跨语言支持。

## 5. 性能与缓存优化
- **路由缓存**: 确保动态加载的扩展路由能够被 Laravel 的 `route:cache` 正确处理。
- **配置缓存**: 优化 `ExtensionService` 的缓存逻辑，减少磁盘 IO。
