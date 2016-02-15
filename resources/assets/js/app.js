require('./core/dependencies');

Vue.use(require('vue-resource'));
Vue.config.debug = true;

Vue.component('users-list', require('./components/UsersList.vue'));
Vue.component('timesheets-list', require('./components/TimeSheetsList.vue'));

new Vue({
	el: 'body'
});