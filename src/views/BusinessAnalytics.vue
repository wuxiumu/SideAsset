<template>
  <el-card>
    <el-tabs v-model="activeTab">
      <el-tab-pane label="项目总览" name="overview">
        <el-table :data="projectList" border stripe>
          <el-table-column prop="name" label="项目" />
          <el-table-column prop="deploy_url" label="部署地址">
            <template #default="scope">
              <a :href="scope.row.deploy_url" target="_blank">{{ scope.row.deploy_url }}</a>
            </template>
          </el-table-column>
          <el-table-column prop="data_source" label="数据源" />
          <el-table-column prop="monetize_type" label="变现方式" />
          <el-table-column prop="status" label="状态" />
          <el-table-column prop="note" label="备注" />
        </el-table>
      </el-tab-pane>
      <el-tab-pane label="变现数据" name="monetize">
        <!-- 简易统计区，可扩展为 ECharts 折线图 -->
        <el-row :gutter="20">
          <el-col :span="16">
            <el-card>
              <h4>收入趋势（7日）</h4>
              <div id="chart" style="height:240px"></div>
            </el-card>
          </el-col>
          <el-col :span="8">
            <el-card>
              <h4>昨日收入</h4>
              <div>广告：￥{{ statYesterday.ad_income }}</div>
              <div>支付：￥{{ statYesterday.pay_income }}</div>
              <div>PV：{{ statYesterday.pv }}</div>
              <div>UV：{{ statYesterday.uv }}</div>
            </el-card>
          </el-col>
        </el-row>
        <el-table :data="statList" border stripe class="mt-4">
          <el-table-column prop="date" label="日期" />
          <el-table-column prop="project" label="项目" />
          <el-table-column prop="ad_income" label="广告收入" />
          <el-table-column prop="pay_income" label="支付收入" />
          <el-table-column prop="uv" label="独立访客" />
          <el-table-column prop="pv" label="浏览量" />
          <el-table-column prop="retention" label="留存" />
        </el-table>
      </el-tab-pane>
    </el-tabs>
  </el-card>
</template>

<script setup lang="ts">
// import { onMounted } from 'vue'
import { ref } from 'vue'

const activeTab = ref('overview')
// 假数据
const projectList = ref([
  { name: 'AI资讯导航', deploy_url: 'https://ai-news.site', data_source: 'Markdown', monetize_type: '广告', status: '运行中', note: '主流AI新闻聚合' }
])
const statList = ref([
  { date: '2025-07-25', project: 'AI资讯导航', ad_income: 28.3, pay_income: 6, uv: 111, pv: 876, retention: '20.5%' },
  // 更多数据
])
const statYesterday = { ad_income: 30.4, pay_income: 11, pv: 953, uv: 156 }
</script>