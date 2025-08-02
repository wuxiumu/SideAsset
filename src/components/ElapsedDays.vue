<template>
  <span v-if="days !== null">{{ days }} 天</span>
  <span v-else>--</span>
</template>
<script setup lang="ts">
import { computed } from 'vue'
import dayjs from 'dayjs'

const props = defineProps<{
  from: string | null | undefined
}>()

const days = computed(() => {
  if (!props.from) return null
  const start = dayjs(props.from)
  if (!start.isValid()) return null
  const now = dayjs()
  const diff = now.diff(start, 'day')
  return diff >= 0 ? diff : null
})
</script>