<template>
  <el-card>
    <!-- 工具栏+搜索区 -->
    <el-form :inline="true" :model="search" class="mb-2" style="display:flex;align-items:center;gap:16px;">
      <el-form-item label="关键字">
        <el-input v-model="search.keyword" placeholder="输入名称/IP/用途/项目" clearable style="width: 200px;" />
      </el-form-item>
      <el-form-item>
        <el-button type="primary" @click="doSearch">搜索</el-button>
        <el-button @click="resetSearch">重置</el-button>
      </el-form-item>
      <el-form-item style="margin-left:auto;">
        <el-button type="primary" @click="openDialog()">添加服务器</el-button>
        <el-upload
            accept=".csv"
            :show-file-list="false"
            :before-upload="handleCsv"
        >
          <el-button size="small">导入</el-button>
        </el-upload>
        <el-button size="small" @click="exportServers">导出</el-button>
        <el-button type="danger" size="small" :disabled="!multipleSelection.length" @click="handleBatchDelete">批量删除</el-button>
      </el-form-item>
      <span style="margin-left:12px;">共 {{ total }} 条，已选 {{ multipleSelection.length }} 条</span>
    </el-form>
    <el-alert type="info" :closable="false" title="TODO: 跨页选择、批量导入/导出格式优化（后期开发）" class="my-2" />
    <!-- 服务器列表表格 -->
    <el-table
        :data="servers"
        border
        stripe
        highlight-current-row
        @selection-change="val => handleSelectionChange(val, multipleSelection)"
        ref="tableRef"
    >
      <el-table-column type="selection" width="50" />
      <el-table-column prop="name" label="名称">
        <template #default="scope">
          <el-link type="primary" @click="goDetail(scope.row.id)">
            {{ scope.row.name }}
          </el-link>
        </template>
      </el-table-column>
      <el-table-column prop="type" label="主机类型" />
      <el-table-column prop="ip" label="IP地址" />
      <el-table-column prop="location" label="地区" />
      <el-table-column prop="os" label="系统" />
      <el-table-column prop="purpose" label="用途" />
      <el-table-column prop="project" label="运行项目" />
      <el-table-column prop="register_date" label="注册时间" min-width="100" />
      <el-table-column prop="expire_date" label="到期时间" min-width="100" />
      <el-table-column label="生命周期/价格" min-width="200">
        <template #default="scope">
          <AssetLifeProgress
              :purchase-date="scope.row.register_date"
              :expire-date="scope.row.expire_date"
              :price="scope.row.price"
          />
        </template>
      </el-table-column>
      <el-table-column label="操作" width="120">
        <template #default="scope">
          <el-button size="small" @click="editServer(scope.row)">编辑</el-button>
        </template>
      </el-table-column>
    </el-table>
    <!-- 分页组件 -->
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
    <!-- 新增/编辑服务器弹窗 -->
    <el-dialog v-model="dialogVisible" :title="editRowId ? '编辑服务器' : '添加服务器'" @close="closeDialogHandler">
      <el-form :model="form" label-width="80px">
        <el-table-column prop="name" label="名称">
          <template #default="scope">
            <el-link type="primary" @click="goDetail(scope.row.id)">
              {{ scope.row.name }}
            </el-link>
          </template>
        </el-table-column>
        <el-form-item label="类型">
          <el-input v-model="form.type" placeholder="ECS/轻量/虚拟主机..." />
        </el-form-item>
        <el-form-item label="IP">
          <el-input v-model="form.ip" />
        </el-form-item>
        <el-form-item label="地区">
          <el-input v-model="form.location" />
        </el-form-item>
        <el-form-item label="系统">
          <el-input v-model="form.os" />
        </el-form-item>
        <el-form-item label="用途">
          <el-input v-model="form.purpose" />
        </el-form-item>
        <el-form-item label="项目">
          <el-input v-model="form.project" />
        </el-form-item>
        <el-form-item label="注册时间">
          <el-date-picker
              v-model="form.register_date"
              type="date"
              placeholder="选择注册时间"
              style="width: 100%;"
              value-format="YYYY-MM-DD"
          />
        </el-form-item>
        <el-form-item label="到期时间">
          <el-date-picker
              v-model="form.expire_date"
              type="date"
              placeholder="选择到期时间"
              style="width: 100%;"
              value-format="YYYY-MM-DD"
          />
        </el-form-item>
        <el-form-item label="价格">
          <el-input v-model="form.price" type="number" placeholder="元" />
        </el-form-item>
        <el-form-item label="扩展信息">
          <div style="width:100%">
            <div v-for="(item, idx) in form.extra" :key="idx" style="display:flex; gap:8px; margin-bottom:8px;">
              <el-input v-model="item.name" placeholder="名称/账号类型" style="width: 110px" />
              <el-input v-model="item.desc" placeholder="描述/账号/密钥等" style="flex:1" />
              <el-button
                  v-if="form.extra.length > 1"
                  icon="el-icon-delete"
                  type="danger"
                  size="small"
                  @click="form.extra.splice(idx,1)"
              >删</el-button>
            </div>
            <el-button
                type="primary"
                size="small"
                plain
                icon="el-icon-plus"
                @click="form.extra.push({ name: '', desc: '' })"
            >新增一项</el-button>
          </div>
        </el-form-item>
      </el-form>
      <template #footer>
        <el-button @click="closeDialogHandler">取消</el-button>
        <el-button type="primary" @click="saveServer">保存</el-button>
      </template>
    </el-dialog>
  </el-card>
</template>

<script setup lang="ts">
// ---- 核心依赖 ----
import { ref, onMounted } from 'vue'
import {
  initForm, closeDialog, getList, editRow, save, handleSelectionChange,
  batchDelete, handleCsvUpload, exportCSV, usePagination
} from '@/utils/curdTools'
import { useCrudApi } from '@/utils/api'
import { useRouter } from 'vue-router'
import AssetLifeProgress from '@/components/AssetLifeProgress.vue'

// ---- API 配置及路由 ----
const API_URL = import.meta.env.VITE_APP_BASE_API + 'servers'
const serverApi = useCrudApi(API_URL)
const router = useRouter()

// ---- 基础数据区 ----
const servers = ref<any[]>([])                  // 列表数据
const total = ref(0)                            // 总数
const multipleSelection = ref<any[]>([])        // 多选
const dialogVisible = ref(false)                // 弹窗控制
const tableRef = ref()                          // 表格 ref
const formDefaults = {
  name: '', type: '', ip: '', location: '', os: '', purpose: '', project: '',
  register_date: '', expire_date: '', price: '',
  extra: [{ name: '', desc: '' }]   // extra 变成数组（至少1组）
}
const form = ref(initForm(formDefaults))        // 表单数据
const editRowId = ref<number|null>(null)        // 编辑行 ID，null 新增
const search = ref({ keyword: '' })             // 搜索关键字

// ---- 分页逻辑（hooks公用，所有页面风格统一） ----
const getServers = () => getList(serverApi, page, pageSize, search, servers, total)
const {
  page,
  pageSize,
  handleSizeChange,
  handleCurrentChange
} = usePagination(getServers, 20)

onMounted(getServers)

// ---- 业务方法 ----
// 打开弹窗（新增）
function openDialog() {
  dialogVisible.value = true
  form.value = initForm(formDefaults)
  editRowId.value = null
}
// 关闭弹窗（无论新增/编辑都重置）
function closeDialogHandler() { closeDialog(form, formDefaults, editRowId, dialogVisible) }
// 编辑服务器
function editServer(row: any) {
  editRow(row, form, editRowId, dialogVisible)
  // 兼容老数据，extra为字符串时转数组
  if (typeof form.value.extra === 'string') {
    try {
      form.value.extra = JSON.parse(form.value.extra)
      if (!Array.isArray(form.value.extra)) form.value.extra = [{ name: '', desc: '' }]
    } catch {
      form.value.extra = [{ name: '', desc: '' }]
    }
  }
  if (!Array.isArray(form.value.extra) || form.value.extra.length === 0) {
    form.value.extra = [{ name: '', desc: '' }]
  }
}
// 保存（新增/编辑）
function saveServer() {
  // 转 json（后端要求 array/object 则用 JSON.parse，否则直接字符串也可）
  if (form.value.extra) {
    try {
      form.value.extra = JSON.parse(form.value.extra)
    } catch(e) {
      // 不是合法 json 可提醒
    }
  }
  save(serverApi, form, editRowId, closeDialogHandler, getServers)
}
// 批量删除
function handleBatchDelete() { batchDelete(serverApi, multipleSelection, getServers) }
// 批量导入
const handleCsv = handleCsvUpload(serverApi, getServers)
// 导出
function exportServers() { exportCSV(servers.value, 'servers.csv') }

// 搜索
function doSearch() {
  page.value = 1
  getServers()
}
// 重置搜索
function resetSearch() {
  search.value.keyword = ''
  page.value = 1
  getServers()
}
function goDetail(id: number|string) {
  router.push({ name: 'ServerDetail', params: { id } })
}
</script>