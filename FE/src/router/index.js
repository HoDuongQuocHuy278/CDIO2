import { createRouter, createWebHistory } from 'vue-router';


const routes = [
    {
        path: '/admin/login',
        component: () => import('../components/Admin/Login/index.vue'),
       
    },
    {
        path: '/admin/dashboard',
        component: () => import('../components/Admin/Dashboard/index.vue'),
       
    },
    {
        path: '/admin/members',
        component: () => import('../components/Admin/Members/index.vue'),
       
    },
   
];

const router = createRouter({
    history: createWebHistory(),
    routes
});

// Navigation guard


export default router;
