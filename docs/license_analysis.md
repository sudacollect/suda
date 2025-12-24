# Suda11 Composer 依赖包及协议分析报告

## 1. 依赖包概览与协议检查

以下是 `suda11/composer.json` 中定义的核心依赖包及其授权协议分析：

| 依赖包名称 | 当前版本 | 授权协议 | 对 MIT 协议的影响 |
| :--- | :--- | :--- | :--- |
| `maennchen/zipstream-php` | `>=3.1.0` | MIT | 无冲突 |
| `intervention/image` | `^3.6` | MIT | 无冲突 |
| `guzzlehttp/guzzle` | `^7.8` | MIT | 无冲突 |
| `arrilot/laravel-widgets` | `^3.14` | MIT | 无冲突 |
| `willvincent/feeds` | `^2.6` | MIT | 无冲突 (其依赖 SimplePie 为 BSD) |
| `cviebrock/eloquent-sluggable` | `^11.0` | MIT | 无冲突 |
| `simplesoftwareio/simple-qrcode` | `^4.2` | MIT | 无冲突 (其依赖 BaconQrCode 为 BSD) |
| `mews/purifier` | `^3.4` | MIT | **需注意** (其核心 HTMLPurifier 为 LGPL) |
| `laravel/ui` | `^4.0` | MIT | 无冲突 |
| `league/flysystem` | `^3.27` | MIT | 无冲突 |
| `illuminate/support` | `^11.0` | MIT | 无冲突 |
| `livewire/livewire` | `^3.4` | MIT | 无冲突 |
| `symfony/yaml` | `^7.0` | MIT | 无冲突 |

## 2. 协议兼容性详细分析

### LGPL 协议风险评估 (`mews/purifier`)
`mews/purifier` 依赖于 `ezyang/htmlpurifier`，后者采用 **LGPL (GNU Lesser General Public License)** 协议。
- **分析**: LGPL 是一种“弱开源”协议。作为库使用时，它允许 Suda11 保持 MIT 协议，前提是你不修改 HTMLPurifier 的源代码。如果你修改了 HTMLPurifier 本身并随 Suda11 分发，那么修改后的 HTMLPurifier 部分必须保持 LGPL。
- **结论**: 对于 Suda11 作为一个 Dashboard 框架来说，目前的使用方式是安全的，不会强制 Suda11 变成 GPL/LGPL。

### BSD 协议兼容性
`willvincent/feeds` 和 `simplesoftwareio/simple-qrcode` 的底层依赖使用了 BSD 协议。BSD 与 MIT 高度兼容，只需在分发时保留原作者的版权声明即可。

## 3. 升级建议 (面向 Laravel 12)

为了支持 Laravel 12 并保持技术栈的先进性，建议进行以下调整：

### A. 核心框架适配
- **`illuminate/support`**: 升级至 `^12.0`。
- **`laravel/ui`**: 考虑逐步淘汰。Laravel 官方已转向 Starter Kits (Breeze/Jetstream)。如果 Suda11 仅使用其前端脚手架功能，建议迁移至原生 Vite 配置。

### B. 关键组件升级
- **`intervention/image`**: 目前已是 v3，确保代码已适配 v3 的非兼容性变化（从 v2 升级上来时）。
- **`livewire/livewire`**: 保持在 `^3.x`，Livewire 3 与 Laravel 11/12 配合良好。
- **`symfony/yaml`**: 升级至 `^7.1` 或更高，以匹配 Laravel 12 可能使用的 Symfony 组件版本。

### C. 协议优化建议
如果你希望 Suda11 成为一个“纯粹”的 MIT 项目，可以考虑以下替代方案：
- **替代 `mews/purifier`**: 寻找基于 MIT 协议的 HTML 过滤库，或者直接使用 Laravel 内置的 Blade 转义机制（如果适用场景简单）。但考虑到 HTMLPurifier 的成熟度，目前保留 `mews/purifier` 是性价比最高的选择。

## 4. 总结
目前 Suda11 的依赖包中**没有**会导致其 MIT 协议失效的传染性协议（如 GPL）。所有依赖项均与 MIT 兼容，可以放心进行商业或开源分发。
