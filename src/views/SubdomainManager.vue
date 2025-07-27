<template>
  <el-card>
    <div class="flex justify-between items-center mb-4">
      <h2>子域名管理 - {{ props.domain }}</h2>
      <el-button type="primary">添加子域名</el-button>
    </div>

    <el-table :data="filteredSubdomains" border stripe>
      <el-table-column prop="subdomain" label="子域名" />
      <el-table-column prop="domain" label="所属主域名" />
      <el-table-column prop="server" label="服务器" />
      <el-table-column prop="note" label="说明" />
    </el-table>
  </el-card>
</template>

<script setup lang="ts">
import { ref, computed } from 'vue'

const props = defineProps<{ domain: string }>()

const subdomains = ref([
  {
    subdomain: 'api.example.com',
    domain: 'example.com',
    server: '腾讯云HK01',
    note: '博客接口服务'
  },
  {
    subdomain: 'img.ai-tools.site',
    domain: 'ai-tools.site',
    server: '阿里云轻量应用服务器',
    note: '图片 CDN 加速服务'
  }
])

const filteredSubdomains = computed(() =>
  subdomains.value.filter(item => item.domain === props.domain)
)
</script>