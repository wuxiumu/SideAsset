// src/utils/curdTools.ts
import { ref } from 'vue'

import Papa from 'papaparse'

export function initForm(defaults: any) {
    return { ...defaults }
}

export function closeDialog(form: any, defaults: any, editRowId: any, dialogVisible: any) {
    dialogVisible.value = false
    Object.assign(form.value, defaults)
    editRowId.value = null
}

export async function getList(api: any, page: any, pageSize: any, search: any, data: any, total: any) {
    const res = await api.list({
        page: page.value,
        per_page: pageSize.value,
        keyword: search.value.keyword || undefined
    })
    data.value = res.data
    total.value = res.total
}

export function editRow(row: any, form: any, editRowId: any, dialogVisible: any) {
    form.value = { ...row }
    editRowId.value = row.id
    dialogVisible.value = true
}

export async function save(api: any, form: any, editRowId: any, closeDialogFn: any, getListFn: any) {
    const data = { ...form.value }
    if (editRowId.value) {
        await api.update(editRowId.value, data)
    } else {
        await api.create(data)
    }
    closeDialogFn()
    getListFn()
}

export function handleSelectionChange(val: any[], multipleSelection: any) {
    multipleSelection.value = val
}

export async function batchDelete(api: any, multipleSelection: any, getListFn: any) {
    if (!multipleSelection.value.length) return
    const ids = multipleSelection.value.map(item => item.id)
    await api.batchRemove(ids)
    multipleSelection.value = []
    getListFn()
}

export function handleCsvUpload(api: any, getListFn: any) {
    return function (file: File) {
        const reader = new FileReader()
        reader.onload = async (e: any) => {
            Papa.parse(e.target.result, {
                header: true,
                complete: async (result) => {
                    for (const d of result.data) {
                        if (d.name) {
                            await api.create(d)
                        }
                    }
                    getListFn()
                }
            })
        }
        reader.readAsText(file)
        return false
    }
}

export function exportCSV(data: any[], filename: string) {
    if (!data.length) return
    const headers = Object.keys(data[0])
    const rows = [headers, ...data.map(d => headers.map(h => d[h] ?? ""))]
    const csv = rows.map(row => row.map(val => `"${String(val).replace(/"/g, '""')}"`).join(',')).join('\n')
    const blob = new Blob([csv], { type: 'text/csv' })
    const url = window.URL.createObjectURL(blob)
    const link = document.createElement('a')
    link.href = url
    link.download = filename
    link.click()
    window.URL.revokeObjectURL(url)
}

// 通用分页响应处理（适配 Laravel/EasyAdmin/Vue等标准接口结构）
export function handlePageResponse(res: any, data: any, total: any) {
    data.value = res.data || res.list || res.items || []
    total.value = res.total || res.count || 0
}

// 分页回调（自动刷新列表）
export function onPaginationChange({ page, pageSize, getListFn }: {
    page: any, pageSize: any, getListFn: Function
}) {
    return {
        handleSizeChange: (val: number) => {
            pageSize.value = val
            page.value = 1
            getListFn()
        },
        handleCurrentChange: (val: number) => {
            page.value = val
            getListFn()
        }
    }
}

export function usePagination(getListFn: Function, initSize = 20) {
    const page = ref(1)
    const pageSize = ref(initSize)
    return {
        page,
        pageSize,
        handleSizeChange: (val: number) => {
            pageSize.value = val
            page.value = 1
            getListFn()
        },
        handleCurrentChange: (val: number) => {
            page.value = val
            getListFn()
        }
    }
}