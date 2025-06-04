import { createRouter, createWebHistory } from 'vue-router'

const routes = [
    {
        path: '/',
        redirect: '/subscriptions'
    },
    {
        path: '/subscriptions',
        name: 'subscriptions.index',
        component: () => import('../components/SubscriptionTable.vue')
    },
    {
        path: '/subscriptions/create',
        name: 'subscriptions.create',
        component: () => import('../components/SubscriptionForm.vue')
    },
    {
        path: '/subscriptions/:id/edit',
        name: 'subscriptions.edit',
        props: (route) => {
            const id = +route.params.id;
            return { id };
        },
        component: () => import('../components/SubscriptionFormPage.vue')
    }
]

export default createRouter({
    history: createWebHistory(),
    routes
})
