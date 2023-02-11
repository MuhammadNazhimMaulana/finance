import Vue from 'vue'
import vueCustomElement from 'vue-custom-element'
import RootBalanceComponent from './components/general/RootBalanceComponent'
import VueSweetalert2 from 'vue-sweetalert2'
import 'sweetalert2/dist/sweetalert2.min.css'

Vue.config.productionTip = false
Vue.use(VueSweetalert2)
Vue.use(vueCustomElement)
Vue.customElement('general-root-balance', RootBalanceComponent)
