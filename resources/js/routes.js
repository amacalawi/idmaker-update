import Faculty from './components/FacultyComponent.vue'
import Student from './components/StudentComponent.vue'
import Home from './components/HomeComponent.vue'
import Example from './components/ExampleComponent.vue'

export const routes = [
    {
        path:'/home',
        component: Home
    },
    { 
        path:'/',
        component: Home
    }
];