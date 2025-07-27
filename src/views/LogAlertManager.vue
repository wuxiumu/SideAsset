<template>
  <el-card>
    <el-form :inline="true" :model="search" class="mb-2">
      <el-form-item label="类型">
        <el-select v-model="search.type" clearable placeholder="全部">
          <el-option v-for="t in typeList" :key="t" :label="t" :value="t" />
        </el-select>
      </el-form-item>
      <el-form-item label="级别">
        <el-select v-model="search.level" clearable placeholder="全部">
          <el-option v-for="l in levelList" :key="l" :label="l" :value="l" />
        </el-select>
      </el-form-item>
      <el-form-item label="状态">
        <el-select v-model="search.status" clearable placeholder="全部">
          <el-option label="未读" value="未读" />
          <el-option label="已读" value="已读" />
          <el-option label="已处理" value="已处理" />
        </el-select>
      </el-form-item>
      <el-form-item label="关键词">
        <el-input v-model="search.keyword" placeholder="内容/对象" clearable />
      </el-form-item>
      <el-form-item>
        <el-button type="primary" @click="doSearch">搜索</el-button>
        <el-button @click="resetSearch">重置</el-button>
      </el-form-item>
    </el-form>
    <div class="mb-2" style="display:flex; align-items:center; gap:10px;">
      <el-button type="success" size="small" @click="batchMarkRead" :disabled="!multipleSelection.length">标记已读</el-button>
<el-button size="small" @click="exportLogs">导出日志</el-button>
      <span>共 {{ total }} 条，已选 {{ multipleSelection.length }} 条</span>
    </div>
    <el-table
        :data="pageData"
        border
        stripe
        highlight-current-row
        @selection-change="handleSelectionChange"
        ref="tableRef"
    >
      <el-table-column type="selection" width="50" />
      <el-table-column prop="time" label="时间" min-width="150" />
      <el-table-column prop="type" label="类型" min-width="100" />
      <el-table-column prop="level" label="级别" min-width="80" />
      <el-table-column prop="content" label="内容" min-width="220" show-overflow-tooltip />
      <el-table-column prop="object" label="关联对象" min-width="120" />
      <el-table-column prop="status" label="状态" min-width="80" />
      <el-table-column label="操作" width="110">
        <template #default="scope">
          <el-button v-if="scope.row.status!=='已处理'" size="small" @click="markProcessed(scope.row)">设为已处理</el-button>
          <el-button size="small" @click="gotoObject(scope.row)">详情</el-button>
        </template>
      </el-table-column>
    </el-table>
    <el-pagination
        class="mt-2"
        background
        layout="total, sizes, prev, pager, next, jumper"
        :total="total"
        :page-size="pageSize"
        :current-page="page"
        @size-change="onSizeChange"
        @current-change="onPageChange"
    />
  </el-card>
</template>

<script setup lang="ts">
import { ref, computed } from 'vue'
import { exportCSV } from '@/utils/curdTools'

// mock数据
const allData = ref<any[]>([
  { id:1, time:'2025-07-27 08:32', type:'域名', level:'警告', content:'example.com 3天后到期', object:'example.com', status:'未读' },
  { id:2, time:'2025-07-27 08:00', type:'接口', level:'信息', content:'openai.com key已更换', object:'OpenAI', status:'已读' },
  { id:3, time:'2025-07-26 19:44', type:'服务器', level:'错误', content:'ECS-aliyun 失联超1小时', object:'ECS-aliyun', status:'未读' },
  { id:4, time:'2025-07-25 20:30', type:'自定义', level:'严重', content:'业务收入低于阈值', object:'AI副业', status:'已处理' }
])
const tableRef = ref()
const multipleSelection = ref<any[]>([])
const search = ref({ type:'', level:'', status:'', keyword:'' })
const typeList = ['域名','接口','服务器','自定义']
const levelList = ['信息','警告','错误','严重']

const filteredData = computed(() => {
  let data = allData.value
  if (search.value.type) data = data.filter(d => d.type === search.value.type)
  if (search.value.level) data = data.filter(d => d.level === search.value.level)
  if (search.value.status) data = data.filter(d => d.status === search.value.status)
  if (search.value.keyword) data = data.filter(d =>
      d.content?.includes(search.value.keyword) || d.object?.includes(search.value.keyword)
  )
  return data
})
const total = computed(() => filteredData.value.length)
const page = ref(1)
const pageSize = ref(10)
const pageData = computed(() =>
    filteredData.value.slice((page.value-1)*pageSize.value, page.value*pageSize.value)
)
function onPageChange(val:number){ page.value=val }
function onSizeChange(val:number){ pageSize.value=val; page.value=1 }
function doSearch(){ page.value=1 }
function resetSearch(){ search.value={ type:'', level:'', status:'', keyword:'' }; page.value=1 }
function handleSelectionChange(val:any[]){ multipleSelection.value=val }
function batchMarkRead() {
  // 标记选中项为已读，健壮处理
  if (!Array.isArray(multipleSelection.value) || !Array.isArray(allData.value)) {
    multipleSelection.value = []
    return
  }
  multipleSelection.value.forEach(item => {
    if (!item || typeof item.id === 'undefined') return
    const idx = allData.value.findIndex(d => d.id === item.id)
    if (idx !== -1) allData.value[idx].status = '已读'
  })
  multipleSelection.value = []
}
function markProcessed(row: any) {
  const idx = allData.value.findIndex(d => d.id === row.id)
  if (idx !== -1) allData.value[idx].status = '已处理'
}

function gotoObject(row: any) {
  // 跳转详情页，实际项目可用 router
  // 这里简单弹窗，实际可替换为 router.push 或 emit
  alert(`跳转到 ${row.object} 详情页`)
}

function exportLogs() {
  exportCSV(allData.value, 'logs.csv')
}

</script>