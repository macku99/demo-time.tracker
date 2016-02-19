require('./core/dependencies');

Vue.use(require('vue-resource'));
Vue.http.headers.common['Authorization'] = 'Bearer ' + localStorage.getItem('token');
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
	}).on('apply.daterangepicker', function(e, picker) {
		$(this).val(picker.startDate.format('MMMM Do YYYY') + ' - ' + picker.endDate.format('MMMM Do YYYY'));

		when('filter.timesheets.by.date.range')
			.broadcast($(this).val());
	}).on('cancel.daterangepicker', function(e, picker) {
		$(this).val('');

		when('filter.timesheets.by.date.range')
			.broadcast($(this).val());
	});

	$('.datepicker').daterangepicker({
		singleDatePicker: true,
		maxDate: moment(),
		locale: {
			format: 'MMMM Do YYYY'
		}
	});

	/*$('.loginForm').on('submit', function(e) {
		e.preventDefault();

		$.ajax({
			method: $(this).attr('method'),
			url: $(this).attr('action'),
			data: $(this).serialize()
		}).done(function(response) {
			console.log(response);
		});
	})*/
});