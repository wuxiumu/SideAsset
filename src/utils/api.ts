import axios from 'axios'

export function useCrudApi(base: string) {
    return {
        // 分页列表，支持 params 可扩展
        list: (params?: any) =>
            axios.get(`${base}`, { params }).then(res => res.data),
        // 详情（推荐统一命名为 show）
        show: (id: string | number) =>
            axios.get(`${base}/${id}`).then(res => res.data),
        // 兼容旧写法
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
        // 批量删除
        batchRemove: (ids: any[]) =>
            axios.delete(`${base}/${ids.join(',')}`).then(res => res.data),
    }
}