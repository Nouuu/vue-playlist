import Vue from 'vue'
import VueRouter from 'vue-router'
import Dashboard from "@/components/Dashboard";
import Login from "@/components/Login";
import Logout from "@/components/Logout";

Vue.use(VueRouter)

const routes = [
    {
        path: '/',
        name: 'Dashboard',
        component: Dashboard,
    },
    {
        path: '/login',
        name: 'Login',
        component: Login,
    },
    {
        path: '/settings',
        name: 'Settings',
        // route level code-splitting
        // this generates a separate chunk (about.[hash].js) for this route
        // which is lazy-loaded when the route is visited.
        component: () => import(/* webpackChunkName: "about" */ '@/components/Settings')
    },
    {
        path: '/users',
        name: 'Users',
        component: () => import('@/components/User/Users')
    },
    {
        path: '/user/:email_user',
        name: 'User',
        component: () => import('@/components/User/User')
    },
    {
        path: '/search',
        name: 'Search',
        component: () => import('@/components/Search')
    },
    {
        path: '/search/album/:album_id',
        name: 'Album view',
        component: () => import('@/components/Discogs/AlbumView')
    },
    {
        path: '/search/artist/:artist_id',
        name: 'Artist view',
        component: () => import('@/components/Discogs/ArtistView')
    },
    {
        path: '/logout',
        name: 'Logout',
        component: Logout
    }
]

const router = new VueRouter({
    mode: 'history',
    base: process.env.BASE_URL,
    routes
})

router.beforeEach((to, from, next) => {
    if (to.name !== 'Login' && sessionStorage.getItem('connected') !== 'true') next({name: 'Login'})
    else next()
})


export default router
