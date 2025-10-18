# 备注

## 开发环境

- PHP: 8.3.26
- MySQL: 5.7.26
- WordPress: 6.8.3
- Node: 22.20.0
- Composer: 2.8.12

## 安装依赖

```bash
# 安装tailwindcss依赖
pnpm i

# 安装composer依赖
composer i

# 安装WP区块依赖
cd blocks
pnpm i
```

## WP区块开发

```bash
# 创建区块开发环境
npx @wordpress/create-block@latest blocks

# 添加一个新的区块到src目录，name为区块名
npx @wordpress/create-block@latest --no-plugin --namespace="freshia" --category="freshia" --target-dir="src/name"
```

## JS开发

本地JS采用 `ES6` 模块化开发

- `modules` 为 `main.js` 提供主功能模块
- `packages` 主题公共功能模块
- `libs` 第三方库（非模块化）
