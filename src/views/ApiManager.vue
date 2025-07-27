<template>
  <el-card>
    <!-- 工具栏区 -->
    <el-form :inline="true" :model="search" class="mb-2" style="display:flex;align-items:center;gap:16px;">
      <el-form-item label="关键字">
        <el-input v-model="search.keyword" placeholder="输入服务名/平台/业务" clearable style="width: 200px;" />
      </el-form-item>
      <el-form-item>
        <el-button type="primary" @click="doSearch">搜索</el-button>
        <el-button @click="resetSearch">重置</el-button>
      </el-form-item>
      <el-form-item style="margin-left:auto;">
        <el-button type="primary" @click="openDialog">添加接口服务</el-button>
        <el-upload
            accept=".csv"
            :show-file-list="false"
            :before-upload="handleCsv"
        >
          <el-button size="small">导入</el-button>
        </el-upload>
        <el-button size="small" @click="exportApis">导出</el-button>
        <el-button type="danger" size="small" :disabled="!multipleSelection.length" @click="handleBatchDelete">批量删除</el-button>
      </el-form-item>
      <span style="margin-left:12px;">共 {{ total }} 条，已选 {{ multipleSelection.length }} 条</span>
    </el-form>

    <!-- 表格 -->
    <el-table
        :data="apiServices"
        border
        stripe
        highlight-current-row
        @selection-change="val => handleSelectionChange(val, multipleSelection)"
        ref="tableRef"
    >
      <el-table-column type="selection" width="50" />
      <el-table-column prop="name" label="服务名称" min-width="120" />
      <el-table-column prop="platform" label="平台" min-width="100" />
      <el-table-column prop="api_key" label="Key" min-width="160" show-overflow-tooltip />
      <el-table-column prop="bind_project" label="绑定业务" min-width="120" />
      <el-table-column prop="quota" label="额度" min-width="80" />
      <el-table-column prop="fee" label="费用" min-width="80" />
      <el-table-column prop="doc_url" label="文档" min-width="100">
        <template #default="scope">
          <a :href="scope.row.doc_url" target="_blank" v-if="scope.row.doc_url">查看</a>
        </template>
      </el-table-column>
      <el-table-column prop="note" label="备注" min-width="100" show-overflow-tooltip />
      <el-table-column label="操作" width="110">
        <template #default="scope">
          <el-button size="small" @click="editApi(scope.row)">编辑</el-button>
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
        @size-change="handleSizeChange"
        @current-change="handleCurrentChange"
    />

    <!-- 新增/编辑弹窗 -->
    <el-dialog v-model="dialogVisible" :title="form.id ? '编辑接口服务' : '添加接口服务'">
      <el-form :model="form" label-width="80px">
        <el-form-item label="服务名称">
          <el-input v-model="form.name" />
        </el-form-item>
        <el-form-item label="平台">
          <el-input v-model="form.platform" />
        </el-form-item>
        <el-form-item label="Key">
          <el-input v-model="form.api_key" />
        </el-form-item>
        <el-form-item label="绑定业务">
          <el-input v-model="form.bind_project" />
        </el-form-item>
        <el-form-item label="额度">
          <el-input v-model="form.quota" />
        </el-form-item>
        <el-form-item label="费用">
          <el-input v-model="form.fee" />
        </el-form-item>
        <el-form-item label="文档">
          <el-input v-model="form.doc_url" />
        </el-form-item>
        <el-form-item label="备注">
          <el-input v-model="form.note" type="textarea" />
        </el-form-item>
      </el-form>
      <template #footer>
        <el-button @click="closeDialogHandler">取消</el-button>
        <el-button type="primary" @click="saveApi">保存</el-button>
      </template>
    </el-dialog>
  </el-card>
</template>

<script setup lang="ts">
import {
  initForm, closeDialog, getList, editRow, save, handleSelectionChange,
  batchDelete, handleCsvUpload, exportCSV, usePagination
} from '@/utils/curdTools'
import { useCrudApi } from '@/utils/api'
import { ref, computed, onMounted } from 'vue'

const API_URL = import.meta.env.VITE_APP_BASE_API + 'api-services'
const apiServiceApi = useCrudApi(API_URL)

const apiServices = ref<any[]>([])
const total = ref(0)
const multipleSelection = ref<any[]>([])
const dialogVisible = ref(false)
const tableRef = ref()
const formDefaults = { name: '', platform: '', api_key: '', bind_project: '', quota: '', fee: '', doc_url: '', note: '' }
const form = ref(initForm(formDefaults))
const editRowId = ref<number|null>(null)
const search = ref({ platform: '', keyword: '' })
const platformList = computed(() => [...new Set(apiServices.value.map(d => d.platform))].filter(Boolean))

const getApiServices = () => getList(apiServiceApi, page, pageSize, search, apiServices, total)
const {
  page,
  pageSize,
  handleSizeChange,
  handleCurrentChange
} = usePagination(getApiServices, 10)

onMounted(getApiServices)

function openDialog() {
  dialogVisible.value = true
  form.value = initForm(formDefaults)
  editRowId.value = null
}
function closeDialogHandler() { closeDialog(form, formDefaults, editRowId, dialogVisible) }
function editApi(row: any) { editRow(row, form, editRowId, dialogVisible) }
function saveApi() { save(apiServiceApi, form, editRowId, closeDialogHandler, getApiServices) }
function handleBatchDelete() { batchDelete(apiServiceApi, multipleSelection, getApiServices) }
const handleCsv = handleCsvUpload(apiServiceApi, getApiServices)
function exportApis() { exportCSV(apiServices.value, 'apis.csv') }
function doSearch() { page.value = 1; getApiServices() }
function resetSearch() { search.value.platform = ''; search.value.keyword = ''; page.value = 1; getApiServices() }

function del(row: any) {
  apiServiceApi.delete(row.id).then(() => {
    getApiServices()
  })
}
</script>