import VueSweetalert2 from "vue-sweetalert2";
import "sweetalert2/dist/sweetalert2.min.css";

/** plugins */
import BootstrapVue3 from "bootstrap-vue-3";
import "bootstrap/dist/css/bootstrap.css";
import "bootstrap-vue-3/dist/bootstrap-vue-3.css";

import Toaster from "@meforma/vue-toaster";

import axios from "./axios";
import "./vee-validate";
import VCalendar from "v-calendar";
import 'v-calendar/dist/style.css';
import "./vee-validate";
import VueCountdown from '@chenfengyuan/vue-countdown';

import "vue-select/dist/vue-select.css";
import vSelect from 'vue-select';

import filters from "./filter";

import { VueEditor } from "vue3-editor";
import Quill from 'quill';
import eventBus from './event-bus';
import { plugin as VueTippy } from 'vue-tippy'
import Vue3TagsInput from 'vue3-tags-input'
import 'tippy.js/dist/tippy.css'
import 'tippy.js/themes/light.css'

let AlignStyle = Quill.import('attributors/style/align')
let BackgroundStyle = Quill.import('attributors/style/background')
let ColorStyle = Quill.import('attributors/style/color')
let DirectionStyle = Quill.import('attributors/style/direction')
let FontStyle = Quill.import('attributors/style/font')
let SizeStyle = Quill.import('attributors/style/size')

Quill.register(AlignStyle, true);
Quill.register(BackgroundStyle, true);
Quill.register(ColorStyle, true);
Quill.register(DirectionStyle, true);
Quill.register(FontStyle, true);
Quill.register(SizeStyle, true);

import { abilitiesPlugin } from "@casl/vue";
import { ability } from "./define-ability";
const { version } = require("../../package.json");

const register = (app) => {
  app.use(VueSweetalert2);
  app.use(abilitiesPlugin, ability)

  /** plugin */
  app.use(BootstrapVue3);
  app.use(Toaster, {
    position: "top-right",
  });
  app.use(VCalendar, {});

  app.config.globalProperties.$axios = axios;
  app.config.globalProperties.$window = window;
  app.config.globalProperties.$eventBus = eventBus;
  app.config.globalProperties.$version = version;
  app.config.globalProperties.$appName = process.env.VUE_APP_TITLE;
  app.config.globalProperties.$appEnv = process.env.VUE_APP_ENV;
  app.config.globalProperties.$API_URL = process.env.VUE_APP_API_URL;
  
  
  app.component(VueCountdown.name, VueCountdown);


  app.config.globalProperties.$filters = filters;
  app.component('v-select', vSelect);
  app.component('vue-editor', VueEditor);
  app.component('vue3-tags-input', Vue3TagsInput)
  app.use(
    VueTippy,
    // optional
    {
      theme: 'light',
      directive: 'tippy', // => v-tippy
      component: 'tippy', // => <tippy/>
      componentSingleton: 'tippy-singleton', // => <tippy-singleton/>,
      defaultProps: {
        placement: 'auto-end',
        allowHTML: true,
      }, // => Global default options * see all props
    }
  )

};

export default {
  register,
};
