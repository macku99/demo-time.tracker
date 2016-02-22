<script type="text/babel">
	var TimeSheetsStore = require('../stores/TimeSheetsStore');

	export default {
		props: {
			userId: {
				required: true
			}
		},

		data() {
			return {
				timesheets: [],
				pagination: {
					currentPage: 1,
					totalPages: 1,
					perPage: 20
				},
				columns: [
					'Date', 'Hours', 'Description'
				],
				filters: {
					dateRange: null
				},
				modal: {
					title: 'Create TimeSheet',
					type: 'create'
				}
			}
		},

		ready() {
			// fetch the user's timesheets
			TimeSheetsStore.allOfUser(this.userId);

			// subscribe to the USERS_LIST_RETRIEVED event
			when(TimeSheetsStore.TIMESHEETS_LIST_RETRIEVED).subscribe(response => {
				this.$set('timesheets', response.data);
				this.$set('pagination.currentPage', response.meta.pagination.current_page);
				this.$set('pagination.totalPages', response.meta.pagination.total);
				this.$set('pagination.perPage', response.meta.pagination.per_page);
			});

			// subscribe to the USER_CREATED event
			when(TimeSheetsStore.TIMESHEET_CREATED).subscribe(() => {
				Helpers.success('The timesheet has been successfully created.');
			});

			// subscribe to the USER_UPDATED event
			when(TimeSheetsStore.TIMESHEET_UPDATED).subscribe(() => {
				Helpers.success('The timesheet has been successfully updated.');
			});

			// subscribe to the USER_UPDATED event
			when(TimeSheetsStore.TIMESHEET_REMOVED).subscribe(() => {
				Helpers.success('The timesheet has been successfully removed.');
			});

			// subscribe to the find out if the logged in user has updated his preferences
			when('update.logged.in.user.preferred.daily.hours').subscribe((userId, preferredDailyHours) => {
				// if we are looking to the logged in user timesheets list
				if (this.userId == userId) {
					// fetch the user's timesheets
					TimeSheetsStore.allOfUser(this.userId);
				}
			});

			// subscribe to find out if the timesheets list is filtered by date range
			when('filter.timesheets.by.date.range').subscribe((dateRange) => {
				this.$set('filters', {
					dateRange: dateRange
				});

				// fetch the user's timesheets
				TimeSheetsStore.allOfUser(this.userId, null, this.filters.dateRange);
			});
		},

		methods: {
			showCreateTimeSheetModal() {
				this.modal = {
					type: 'create',
					title: 'Create Timesheet'
				};
				when('show.create.or.update.timesheet.modal')
					.broadcast(null);
			},
			showUpdateTimeSheetModal(timesheet) {
				this.modal = {
					type: 'update',
					title: 'Update Timesheet'
				};
				when('show.create.or.update.timesheet.modal')
					.broadcast(timesheet);
			},

			removeTimeSheet(timesheet) {
				sweetalert({
					title: "Are you sure?",
					text: "Are you sure you want to remove this timesheet?",
					type: "warning",
					showCancelButton: true
				}, () => {
					TimeSheetsStore.removeTimeSheet(this.userId, timesheet).then(() => {
						// fetch the user's timesheets
						TimeSheetsStore.allOfUser(this.userId);
					});
				});
			},

			whenPaginationPageHasChanged(page) {
				// fetch the user's timesheets for the page
				TimeSheetsStore.allOfUser(this.userId, page, this.filters.dateRange);
			},

			userOverDidIt(preferredDailyHours, totalHoursWorked) {
				return totalHoursWorked >= preferredDailyHours;
			},

			rowClass(preferredDailyHours, totalHoursWorked) {
				if (this.userOverDidIt(preferredDailyHours, totalHoursWorked)) {
					return 'success';
				}

				return 'danger';
			}
		},

		components: {
			'modal-create-or-update-timesheet': require('../components/ModalCreateOrUpdateTimeSheet.vue'),
			'pagination': require('../components/Pagination.vue')
		}
	};
</script>

<template>
	<div class="row toolbar">
		<div class="col-md-6">
			<button
				type="button"
				class="btn btn-info"
				@click.prevent="showCreateTimeSheetModal()"
			>
				Create Timesheet
			</button>
			<a
				:href="'/users/' + userId + '/timesheets/export/' + (filters.dateRange ? filters.dateRange : '')"
				target="_blank"
				class="btn btn-warning"
			    v-if="timesheets.length"
			>
				Export TimeSheets
			</a>
		</div>
		<div class="col-md-4 col-md-offset-2">
			<input
				type="text"
				class="form-control"
				name="daterange"
				placeholder="filter by date range"
				v-model="filters.dateRange"
				:value="filters.dateRange"
			>
		</div>
	</div>

	<table class="table table-hover">
		<thead>
		<tr>
			<th v-for="column in columns">{{ column }}</th>
			<th class="text-right">&nbsp;</th>
		</tr>
		</thead>
		<tbody>
		<tr
			:class="rowClass(timesheet.user.data.preferredDailyHours, timesheet.totalHoursWorkedOnTheDate)"
			v-for="timesheet in timesheets"
		>
			<th class="col-md-2" scope="row">{{ timesheet.date }}</th>
			<td class="col-md-2">{{ timesheet.hours }} {{ timesheet.hours | pluralize 'hour' }}</td>
			<td class="col-md-6">{{ timesheet.description }}</td>
			<td class="col-md-2 text-right">
				<div class="btn-group">
					<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown"
					        aria-haspopup="true" aria-expanded="false">
						Actions <span class="caret"></span>
					</button>
					<ul class="dropdown-menu">
						<li>
							<a href="#" @click.prevent="showUpdateTimeSheetModal(timesheet)">Update</a>
						</li>
						<li><a href="#" @click.prevent="removeTimeSheet(timesheet)">Remove</a></li>
					</ul>
				</div>
			</td>
		</tr>
		<tr v-if="!timesheets.length">
			<td colspan="4" class="text-center">There are no timesheets recorded.</td>
		</tr>
		</tbody>
	</table>

	<pagination
		:current-page.sync="pagination.currentPage"
		:total-items="pagination.totalPages"
		:per-page="pagination.perPage"
		@pagination.page.changed="whenPaginationPageHasChanged"
		v-if="timesheets"
	>
	</pagination>

	<modal-create-or-update-timesheet :user-id="userId" :type="modal.type"
	                                  :title="modal.title"></modal-create-or-update-timesheet>

</template>