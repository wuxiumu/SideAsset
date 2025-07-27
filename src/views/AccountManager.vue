<template>
  <el-card>
    <!-- 筛选区 -->
    <el-form :inline="true" :model="search" class="mb-2">
      <el-form-item label="平台">
        <el-select v-model="search.platform" clearable placeholder="全部">
          <el-option v-for="p in platformList" :key="p" :label="p" :value="p" />
        </el-select>
      </el-form-item>
      <el-form-item label="邮箱">
        <el-input v-model="search.email" placeholder="邮箱关键词" clearable />
      </el-form-item>
      <el-form-item>
        <el-button type="primary" @click="doSearch">搜索</el-button>
        <el-button @click="resetSearch">重置</el-button>
      </el-form-item>
    </el-form>

    <!-- 工具栏区：风格统一，所有管理页面体验一致 -->
    <div class="mb-2" style="display: flex; align-items: center; justify-content: space-between;">
      <div style="display: flex; gap: 8px; align-items: center;">
        <el-button type="primary" @click="openDialog">新增账号</el-button>
        <el-upload
            accept=".csv"
            :show-file-list="false"
            :before-upload="handleCsv"
        >
          <el-button size="small">导入</el-button>
        </el-upload>
        <el-button size="small" @click="exportAccounts">导出</el-button>
        <el-button type="danger" size="small" :disabled="!multipleSelection.length" @click="handleBatchDelete">批量删除</el-button>
      </div>
      <span>共 {{ total }} 条，已选 {{ multipleSelection.length }} 条</span>
    </div>

    <!-- 账号列表表格 -->
    <el-table
        :data="accounts"
        border
        stripe
        highlight-current-row
        @selection-change="val => handleSelectionChange(val, multipleSelection)"
        ref="tableRef"
    >
      <el-table-column type="selection" width="50" />
      <el-table-column prop="platform" label="平台" min-width="100" />
      <el-table-column prop="username" label="账号" min-width="100" />
      <el-table-column prop="email" label="邮箱" min-width="160" show-overflow-tooltip />
      <el-table-column prop="bind_project" label="绑定业务" min-width="120" />
      <el-table-column prop="login_type" label="登录方式" min-width="100" />
      <el-table-column prop="note" label="备注" min-width="100" show-overflow-tooltip />
      <el-table-column label="操作" width="110">
        <template #default="scope">
          <el-button size="small" @click="editAccount(scope.row)">编辑</el-button>
        </template>
      </el-table-column>
    </el-table>

    <!-- 分页区：全局 hooks 统一风格 -->
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

    <!-- 新增/编辑弹窗 -->
    <el-dialog v-model="dialogVisible" :title="editRowId ? '编辑账号' : '新增账号'" @close="closeDialogHandler">
      <el-form :model="form" label-width="80px">
        <el-form-item label="平台">
          <el-input v-model="form.platform" />
        </el-form-item>
        <el-form-item label="账号">
          <el-input v-model="form.username" />
        </el-form-item>
        <el-form-item label="邮箱">
          <el-input v-model="form.email" />
        </el-form-item>
        <el-form-item label="绑定业务">
          <el-input v-model="form.bind_project" />
        </el-form-item>
        <el-form-item label="登录方式">
          <el-input v-model="form.login_type" />
        </el-form-item>
        <el-form-item label="备注">
          <el-input v-model="form.note" type="textarea" />
        </el-form-item>
      </el-form>
      <template #footer>
        <el-button @click="closeDialogHandler">取消</el-button>
        <el-button type="primary" @click="saveAccount">保存</el-button>
      </template>
    </el-dialog>
  </el-card>
</template>

<script setup lang="ts">
// ---- 导入工具库及 hooks（全局统一）----
import {
  initForm, closeDialog, getList, editRow, save, handleSelectionChange,
  batchDelete, handleCsvUpload, exportCSV, usePagination
} from '@/utils/curdTools'
import { useCrudApi } from '@/utils/api'
import { ref, computed, onMounted } from 'vue'

// ---- API 地址与实例统一 ----
const API_URL = import.meta.env.VITE_APP_BASE_API + 'accounts'
const accountApi = useCrudApi(API_URL)

// ---- 响应式变量 ----
const accounts = ref<any[]>([])
const total = ref(0)
const multipleSelection = ref<any[]>([])
const dialogVisible = ref(false)
const tableRef = ref()
const formDefaults = { platform: '', username: '', email: '', bind_project: '', login_type: '', note: '' }
const form = ref(initForm(formDefaults))
const editRowId = ref<number|null>(null)
const search = ref({ platform: '', email: '' })

// ---- 平台列表自动生成 ----
const platformList = computed(() => [...new Set(accounts.value.map(d => d.platform))].filter(Boolean))

// ---- 分页逻辑（全局统一hooks）----
const getAccounts = () => getList(accountApi, page, pageSize, search, accounts, total)
const {
  page,
  pageSize,
  handleSizeChange,
  handleCurrentChange
} = usePagination(getAccounts, 10)

// ---- 生命周期：挂载即拉取数据 ----
onMounted(getAccounts)

// ---- 工具方法复用区 ----
// 打开弹窗
function openDialog() {
  dialogVisible.value = true
  form.value = initForm(formDefaults)
  editRowId.value = null
}
// 关闭弹窗
function closeDialogHandler() { closeDialog(form, formDefaults, editRowId, dialogVisible) }
// 编辑账号
function editAccount(row: any) { editRow(row, form, editRowId, dialogVisible) }
// 保存账号（新增/编辑）
function saveAccount() { save(accountApi, form, editRowId, closeDialogHandler, getAccounts) }
// 批量删除
function handleBatchDelete() { batchDelete(accountApi, multipleSelection, getAccounts) }
// 导入
const handleCsv = handleCsvUpload(accountApi, getAccounts)
// 导出
function exportAccounts() { exportCSV(accounts.value, 'accounts.csv') }
// 搜索
function doSearch() { page.value = 1; getAccounts() }
function resetSearch() { search.value.platform = ''; search.value.email = ''; page.value = 1; getAccounts() }

</script>