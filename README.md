# 备注

## 区块开发

```bash
# 创建一个区块开发环境
npx @wordpress/create-block@latest blocks

# 添加一个模板区块到src目录，name为区块名
npx @wordpress/create-block@latest --no-plugin --namespace="freshia" --category="freshia" --target-dir="src/name"
```

## JS模块

采用 `ES6` 模块化开发

- `modules` 目录为 `main.js` 文件主题引用功能模块
- `packages` 目录为主题公共功能模块
- `libs` 目录为第三方库
