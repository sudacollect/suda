# Suda11 扩展系统分析

## 1. 扩展实现原理
Suda11 的扩展系统允许开发者在不修改核心代码的情况下增加功能。扩展可以看作是一个微型的 Laravel 应用，拥有自己的路由、控制器、模型、视图和资源。

## 2. 扩展加载机制
系统支持两种方式加载扩展：

### A. 本地扩展 (Local Extensions)
- **路径**: `app/Extensions/` (可通过 `sudaconf.extension_dir` 配置)
- **识别**: `ExtensionService` 扫描该目录下的子目录，寻找 `config.yaml` 或 `config.php`。
- **结构**:
    - `config.php`: 基础配置（名称、Slug、版本等）
    - `menu.php`: 菜单定义
    - `routes/`: 路由定义（admin.php, web.php, api.php）
    - `Controllers/`: 控制器
    - `resources/views/`: 视图文件

### B. Composer 扩展
- **识别**: 通过扫描 `vendor/composer/installed.json` 中类型为 `suda-extension` 的包。
- **优势**: 支持版本管理和自动化分发。

## 3. 多扩展开发与兼容性
为了避免不同扩展间的冲突，Suda11 采取了以下措施：

- **命名空间隔离**: 每个扩展应使用独立的命名空间（如 `App\Extensions\Piaoping`）。
- **路由前缀**: 扩展路由通常挂载在 `admin/extension/{slug}/` 下，通过 `Sudacore::getExtendAdminRoutes()` 动态加载。
- **视图命名空间**: 核心通过 `view_extension` 命名空间加载扩展视图，避免与系统视图冲突。
- **数据库前缀**: 建议扩展表使用统一前缀或通过配置管理。

## 4. 扩展生命周期
1. **发现**: `ExtensionService` 扫描目录并缓存配置。
2. **安装**: 将扩展信息写入数据库/缓存，并发布必要的静态资源（如 `icon.png`）。
3. **引导**: `SudaServiceProvider` 在启动时加载已安装扩展的视图路径。
4. **路由注册**: 在路由定义阶段，`Sudacore` 引入各扩展的路由文件。
