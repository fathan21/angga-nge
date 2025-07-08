




/** app */
import App from './App.vue';
import {createApp} from 'vue';
import routes from './routes';
import store from "./store";

import BaseComponents from './components/index';
import Plugins from './plugins/index';


import './assets/css/custom.scss'
import './assets/css/app.css'



const app = createApp(App);
app.use(routes).use(store);

BaseComponents.register(app);
Plugins.register(app);

app.mount('#app')