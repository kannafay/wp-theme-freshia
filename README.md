# 开发备注

## 开发环境

- PHP: 8.3.26
- MySQL: 5.7.26
- WordPress: 6.8.3
- Node: 22.20.0
- Composer: 2.8.12

## 安装依赖

```bash
# 安装pnpm依赖
pnpm i

# 安装composer依赖
composer i
```

## WP区块开发

```bash
# 创建区块开发环境
pnpm dlx @wordpress/create-block@latest blocks

# 添加一个新的区块
pnpm dlx @wordpress/create-block@latest --no-plugin --namespace="freshia" --category="freshia" --target-dir="src/name"
```

## 开发Kit套件

- Tailwind CSS
- preline
- Axios
- Swup
- Vue
- GSAP
- alpinejs
