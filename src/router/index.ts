import { createRouter, createWebHashHistory } from 'vue-router'
import Dashboard from '@/views/Dashboard.vue'
import DomainManager from '@/views/DomainManager.vue'
import ServerManager from '@/views/ServerManager.vue'
import NotFound from '@/views/NotFound.vue'
import SubdomainManager from '@/views/SubdomainManager.vue'
import InfoManager from '@/views/InfoManager.vue'
import ApiManager from '@/views/ApiManager.vue'
import AccountManager from '@/views/AccountManager.vue'
import BusinessAnalytics from '@/views/BusinessAnalytics.vue'
import LogAlertManager from '@/views/LogAlertManager.vue'
import ServerDetail from '@/views/ServerDetail.vue'

const routes = [
    { path: '/', name: 'Dashboard', component: Dashboard },
    { path: '/domains', name: 'Domains', component: DomainManager },
    { path: '/infoManager', name: 'InfoManager', component: InfoManager },
    { path: '/apiManager', name: 'ApiManager', component: ApiManager },
    { path: '/accountManager', name: 'AccountManager', component: AccountManager },
    { path: '/businessAnalytics', name: 'BusinessAnalytics', component: BusinessAnalytics },
    { path: '/logAlertManager', name: 'LogAlertManager', component: LogAlertManager },

    { path: '/servers', name: 'Servers', component: ServerManager },
    { path: '/:pathMatch(.*)*', name: 'NotFound', component: NotFound },
    {
        path: '/subdomain/:domain/:domainId',
        name: 'SubdomainManager',
        component: SubdomainManager,
        props: true
    },
    {
        path: '/servers/:id',
        name: 'ServerDetail',
        component: ServerDetail, // 详情页面
        props: true // 让id自动作为props传递
    },
]

const router = createRouter({
    history: createWebHashHistory(),
    routes,
})

export default router