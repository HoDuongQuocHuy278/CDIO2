import { createRouter, createWebHistory } from 'vue-router';



const routes = [
    {
        path: '/admin/login',
        component: () => import('../components/Admin/Login/index.vue'),
    },

    {
        path: '/admin/forgotPassword',
        component: () => import('../components/Admin/ForgotPassWord/index.vue'),
    },

    {
        path: '/admin/resetPassword',
        component: () => import('../components/Admin/ResetPassWord/index.vue'),
    },

    {
        path: '/admin/OTPcode',
        component: () => import('../components/Admin/OTPCode/index.vue'),
    },

    {
        path: "/admin/dashboard",
        component: () => import("../components/Admin/Dashboard/index.vue"),
        meta: {
            layout: "AdminLayout",
        },
    },

    {
        path: "/admin/members",
        component: () => import("../components/Admin/Member/index.vue"),
        meta: {
            layout: "AdminLayout",
        },
    },

    {
        path: "/admin/checkin",
        component: () => import("../components/Admin/Checkin/index.vue"),
        meta: {
            layout: "AdminLayout",
        },
    },

    {
        path: "/admin/services",
        component: () => import("../components/Admin/Service/index.vue"),
        meta: {
            layout: "AdminLayout",
        },
    },
];

const router = createRouter({
    history: createWebHistory(),
    routes
});

// Navigation guard


export default router;
