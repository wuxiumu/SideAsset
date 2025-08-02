<template>
  <el-card v-if="loading">加载中...</el-card>
  <el-card v-else>
    <div class="mb-4">
      <h2>
        <el-button type="text" @click="goBack" style="padding:0 8px 0 0;">
          <el-icon><ArrowLeft /></el-icon>返回
        </el-button>
        服务器详情：{{ server.name }}
      </h2>
      <el-descriptions :column="2" border>
        <el-descriptions-item label="主机类型">{{ server.type }}</el-descriptions-item>
        <el-descriptions-item label="IP">{{ server.ip }}</el-descriptions-item>
        <el-descriptions-item label="地区">{{ server.location }}</el-descriptions-item>
        <el-descriptions-item label="操作系统">{{ server.os }}</el-descriptions-item>
        <el-descriptions-item label="用途">{{ server.purpose }}</el-descriptions-item>
        <el-descriptions-item label="项目">{{ server.project }}</el-descriptions-item>
        <el-descriptions-item label="注册时间">{{ server.register_date }}</el-descriptions-item>
        <el-descriptions-item label="到期时间">{{ server.expire_date }}</el-descriptions-item>
        <el-descriptions-item label="价格(元)">{{ server.price }}</el-descriptions-item>
        <el-descriptions-item label="扩展信息">
          <div v-if="Array.isArray(server.extra)">
            <div v-for="ex in server.extra" :key="ex.name">
              <b>{{ ex.name }}</b>: {{ ex.desc }}
            </div>
          </div>
          <span v-else>--</span>
        </el-descriptions-item>
      </el-descriptions>
      <div class="mt-2">
        <el-button type="primary" @click="editMode = true">编辑</el-button>
        <el-button @click="goBack" style="margin-left: 12px;">返回</el-button>
      </div>
    </div>
    <el-form v-if="editMode" :model="editForm" label-width="80px">
      <el-form-item label="名称">
        <el-input v-model="editForm.name" />
      </el-form-item>
      <el-form-item label="主机类型">
        <el-input v-model="editForm.type" />
      </el-form-item>
      <el-form-item label="IP">
        <el-input v-model="editForm.ip" />
      </el-form-item>
      <el-form-item label="地区">
        <el-input v-model="editForm.location" />
      </el-form-item>
      <el-form-item label="操作系统">
        <el-input v-model="editForm.os" />
      </el-form-item>
      <el-form-item label="用途">
        <el-input v-model="editForm.purpose" />
      </el-form-item>
      <el-form-item label="项目">
        <el-input v-model="editForm.project" />
      </el-form-item>
      <el-form-item label="注册时间">
        <el-date-picker v-model="editForm.register_date" type="date" value-format="YYYY-MM-DD" placeholder="选择注册时间" style="width:100%;" />
      </el-form-item>
      <el-form-item label="到期时间">
        <el-date-picker v-model="editForm.expire_date" type="date" value-format="YYYY-MM-DD" placeholder="选择到期时间" style="width:100%;" />
      </el-form-item>
      <el-form-item label="价格">
        <el-input v-model="editForm.price" type="number" placeholder="元" />
      </el-form-item>
      <el-form-item label="扩展信息">
        <div style="width:100%">
          <div v-for="(item, idx) in editForm.extra" :key="idx" style="display:flex; gap:8px; margin-bottom:8px;">
            <el-input v-model="item.name" placeholder="名称/账号类型" style="width: 110px" />
            <el-input v-model="item.desc" placeholder="描述/账号/密钥等" style="flex:1" />
            <el-button v-if="editForm.extra.length > 1" icon="el-icon-delete" type="danger" size="small" @click="editForm.extra.splice(idx,1)">删</el-button>
          </div>
          <el-button type="primary" size="small" plain icon="el-icon-plus" @click="editForm.extra.push({ name: '', desc: '' })">
            新增一项
          </el-button>
        </div>
      </el-form-item>
      <el-form-item>
        <el-button @click="editMode = false">取消</el-button>
        <el-button type="primary" @click="saveEdit">保存</el-button>
      </el-form-item>
    </el-form>
  </el-card>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useCrudApi } from '@/utils/api'
import { ArrowLeft } from '@element-plus/icons-vue'

const route = useRoute()
const router = useRouter()
const serverApi = useCrudApi(import.meta.env.VITE_APP_BASE_API + 'servers')
const server = ref<any>({})
const loading = ref(true)
const editMode = ref(false)
const editForm = ref<any>({})

// 加载详情
async function loadDetail() {
  loading.value = true
  const res = await serverApi.show(route.params.id)
  server.value = res
  // 兼容扩展信息为字符串/空/null的情况
  if (!Array.isArray(res.extra)) {
    try {
      editForm.value.extra = res.extra ? JSON.parse(res.extra) : [{ name: '', desc: '' }]
    } catch {
      editForm.value.extra = [{ name: '', desc: '' }]
    }
  } else {
    editForm.value.extra = res.extra
  }
  // 其它字段
  editForm.value = { ...res, extra: editForm.value.extra }
  loading.value = false
}

function saveEdit() {
  // 这里假定后端直接接受 array，如果后端只收字符串可以 JSON.stringify
  serverApi.update(route.params.id, editForm.value).then(() => {
    loadDetail()
    editMode.value = false
  })
}

function goBack() {
  router.back()
}

onMounted(loadDetail)
</script>