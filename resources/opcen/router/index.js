import { createWebHistory, createRouter } from "vue-router";

import Home from "@/views/Home.vue";
import Login from "@/views/Login.vue";
import Emt from "@/views/Emt.vue";
import Reports from "@/views/Reports.vue";
import ActiveDispatch from "@/views/EmtViews/ActiveDispatch.vue";
import DispatchHistory from "@/views/EmtViews/DispatchHistory.vue";
import Administrator from "@/views/Administrator.vue";
import AdminOverview from "@/views/AdminViews/Overview.vue";
import Ambulances from "@/views/AdminViews/Ambulances.vue";
import Drivers from "@/views/AdminViews/Drivers.vue";
import HealthFacilities from "@/views/AdminViews/HealthFacilities.vue";
import Responders from "@/views/AdminViews/Responders.vue";
import Users from "@/views/AdminViews/Users.vue";
import Unauthorized from "@/views/Unauthorized.vue";
import ReportsOverview from "@/views/ReportsViews/ReportsOverview.vue";
import ReportsList from "@/views/ReportsViews/ReportsList.vue";
import { useUserStore } from '@/stores/user/userStore'

const routes = [
  {
    path: "/",
    name: "Opcen",
    component: Home,
    meta: {
      can: 'view-opcen',
      onDeniedRoute: '/unauthorized'
    }
  },
  {
    path: "/emt",
    name: "EmtBase",
    component: Emt,
    meta: {
      can: 'is-emt',
      onDeniedRoute: '/unauthorized'
    },
    children: [
      {
        path: "",
        name: "Active Dispatch",
        component: ActiveDispatch
      },
      {
        path: "/emt/dispatch-history",
        name: "Dispatch History",
        component: DispatchHistory
      }
    ]
  },
  {
    path: "/admin",
    name: "AdminBase",
    component: Administrator,
    meta: {
      can: 'view-admin',
      onDeniedRoute: '/unauthorized'
    },
    children: [
      {
        path: "",
        name: "AdminOverview",
        component: AdminOverview
      },
      {
        path: "/admin/ambulances",
        name: "Ambulances",
        component: Ambulances
      },
      {
        path: "/admin/drivers",
        name: "Drivers",
        component: Drivers
      },
      {
        path: "/admin/health-facilities",
        name: "HealthFacilities",
        component: HealthFacilities
      },
      {
        path: "/admin/responders",
        name: "Responders",
        component: Responders
      },
      {
        path: "/admin/Users",
        name: "Users",
        component: Users
      }
    ]
  },
  {
    path: "/reports",
    name: "ReportsBase",
    component: Reports,
    children: [
      {
        path: "",
        name: "ReportsOverview",
        component: ReportsOverview
      },
      {
        path: "/reports/list",
        name: "ReportsList",
        component: ReportsList
      }
    ]
  },
  {
    path: "/login",
    name: "Login",
    component: Login,
  },
  {
    path: "/unauthorized",
    name: "Unauthorized",
    component: Unauthorized
  },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

router.beforeEach((to, from, next) => {
  const store = useUserStore();
  if (to.name === 'Opcen' && store.user.role === 'emt') {
    next({ path: '/emt' })
  } else
    next()
})

export default router;