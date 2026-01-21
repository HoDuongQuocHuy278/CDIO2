import { createRouter, createWebHistory } from 'vue-router';
import Login from '../components/Login/index.vue';
import Dashboard from '../components/Dashboard/index.vue';
import Members from '../components/Members/index.vue';

const routes = [
    {
        path: '/login',
        name: 'Login',
        component: Login,
        meta: { requiresAuth: false }
    },
    {
        path: '/',
        name: 'Dashboard',
        component: Dashboard,
        meta: { requiresAuth: true }
    },
    {
        path: '/members',
        name: 'Members',
        component: Members,
        meta: { requiresAuth: true }
    },
    {
        path: '/:pathMatch(.*)*',
        redirect: '/'
    }
];

const router = createRouter({
    history: createWebHistory(),
    routes
});

// Navigation guard
router.beforeEach((to, from, next) => {
    const token = localStorage.getItem('key_admin');
    const requiresAuth = to.matched.some(record => record.meta.requiresAuth);

    if (requiresAuth && !token) {
        // Cần đăng nhập nhưng chưa có token
        next('/login');
    } else if (to.path === '/login' && token) {
        // Đã đăng nhập rồi thì không cho vào trang login
        next('/');
    } else {
        next();
    }
});

export default router;
