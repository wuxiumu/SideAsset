#!/bin/bash
# chmod +x ./easy.sh
# 加载 nvm
export NVM_DIR="$([ -z "${XDG_CONFIG_HOME-}" ] && printf %s "${HOME}/.nvm" || printf %s "${XDG_CONFIG_HOME}/nvm")"
[ -s "$NVM_DIR/nvm.sh" ] && \. "$NVM_DIR/nvm.sh"  # This loads nvm

# 设置 Node.js 版本
echo "正在使用 nvm 设置 Node.js 版本为 16..."
nvm use 20
if [ $? -ne 0 ]; then
    echo "设置 Node.js 版本失败，退出..."
    exit 1
fi
echo "Node.js 版本设置成功：$(node -v)"

# 检查 npm 是否已安装
echo "正在检查 npm 是否已安装..."
if ! command -v npm &> /dev/null; then
    echo "npm 未安装，退出..."
    exit 1
fi
echo "npm 已安装：$(npm -v)"

# 安装依赖项
echo "正在安装项目依赖项..."
npm install
if [ $? -ne 0 ]; then
    echo "安装依赖项失败，退出..."
    exit 1
fi
echo "依赖项安装成功"

# 运行开发脚本
echo "正在运行开发脚本..."


# php版本检查
echo "正在检查 PHP 版本..."
php -v
if [ $? -ne 0 ]; then
    echo "PHP 未安装，退出..."
    exit 1
fi
echo "PHP 版本检查成功"


# 进入 Laravel 项目目录
cd side-api-laravel

# 安装依赖
echo "正在安装 Laravel 依赖项..."
composer install || { echo "安装 Laravel 依赖项失败，退出..."; exit 1; }
echo "Laravel 依赖项安装成功"

# 检查 public/index.php 是否存在
if [ ! -f "public/index.php" ]; then
  echo "错误：public/index.php 不存在，请检查 Laravel 项目是否完整"
  exit 1
fi


# 启动 PHP 内置服务器
echo "启动 Laravel 开发服务器：http://localhost:8000"

cd ../

 # 启动 PHP 内置服务器（后台运行）
php -S 0.0.0.0:8000 -t side-api-laravel/public &
PHP_PID=$!

# 捕获 Ctrl+C（中断）信号，确保清理后台进程
trap "echo '正在终止 PHP 服务...'; kill $PHP_PID; exit" INT

# 启动前端开发服务
npm run dev

# 前端服务停止后，终止 PHP 服务
kill $PHP_PID

if [ $? -ne 0 ]; then
    echo "运行开发脚本失败..."
    exit 1
fi