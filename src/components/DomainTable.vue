<template>
  <el-card>
    <div class="flex justify-between items-center mb-4">
      <h2>域名管理</h2>
      <el-button type="primary" @click="dialogVisible = true">添加域名</el-button>
    </div>

    <el-table :data="domains" border stripe>
      <el-table-column prop="domain" label="域名" />
      <el-table-column prop="registrar" label="注册商" />
      <el-table-column prop="purchase_date" label="购买日期" />
      <el-table-column prop="expire_date" label="到期日期" />
      <el-table-column prop="project" label="绑定业务" />
    </el-table>

    <!-- 添加域名弹窗 -->
    <el-dialog v-model="dialogVisible" title="添加域名">
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
        <el-button type="primary" @click="save">保存</el-button>
      </template>
    </el-dialog>
  </el-card>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import { useDomainStore } from '@/stores/domainStore'

const store = useDomainStore()
const domains = store.domains

const dialogVisible = ref(false)
const form = ref({
  domain: '',
  registrar: '',
  purchase_date: '',
  expire_date: '',
  project: '',
})

function save() {
  store.addDomain({ ...form.value })
  dialogVisible.value = false
  form.value = { domain: '', registrar: '', purchase_date: '', expire_date: '', project: '' }
}
</script>