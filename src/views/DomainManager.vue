<template>
  <el-card>
    <!-- 工具栏区 -->
    <el-form :inline="true" :model="search" class="mb-2" style="display:flex;align-items:center;gap:16px;">
      <el-form-item label="关键字">
        <el-input v-model="search.keyword" placeholder="输入域名/业务/注册商" clearable style="width: 200px;" />
      </el-form-item>
      <el-form-item label="注册商">
        <el-select v-model="search.registrar" clearable placeholder="全部注册商" style="width: 140px;">
          <el-option
              v-for="r in registrarList"
              :key="r"
              :label="r"
              :value="r"
          />
        </el-select>
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
      <el-table-column label="生命周期" min-width="170">
        <template #default="scope">
          <AssetLifeProgress
              :purchase-date="scope.row.purchase_date"
              :expire-date="scope.row.expire_date"
              :price="scope.row.price"
          />
        </template>
      </el-table-column>
      <el-table-column prop="price" label="价格(元)" min-width="100" />
      <el-table-column prop="purchase_date" label="购买日期" min-width="100"/>
      <el-table-column prop="expire_date" label="到期日期" min-width="100"/>
      <el-table-column prop="project" label="绑定业务" min-width="120"/>
      <el-table-column label="操作" width="120">
        <template #default="scope">
          <el-button size="small" @click="goToSubdomainPage(scope.row.domain, scope.row.id)">子域名</el-button>
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
        <el-form-item label="价格">
          <el-input v-model="form.price" type="number" placeholder="输入价格" />
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
import { ref, onMounted, computed } from 'vue'
import { useRouter } from 'vue-router'
import { useCrudApi } from '@/utils/api'
import { usePagination } from '@/utils/curdTools'
import dayjs from 'dayjs'
import AssetLifeProgress from '@/components/AssetLifeProgress.vue'
import {
  initForm, closeDialog, getList, editRow, save, handleSelectionChange,
  batchDelete, handleCsvUpload, exportCSV
} from '@/utils/curdTools'

const API_URL = import.meta.env.VITE_APP_BASE_API + 'domains'
const domainApi = useCrudApi(API_URL)
const router = useRouter()

// 基本表单
const formDefaults = { domain: '', registrar: '', price: null, purchase_date: '', expire_date: '', project: '' }
const form = ref(initForm(formDefaults))
const editRowId = ref<number | null>(null)
const dialogVisible = ref(false)

// 列表相关
const domains = ref<any[]>([])
const total = ref(0)
const search = ref({ keyword: '', registrar: '' })
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

const registrarList = computed(() => [...new Set(domains.value.map(d => d.registrar).filter(Boolean))])

// 页面跳转
function goToSubdomainPage(domain: string, domainId: number|string) {
  router.push({ name: 'SubdomainManager', params: { domain, domainId } })
}

onMounted(getDomains)

function openDialog() {
  dialogVisible.value = true
  form.value = initForm(formDefaults)
  editRowId.value = null
  // 关键：如果当前筛选选了注册商，自动填入
  if (search.value.registrar) {
    form.value.registrar = search.value.registrar
  }
}
function closeDialogHandler() {
  closeDialog(form, formDefaults, editRowId, dialogVisible)
}
function editDomain(row: any) {
  editRow(row, form, editRowId, dialogVisible)
}
function saveDomain() {
  const payload = {
    ...form.value,
    price: form.value.price !== '' && form.value.price != null ? Number(form.value.price) : null,
    // 日期格式转换，防止后端 SQL 报错
    purchase_date: form.value.purchase_date ? dayjs(form.value.purchase_date).format('YYYY-MM-DD') : null,
    expire_date: form.value.expire_date ? dayjs(form.value.expire_date).format('YYYY-MM-DD') : null,
  }
  save(domainApi, ref(payload), editRowId, closeDialogHandler, getDomains)
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
  search.value.registrar = ''
  page.value = 1
  getDomains()
}
</script>