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
				this.success('The timesheet has been successfully created.');
			});

			// subscribe to the USER_UPDATED event
			when(TimeSheetsStore.TIMESHEET_UPDATED).subscribe(() => {
				this.success('The timesheet has been successfully updated.');
			});

			// subscribe to the USER_UPDATED event
			when(TimeSheetsStore.TIMESHEET_REMOVED).subscribe(() => {
				this.success('The timesheet has been successfully removed.');
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
				TimeSheetsStore.allOfUser(this.userId, page);
			},

			success(message) {
				sweetalert({
					title: 'Congratulations!',
					text: message,
					type: 'success',
					timer: 3000,
					showConfirmButton: false,
					allowOutsideClick: true
				});
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
		</div>
		<div class="col-md-3 col-md-offset-3">
			<input type="text" value="01/01/15 - 01/08/15" class="form-control" data-provide="datepicker">
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
		<tr v-for="timesheet in timesheets">
			<th class="col-md-2" scope="row">{{ timesheet.date }}</th>
			<td class="col-md-2">{{ timesheet.hours }}</td>
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