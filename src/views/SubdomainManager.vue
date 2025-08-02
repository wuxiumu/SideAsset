<template>
  <el-card>
    <!-- 主域名信息卡片 -->
    <div class="flex justify-between items-center mb-4">
      <h2>
        子域名管理 - {{ domainInfo.domain || domain }}
        <span v-if="domainInfo.registrar" style="font-size:14px; color:#666;">
          (注册商: {{ domainInfo.registrar }}, 到期: {{ domainInfo.expire_date }})
        </span>
      </h2>
      <el-button link @click="goBack" style="padding:0 8px 0 0;">
        <el-icon><ArrowLeft /></el-icon>返回
      </el-button>
      <el-button type="primary" @click="openDialog">添加子域名</el-button>
    </div>

    <!-- 子域名列表表格 -->
    <el-table :data="subdomains" border stripe>
      <el-table-column prop="subdomain" label="子域名">
        <template #default="scope">
          <el-link
              :href="getSubdomainUrl(scope.row.subdomain)"
              target="_blank"
              underline="never"
              style="font-weight:500"
          >
            {{ scope.row.subdomain }}
          </el-link>
        </template>
      </el-table-column>
      <el-table-column prop="bind_server_id" label="服务器">
        <template #default="scope">
          <template v-if="servers.length && scope.row.bind_server_id">
            <el-link
                type="primary"
                @click="goServerDetail(scope.row.bind_server_id)"
                style="margin-right:4px"
            >
              {{ servers.find(s => s.id === scope.row.bind_server_id)?.name || '--' }}
            </el-link>
            <el-link
                type="info"
                @click="goServerDetail(scope.row.bind_server_id)"
                v-if="servers.find(s => s.id === scope.row.bind_server_id)?.ip"
                style="margin-left:0"
            >
              ({{ servers.find(s => s.id === scope.row.bind_server_id)?.ip }})
            </el-link>
          </template>
          <span v-else>
      {{ scope.row.server_name || '--' }}
    </span>
        </template>
      </el-table-column>
      <el-table-column prop="published_at" label="发布时间">
        <template #default="scope">
    <span>
      {{ scope.row.published_at ? scope.row.published_at.slice(0, 19).replace('T', ' ') : '--' }}
      <span v-if="scope.row.published_at" style="margin-left:10px; color:#888; font-size:12px;">
        <ElapsedDays :from="scope.row.published_at" />
      </span>
    </span>
        </template>
      </el-table-column>
      <el-table-column prop="status" label="状态">
        <template #default="scope">
          <el-tag v-if="scope.row.status == 1" type="success">正常</el-tag>
          <el-tag v-else type="info">禁用</el-tag>
        </template>
      </el-table-column>
      <el-table-column prop="note" label="说明" />
      <el-table-column label="操作" width="100">
        <template #default="scope">
          <el-button size="small" @click="editSubdomain(scope.row)">编辑</el-button>
        </template>
      </el-table-column>
    </el-table>

    <!-- 后续可扩展分页、批量操作等（如前） -->

    <!-- 新增/编辑弹窗（可选） -->
    <el-dialog v-model="dialogVisible" :title="editRowId ? '编辑子域名' : '新增子域名'" @close="closeDialogHandler">
      <el-form :model="form" label-width="80px">
        <el-form-item label="子域名">
          <el-input v-model="form.subdomain" />
        </el-form-item>
        <el-form-item label="服务器">
          <el-select v-model="form.bind_server_id" clearable placeholder="请选择服务器">
            <el-option
                v-for="srv in servers"
                :key="srv.id"
                :label="srv.name + (srv.ip ? ' (' + srv.ip + ')' : '')"
                :value="srv.id"
            />
          </el-select>
        </el-form-item>
        <el-form-item label="说明">
          <el-input v-model="form.note" />
        </el-form-item>
        <el-form-item label="发布时间">
          <el-date-picker
              v-model="form.published_at"
              type="datetime"
              value-format="YYYY-MM-DD HH:mm:ss"
              placeholder="选择发布时间"
              style="width: 100%;"
          />
        </el-form-item>
        <el-form-item label="状态">
          <el-select v-model="form.status" placeholder="请选择状态" style="width: 100%;">
            <el-option label="正常" :value="1" />
            <el-option label="禁用" :value="0" />
          </el-select>
        </el-form-item>
      </el-form>
      <template #footer>
        <el-button @click="closeDialogHandler">取消</el-button>
        <el-button type="primary" @click="saveSubdomain">保存</el-button>
      </template>
    </el-dialog>
  </el-card>
</template>

<script setup lang="ts">
// ---- 统一风格：hooks, 接口驱动 ----
import { ref, onMounted } from 'vue'
import { useRoute } from 'vue-router'
import { useCrudApi } from '@/utils/api'
import {
  initForm, closeDialog, getList, editRow, save
} from '@/utils/curdTools'
import { ArrowLeft } from '@element-plus/icons-vue'
import ElapsedDays from '@/components/ElapsedDays.vue'
import { useRouter } from 'vue-router'
const router = useRouter()

// 路由参数
const route = useRoute()
const domainId = route.params.domainId
const domain = route.params.domain  // 路由需确保 params 有 domain 字符串

// 子域名接口实例
const API_URL = import.meta.env.VITE_APP_BASE_API + 'subdomains'
const subdomainApi = useCrudApi(API_URL)

// 域名详情（主域名信息）
const domainInfo = ref<any>({})
const domainApi = useCrudApi(import.meta.env.VITE_APP_BASE_API + 'domains')
async function getDomainInfo() {
  if (domainId) domainInfo.value = await domainApi.show(domainId)
}

// 子域名数据和弹窗等
const subdomains = ref<any[]>([])
const dialogVisible = ref(false)
const formDefaults = {
  subdomain: '',
  server_name: '',
  note: '',
  domain_id: domainId,
  published_at: '',
  status: 1
}
const form = ref(initForm(formDefaults))
const editRowId = ref<number|null>(null)

// 拉子域名接口（按 domain_id）
async function getSubdomains() {
  const res = await subdomainApi.list({ domain_id: domainId })
  subdomains.value = res.data || res.list || []
}

// 新增：服务器列表
const servers = ref<any[]>([])
const serverApi = useCrudApi(import.meta.env.VITE_APP_BASE_API + 'servers')
async function getServers() {
  // 推荐接口返回 data/list
  const res = await serverApi.list()
  servers.value = res.data || res.list || []
}

// 新增/编辑弹窗
function openDialog() {
  dialogVisible.value = true
  form.value = initForm(formDefaults)
  editRowId.value = null
}
function closeDialogHandler() {
  closeDialog(form, formDefaults, editRowId, dialogVisible)
}
function editSubdomain(row: any) {
  editRow(row, form, editRowId, dialogVisible)
}
function saveSubdomain() {
  // domain_id 必须携带
  form.value.domain_id = domainId
  save(subdomainApi, form, editRowId, closeDialogHandler, getSubdomains)
}

// 生命周期
onMounted(() => {
  getDomainInfo()
  getSubdomains()
  getServers()  // 新增

})
function goServerDetail(serverId: number|string) {
  router.push({ name: 'ServerDetail', params: { id: serverId } })
}
function goBack() {
  router.back()
}
function getSubdomainUrl(subdomain: string) {
  if (!subdomain) return '#'
  // 如果用户已经带了 http 或 https，不再重复加
  if (/^https?:\/\//.test(subdomain)) return subdomain
  return 'http://' + subdomain
}
</script>