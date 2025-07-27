import { createApp } from 'vue'
import App from './App.vue'
import ElementPlus from 'element-plus'
import 'element-plus/dist/index.css'
import '@/styles/element-custom.css'
import { createPinia } from 'pinia'
import router from './router' // ✅ 导入路由

const app = createApp(App)
app.use(createPinia())
app.use(ElementPlus)
app.use(router)  // ✅ 注册路由
app.mount('#app')