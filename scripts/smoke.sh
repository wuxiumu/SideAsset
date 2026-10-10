#!/usr/bin/env bash
# 轻量冒烟：结构检查 + 前端依赖可装则 vite build（不强制 MySQL/Laravel 全起）
set -euo pipefail
ROOT="$(cd "$(dirname "$0")/.." && pwd)"
cd "$ROOT"
fail() { echo "✗ $1" >&2; exit 1; }
ok() { echo "✓ $1"; }

command -v node >/dev/null || fail "需要 Node 20+"
command -v php >/dev/null || fail "需要 PHP"
[[ -f package.json && -f vite.config.js ]] || fail "缺少前端入口"
[[ -f side-api-laravel/public/index.php ]] || fail "缺少 Laravel public/index.php"
[[ -f side-api-laravel/.env.example ]] || fail "缺少 Laravel .env.example"
[[ -f .env.example ]] || fail "缺少根 .env.example"
ok "核心文件齐全"

# seeder 仅占位，不应含真实 sk-
if grep -RInE 'sk-[a-zA-Z0-9]{20,}' side-api-laravel/database 2>/dev/null | grep -v 'sk-xxx'; then
  fail "database 疑似真实 API Key"
fi
ok "无长真实 sk- 硬编码（seeder 占位除外）"

php -l side-api-laravel/public/index.php >/dev/null
ok "PHP lint OK"

if [[ ! -d node_modules ]]; then
  npm install --no-fund --no-audit
fi
npm run build
ok "vite build 通过"

echo ""
echo "smoke: ok"
echo "提示：全栈本地可用 ./easy.sh 或分别启 Laravel(8000)+Vite"
