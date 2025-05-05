import { createRouter, createWebHistory } from 'vue-router';
import LoginView from "../views/LoginView.vue";
import RegisterView from "../views/RegisterView.vue";
import DashboardLayout from "../layouts/DashboardLayout.vue";
import InicioDashboard from "../views/InicioDashboard.vue";
import DepartamentosView from "../views/DepartamentosView.vue";
import DistritosView from '../views/DistritosView.vue';
import MunicipiosView from '../views/MunicipiosView.vue';
import EmpleadosView from '../views/EmpleadosView.vue';
import CategoriaView from '../views/CategoriaView.vue';
import EstadoLibroView from '../views/EstadoLibroView.vue';
import EditorialesView from '../views/EditorialesView.vue';
import AutoresView from '../views/AutoresView.vue';



const routes = [
  { path: "/", component: LoginView },
  { path: "/register", component: RegisterView },

  {
    path: "/",
    component: DashboardLayout,
    children: [
      { path: "home", component: InicioDashboard },
      { path: "departamento", component: DepartamentosView },
      {path: "distritos", component: DistritosView },
      {path: "municipios", component: MunicipiosView },
      {path: "empleados", component: EmpleadosView },
      {path: "categorias", component: CategoriaView },
      {path: "estadolibros", component: EstadoLibroView },
      {path: "editoriales", component: EditorialesView },
      {path: "autores", component: AutoresView },
    ],
  },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

export default router;
