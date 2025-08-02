<template>
  <el-tooltip :content="tooltip" placement="top">
    <div class="life-progress-bar" style="display: flex; align-items: center;">
      <el-progress
          :percentage="percentage"
          :status="status"
          :stroke-width="strokeWidth"
          :color="color"
          :show-text="false"
          style="width: 120px"
      />
      <span v-if="showRemain" :style="{ color: statusColor, fontSize: '12px', marginLeft: '8px' }">
      {{ remainDays }}天
    </span>
      <span v-if="price !== undefined && price !== null" style="font-size: 12px; color: #888; margin-left: 8px;">
      剩余￥{{ remainValue }}
    </span>

    </div>
  </el-tooltip>
</template>

<script setup lang="ts">
import { computed } from 'vue'
import dayjs from 'dayjs'

// props
const props = defineProps<{
  purchaseDate: string | null,
  expireDate: string | null,
  price?: number | string,
  showRemain?: boolean,
  strokeWidth?: number
}>()
const now = dayjs()

const start = computed(() => props.purchaseDate ? dayjs(props.purchaseDate) : null)
const end = computed(() => props.expireDate ? dayjs(props.expireDate) : null)

const totalDays = computed(() =>
    start.value && end.value && end.value.isValid() && start.value.isValid()
        ? end.value.diff(start.value, 'day')
        : 0
)
const usedDays = computed(() =>
    start.value && start.value.isValid() ? now.diff(start.value, 'day') : 0
)
const remainDays = computed(() =>
    end.value && end.value.isValid() ? end.value.diff(now, 'day') : '--'
)
const percentage = computed(() =>
    totalDays.value > 0
        ? Math.min(Math.max((usedDays.value / totalDays.value) * 100, 0), 100)
        : 100
)

const status = computed(() => {
  if (remainDays.value === '--' || remainDays.value > 30) return 'success'
  if (remainDays.value > 0) return 'warning'
  return 'exception'
})

const color = computed(() => {
  if (status.value === 'exception') return '#f56c6c'
  if (status.value === 'warning') return '#e6a23c'
  return '#67c23a'
})

const statusColor = computed(() => {
  if (status.value === 'exception') return '#f56c6c'
  if (status.value === 'warning') return '#e6a23c'
  return '#333'
})
// 剩余金钱价值
const remainValue = computed(() => {
  if (
      props.price !== undefined &&
      props.price !== null &&
      !isNaN(Number(props.price)) &&
      totalDays.value > 0 &&
      remainDays.value !== '--' &&
      remainDays.value > 0
  ) {
    return ((remainDays.value / totalDays.value) * Number(props.price)).toFixed(2)
  }
  return '--'
})

// tooltip 加上剩余价值
const tooltip = computed(() =>
    `购买: ${props.purchaseDate || '--'}，到期: ${props.expireDate || '--'}，剩余: ${remainDays.value}天` +
    (props.price !== undefined && props.price !== null && remainValue.value !== '--'
        ? `，剩余价值: ￥${remainValue.value}`
        : '')
)
</script>