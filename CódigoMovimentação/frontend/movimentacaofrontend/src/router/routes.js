const routes = [
  {
    path: '/',
    component: () => import('layouts/AuthLayout.vue'),
    children: [
      {
        path: '',
        name: 'login',
        component: () => import('pages/LoginPage.vue')  // <-- ESSA É A ROTA RAIZ
      },
      {
        path: 'register',
        name: 'register',
        component: () => import('pages/IndexPage.vue')
      },
      {
        path: 'newpassword',
        name: 'newpassword',
        component: () => import('pages/NewPassword.vue')
      }
    ]
  },

  {
    path: '/app',
    component: () => import('layouts/MainLayout.vue'),
    children: [
      {
        path: 'profile',
        name: 'profile',
        component: () => import('pages/ProfilePage.vue')
      },
      {
        path: 'movements',
        name: 'movements',
        component: () => import('pages/MovementsPage.vue')
      }
    ]
  },

  {
    path: '/:catchAll(.*)*',
    component: () => import('pages/ErrorNotFound.vue')
  }
]

export default routes
