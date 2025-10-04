# 命令参数

1. 动态区块
2. 只生成区块文件，不生成插件相关文件

```bash
# 1
npx @wordpress/create-block@latest --variant="dynamic" --no-plugin --target-dir="test" --namespace="freshia" --title="测试" --short-description="区块描述" --category="freshia" --wp-scripts

# 2
npx @wordpress/create-block@latest --namespace="freshia" --category="freshia" --no-plugin
```
