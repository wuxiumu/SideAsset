<template>
  <el-card>
    <!-- 工具栏区 -->
    <el-form :inline="true" :model="search" class="mb-2" style="display:flex;align-items:center;gap:16px;">
      <el-form-item label="关键字">
        <el-input v-model="search.keyword" placeholder="输入域名/业务/注册商" clearable style="width: 200px;" />
      </el-form-item>
      <el-form-item>
        <el-button type="primary" @click="doSearch">搜索</el-button>
        <el-button @click="resetSearch">重置</el-button>
      </el-form-item>
      <el-form-item style="margin-left:auto;">
        <el-button type="primary" @click="openDialog">添加域名</el-button>
        <el-upload
          accept=".csv"
          :show-file-list="false"
          :before-upload="handleCsv"
        >
          <el-button size="small">导入</el-button>
        </el-upload>
        <el-button size="small" @click="exportDomains">导出</el-button>
        <el-button type="danger" size="small" :disabled="!multipleSelection.length" @click="handleBatchDelete">批量删除</el-button>
      </el-form-item>
      <span style="margin-left:12px;">共 {{ total }} 条，已选 {{ multipleSelection.length }} 条</span>
    </el-form>
    <el-alert type="info" :closable="false" title="TODO: 快到期高亮 跨页选择或批量导入/导出格式优化（后期开发，开发完删除本提示）" class="my-2" />
    <el-table
      :data="domains"
      border
      stripe
      highlight-current-row
      @selection-change="val => handleSelectionChange(val, multipleSelection)"
      ref="tableRef"
    >
      <el-table-column type="selection" width="50" />
      <el-table-column prop="domain" label="域名" min-width="120"/>
      <el-table-column prop="registrar" label="注册商" min-width="100"/>
      <el-table-column prop="purchase_date" label="购买日期" min-width="100"/>
      <el-table-column prop="expire_date" label="到期日期" min-width="100"/>
      <el-table-column prop="project" label="绑定业务" min-width="120"/>
      <el-table-column label="操作" width="120">
        <template #default="scope">
          <el-button size="small" @click="goToSubdomainPage(scope.row.domain)">子域名</el-button>
          <el-button size="small" @click="editDomain(scope.row)">编辑</el-button>
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
      @size-change="handleSizeChange"
      @current-change="handleCurrentChange"
    />
    <!-- 添加域名弹窗 -->
    <el-dialog v-model="dialogVisible" :title="editRowId ? '编辑域名' : '添加域名'" @close="closeDialogHandler">
      <el-form :model="form" label-width="80px">
        <el-form-item label="域名">
          <el-input v-model="form.domain" />
        </el-form-item>
        <el-form-item label="注册商">
          <el-input v-model="form.registrar" />
        </el-form-item>
        <el-form-item label="购买时间">
          <el-date-picker v-model="form.purchase_date" type="date" />
        </el-form-item>
        <el-form-item label="到期时间">
          <el-date-picker v-model="form.expire_date" type="date" />
        </el-form-item>
        <el-form-item label="业务">
          <el-input v-model="form.project" />
        </el-form-item>
      </el-form>
      <template #footer>
        <el-button @click="dialogVisible = false">取消</el-button>
        <el-button type="primary" @click="saveDomain">保存</el-button>
      </template>
    </el-dialog>
  </el-card>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { useCrudApi } from '@/utils/api'
import { usePagination } from '@/utils/curdTools'
import {
  initForm, closeDialog, getList, editRow, save, handleSelectionChange,
  batchDelete, handleCsvUpload, exportCSV
} from '@/utils/curdTools'

const API_URL = import.meta.env.VITE_APP_BASE_API + 'domains'
const domainApi = useCrudApi(API_URL)
const router = useRouter()

// 基本表单
const formDefaults = { domain: '', registrar: '', purchase_date: '', expire_date: '', project: '' }
const form = ref(initForm(formDefaults))
const editRowId = ref<number | null>(null)
const dialogVisible = ref(false)

// 列表相关
const domains = ref<any[]>([])
const total = ref(0)
const search = ref({ keyword: '' })
const multipleSelection = ref<any[]>([])
const tableRef = ref()

// 分页 hooks
const getDomains = () => getList(domainApi, page, pageSize, search, domains, total)
const {
  page,
  pageSize,
  handleSizeChange,
  handleCurrentChange
} = usePagination(getDomains, 20)

// 页面跳转
function goToSubdomainPage(domain: string) {
  router.push({ name: 'SubdomainManager', params: { domain } })
}

onMounted(getDomains)

function openDialog() {
  dialogVisible.value = true
  form.value = initForm(formDefaults)
  editRowId.value = null
}
function closeDialogHandler() {
  closeDialog(form, formDefaults, editRowId, dialogVisible)
}
function editDomain(row: any) {
  editRow(row, form, editRowId, dialogVisible)
}
function saveDomain() {
  save(domainApi, form, editRowId, closeDialogHandler, getDomains)
}
function handleBatchDelete() {
  batchDelete(domainApi, multipleSelection, getDomains)
}
const handleCsv = handleCsvUpload(domainApi, getDomains)
function exportDomains() {
  exportCSV(domains.value, 'domains.csv')
}
function doSearch() {
  page.value = 1
  getDomains()
}
function resetSearch() {
  search.value.keyword = ''
  page.value = 1
  getDomains()
}
</script>