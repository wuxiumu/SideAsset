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
        path: '/domains/:domain/subdomains',
        name: 'SubdomainManager',
        component: SubdomainManager,
        props: true
    },
]

const router = createRouter({
    history: createWebHashHistory(),
    routes,
})

export default router