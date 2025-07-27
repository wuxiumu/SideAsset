import axios from 'axios'

export function useCrudApi(base: string) {
    return {
        // 分页列表，支持 params 可扩展
        list: (params?: any) =>
            axios.get(`${base}`, { params }).then(res => res.data),
        // 详情
        detail: (id: string | number) =>
            axios.get(`${base}/${id}`).then(res => res.data),
        // 新增
        create: (data: any) =>
            axios.post(`${base}`, data).then(res => res.data),
        // 修改
        update: (id: string | number, data: any) =>
            axios.put(`${base}/${id}`, data).then(res => res.data),
        // 删除
        remove: (id: string | number) =>
            axios.delete(`${base}/${id}`).then(res => res.data),
        // 批量删除（批量 API 路径格式需与后端统一）
        batchRemove: (ids: any[]) =>
            axios.delete(`${base}/${ids.join(',')}`).then(res => res.data),
    }
}