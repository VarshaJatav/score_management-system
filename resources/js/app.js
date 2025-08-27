import './bootstrap'
import '../css/app.css'
import './echo'
import { createApp } from 'vue';
import Scoreboard from './components/Scoreboard.vue';
import AdminPanel from './components/AdminPanel.vue';
import Login from './components/login.vue' ;

const app = createApp({});
app.component('scoreboard', Scoreboard)
app.component('admin-panel', AdminPanel)
app.component('login', Login)
app.mount('#app');
