var Vue = require('vue')
var VueResource = require('vue-resource')
var Vuetable = require('vuetable/src/components/Vuetable.vue')
var VuetablePagination = require('vuetable/src/components/VuetablePagination.vue')
var VuetablePaginationDropdown = require('vuetable/src/components/VuetablePaginationDropdown.vue')
var VuetablePaginationBootstrap = require('vuetable/src/components/VuetablePaginationBootstrap.vue')
var VuetablePaginationSimple = require('../vendor/vue-table/components/VuetablePaginationSimple.vue')
var VueEditable = require('../vendor/vue-editable/vue-editable.js')
var VueStrap = require('../vendor/vue-strap/vue-strap.min.js')
var VueValidator = require('vue-validator')

Vue.use(VueResource)
Vue.use(VueEditable)
Vue.use(VueValidator)

Vue.component('vuetable', Vuetable);
Vue.component('vuetable-pagination', VuetablePagination)
Vue.component('vuetable-pagination-dropdown', VuetablePaginationDropdown)
Vue.component('vuetable-pagination-bootstrap', VuetablePaginationBootstrap)
Vue.component('vuetable-pagination-simple', VuetablePaginationSimple)

var E_SERVER_ERROR = 'Error communicating with the server';

Vue.config.debug = true        

Vue.component('custom-error', {
  props: ['field', 'validator', 'message'],
  template: '<em><p class="error-{{field}}-{{validator}}">{{message}}</p></em>'
});

var vm = new Vue({
    components: {
        modal: VueStrap.modal,
        'v-select': VueStrap.select
    },
    el: "#crud-app",
    data: {
        formModal: false,
        infoModal: false,
        showModal: false,
        deleteModal: false,
        flashMessage: null,
        defaultErrorMessage: 'Some errors in sended data, please check!.',
        flashTypeDanger: 'danger',
        flashType: null,
        submitMessage: "",
        url: apiUrl,           
        row: objectRow,
        searchFor: '',
        columns: tableColumns,     
        sortOrder: {
            field: fieldInitOrder,
            direction: 'asc'
        },
        perPage: 10,
        paginationComponent: 'vuetable-pagination-bootstrap',
        paginationInfoTemplate: 'แสดง {from} ถึง {to} จากทั้งหมด {total} รายการ',
        itemActions: [
            { name: 'view-item', label: '', icon: 'glyphicon glyphicon-zoom-in', class: 'btn btn-info', extra: {'title': 'View', 'data-toggle':"tooltip", 'data-placement': "left"} },
            { name: 'edit-item', label: '', icon: 'glyphicon glyphicon-pencil', class: 'btn btn-warning', extra: {title: 'Edit', 'data-toggle':"tooltip", 'data-placement': "top"} },
            { name: 'delete-item', label: '', icon: 'glyphicon glyphicon-remove', class: 'btn btn-danger', extra: {title: 'Delete', 'data-toggle':"tooltip", 'data-placement': "right" } }
        ],
        moreParams: []                                 
    },
    watch: {
        'perPage': fu