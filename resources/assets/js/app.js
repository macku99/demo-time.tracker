require('./core/dependencies');

Vue.use(require('vue-resource'));
Vue.config.debug = true;

Vue.component('users-list', require('./components/UsersList.vue'));
Vue.component('timesheets-list', require('./components/TimeSheetsList.vue'));

new Vue({
	el: 'body'
});

$(() => {
	$('input[name="daterange"]').daterangepicker({
		autoUpdateInput: false,
		ranges: {
			'Today': [moment(), moment()],
			'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
			'Last 7 Days': [moment().subtract(6, 'days'), moment()],
			'Last 30 Days': [moment().subtract(29, 'days'), moment()],
			'This Month': [moment().startOf('month'), moment().endOf('month')],
			'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
		},
		locale: {
			cancelLabel: 'Clear',
			format: 'MMMM Do YYYY'
		}
	}).on('apply.daterangepicker', function(ev, picker) {
		$(this).val(picker.startDate.format('MMMM Do YYYY') + ' - ' + picker.endDate.format('MMMM Do YYYY'));
	}).on('cancel.daterangepicker', function(ev, picker) {
		$(this).val('');
	});

	$('.datepicker').daterangepicker({
		singleDatePicker: true,
		maxDate: moment(),
		locale: {
			format: 'MMMM Do YYYY'
		}
	});
});