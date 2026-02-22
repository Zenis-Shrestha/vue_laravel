import { createRouter, createWebHistory } from "vue-router";

import Login from "../components/Login.vue";
import Register from "../components/Register.vue";
import Dashboard from "../components/Dashboard.vue";
import Users from "../components/Users.vue";
import UserCreate from "../components/UserCreate.vue";
import UserEdit from "../components/UserEdit.vue";
import Aboutme from "../components/Aboutme.vue";

const routes = [
    { path: "/", redirect: "/login" },

    { path: "/login", name: "login", component: Login },
    { path: "/register", name: "register", component: Register },

    { path: "/dashboard", name: "dashboard", component: Dashboard, meta: { requiresAuth: true } },
    { path: "/users", name: "users", component: Users, meta: { requiresAuth: true } },
    { path: "/users/create", name: "users-create", component: UserCreate, meta: { requiresAuth: true } },
    {
        path: "/users/:id/edit",
        name: "users-edit",
        component: UserEdit,
        meta: { requiresAuth: true },
    },
    { path: "/about", name: "about", component: Aboutme, meta: { requiresAuth: true } },

];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

router.beforeEach((to, from, next) => {
    const token = localStorage.getItem("token");
    if (to.meta.requiresAuth && !token) return next("/login");
    next();
});

export default router;
