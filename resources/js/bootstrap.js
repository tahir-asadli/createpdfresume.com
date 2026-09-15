import axios from "axios";
import AirDatepicker from "air-datepicker";
import localeAz from "./air-datepicker/locale/az";
import localeGlobal from "./air-datepicker/locale/global";
import localeEn from "air-datepicker/locale/en";
import Sortable from "sortablejs";

window.axios = axios;
window.AirDatepicker = AirDatepicker;
window.localeAz = localeAz;
window.localeEn = localeEn;
window.localeGlobal = localeGlobal;
window.Sortable = Sortable;

// import Alpine from 'alpinejs';

// window.Alpine = Alpine;

// Alpine.start();

// import Alpine from 'alpinejs';
// import focus from '@alpinejs/focus'

// Alpine.plugin(focus)

// window.Alpine = Alpine;
// Alpine.start();

window.axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";
