<script setup>
</script>

<template>
  <el-container style="min-height: 100vh;">
    <el-header>
      <el-menu mode="horizontal" router default-active="/">
        <el-menu-item index="/">首页</el-menu-item>
        <el-menu-item index="/domains">域名管理</el-menu-item>
        <el-menu-item index="/servers">服务器管理</el-menu-item>
        <el-menu-item index="/apiManager">接口服务管理</el-menu-item>
        <el-menu-item index="/accountManager">账户管理</el-menu-item>
        <el-menu-item index="/businessAnalytics">统计分析</el-menu-item>
        <el-menu-item index="/logAlertManager">日志告警管理</el-menu-item>
      </el-menu>
    </el-header>
    <el-main>
      <router-view />
    </el-main>
  </el-container>
</template>

<style scoped>
</style>
