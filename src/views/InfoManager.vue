<template>
  <el-card>
    <!-- 顶部筛选区 -->
    <el-form :inline="true" :model="search" class="mb-2">
      <el-form-item label="分类">
        <el-select v-model="search.category" clearable placeholder="全部">
          <el-option v-for="c in categories" :key="c" :label="c" :value="c" />
        </el-select>
      </el-form-item>
      <el-form-item label="关键词">
        <el-input v-model="search.keyword" placeholder="输入标题/作者" clearable />
      </el-form-item>
      <el-form-item>
        <el-button type="primary" @click="doSearch">搜索</el-button>
        <el-button @click="resetSearch">重置</el-button>
      </el-form-item>
    </el-form>

    <!-- 工具栏 -->
    <div class="mb-2" style="display: flex; align-items: center; justify-content: space-between;">
      <div style="display: flex; gap: 8px; align-items: center;">
        <el-button type="primary" @click="addItem">新增</el-button>
        <el-upload
            accept=".csv"
            :show-file-list="false"
            :before-upload="handleCsvUpload"
        >
          <el-button size="small">导入</el-button>
        </el-upload>
        <el-button size="small" @click="exportCSV">导出</el-button>
        <el-button type="danger" size="small" :disabled="!multipleSelection.length" @click="batchDelete">批量删除</el-button>
      </div>
      <span>共 {{ total }} 条</span>
    </div>

    <!-- 主表格 -->
    <el-table
        :data="pageData"
        border
        stripe
        highlight-current-row
        @selection-change="handleSelectionChange"
        :row-class-name="rowClassName"
        ref="tableRef"
    >
      <el-table-column type="selection" width="50" />
      <el-table-column prop="id" label="ID" width="60" sortable />
      <el-table-column prop="category" label="分类" width="120" />
      <el-table-column prop="title" label="标题" min-width="220" show-overflow-tooltip />
      <el-table-column prop="author" label="作者" width="100" />
      <el-table-column prop="created_at" label="发布时间" width="160" sortable />
      <el-table-column label="操作" width="120">
        <template #default="scope">
          <el-button size="small" @click="edit(scope.row)">修改</el-button>
          <el-button size="small" type="danger" @click="del(scope.row)">删除</el-button>
        </template>
      </el-table-column>
    </el-table>

    <!-- 分页 -->
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
import Papa from 'papaparse'
import { ref, computed } from 'vue'

// mock data & 分类
const categories = ['AI芯片', '光模块', 'AI服务器', 'AI技术']
const allData = ref<any[]>([
  // 这里填 mock 数据或用接口获取
  { id: 21, category: 'AI芯片', title: '特斯拉大跌的真相', author: '李雷', created_at: '2025-07-25 16:00:16' },
  // ...其它
])
const total = computed(() => filteredData.value.length)
const pageSize = ref(10)
const page = ref(1)
const search = ref({ category: '', keyword: '' })

// 搜索&分页
const filteredData = computed(() => {
  let data = allData.value
  if (search.value.category) data = data.filter(d => d.category === search.value.category)
  if (search.value.keyword) data = data.filter(d =>
      d.title?.includes(search.value.keyword) || d.author?.includes(search.value.keyword)
  )
  return data
})
const pageData = computed(() =>
    filteredData.value.slice((page.value - 1) * pageSize.value, page.value * pageSize.value)
)

// 批量选择
const multipleSelection = ref<any[]>([])
const tableRef = ref()
function handleSelectionChange(val: any[]) {
  multipleSelection.value = val
}

// 工具函数
function addItem() {/*TODO*/}
function edit(row: any) {/*TODO*/}
function del(row: any) {/*TODO*/}
function batchDelete() {
  multipleSelection.value.forEach(item => {
    const idx = allData.value.findIndex(d => d.id === item.id)
    if (idx !== -1) allData.value.splice(idx, 1)
  })
  multipleSelection.value = []
}
function doSearch() { page.value = 1 }
function resetSearch() { search.value.category = ''; search.value.keyword = ''; page.value = 1 }
function rowClassName() { return '' }

// 分页
function onPageChange(val: number) { page.value = val }
function onSizeChange(val: number) { pageSize.value = val; page.value = 1 }

// 导入导出
function handleCsvUpload(file: File) {
  const reader = new FileReader()
  reader.onload = (e: any) => {
    Papa.parse(e.target.result, {
      header: true,
      complete: (result) => {
        // 自行对 row.id 等做唯一处理
        allData.value.push(...result.data)
      }
    })
  }
  reader.readAsText(file)
  return false
}
function exportCSV() {
  if (!allData.value.length) return
  const headers = Object.keys(allData.value[0])
  const rows = [headers, ...allData.value.map(d => headers.map(h => d[h] ?? ""))]
  const csv = rows.map(row => row.map(val => `"${String(val).replace(/"/g, '""')}"`).join(',')).join('\n')
  const blob = new Blob([csv], { type: 'text/csv' })
  const url = window.URL.createObjectURL(blob)
  const link = document.createElement('a')
  link.href = url
  link.download = 'info.csv'
  link.click()
  window.URL.revokeObjectURL(url)
}
</script>