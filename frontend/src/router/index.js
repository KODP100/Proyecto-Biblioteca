import { createRouter, createWebHistory } from 'vue-router';
import LoginView from "../views/LoginView.vue";
import RegisterView from "../views/RegisterView.vue";
import DashboardLayout from "../layouts/DashboardLayout.vue";
import InicioDashboard from "../views/InicioDashboard.vue";
import EditorialesView from '../views/EditorialesView.vue';
import LibroView from '../views/LibroView.vue';
import MultasView  from '../views/MultasView.vue';
import PrestamosView from '../views/PrestamosView.vue';
import DevolucionesView from '../views/DevolucionesView.vue';



const routes = [
  { path: "/", component: LoginView },
  { path: "/register", component: RegisterView },

  {
    path: "/",
    component: DashboardLayout,
    children: [
      { path: "home", component: InicioDashboard },
      {path: "editoriales", component: EditorialesView },
      {path: "libros", component: LibroView },
      {path: "multas", component: MultasView },
      {path: "prestamos", component: PrestamosView },
      {path: "devoluciones", component: DevolucionesView },
    ],
  },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

export default router;
