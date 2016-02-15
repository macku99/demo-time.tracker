<script type="text/babel">
	var Form = require('../helpers/Form');
	var TimeSheetsStore = require('../stores/TimeSheetsStore');

	export default {
		props: {
			userId: {
				required: true
			},
			type: {
				type: String,
				required: true,
				default: 'create'
			},
			title: {
				type: String,
				default: ''
			}
		},

		data() {
			return {
				show: false,
				timesheet: null,
				timeSheetForm: this.resetFormData()
			};
		},

		ready() {
			when('show.create.or.update.timesheet.modal')
				.subscribe(timesheet => {
					this.$set('show', true);
					this.$set('timesheet', timesheet);
					this.$set('timeSheetForm', this.resetFormData());
				});
		},

		methods: {
			createOrUpdateTimeSheet() {
				if (this.type == 'create') {
					return this.createTimeSheet();
				} else {
					return this.updateTimeSheet();
				}
			},
			createTimeSheet() {
				var createTimeSheetPromise = TimeSheetsStore.storeTimeSheet(this.userId, this.timeSheetForm.data);
				this.timeSheetForm.send(createTimeSheetPromise.then(() => {
					// fetch the user's timesheets
					TimeSheetsStore.allOfUser(this.userId);

					this.show = false;
					this.timesheet = null;
					this.timeSheetForm = this.resetFormData();
				}));
			},
			updateTimeSheet() {
				var updateTimeSheetPromise = TimeSheetsStore.updateTimeSheet(this.userId, this.timesheet, this.timeSheetForm.data);
				this.timeSheetForm.send(updateTimeSheetPromise.then(() => {
					// fetch the user's timesheets
					TimeSheetsStore.allOfUser(this.userId);

					this.show = false;
					this.timesheet = null;
					this.timeSheetForm = this.resetFormData();
				}));
			},

			resetFormData() {
				return new Form({
					date: null,
					hours: null,
					description: null
				});
			},

			close() {
				this.show = false;
				this.timesheet = null;
				this.timeSheetForm = this.resetFormData();
			}
		},

		computed: {
			formData() {
				return this.timeSheetForm.data;
			}
		},

		components: {
			modal: require('../components/Modal.vue'),
			'form-error-block': require('../components/FormErrorBlock.vue')
		}
	}
</script>


<template>
	<modal :show.sync="show" @hidden.modal="close" :title="title">

		<form class="form-horizontal">
			<div class="form-group" :class="{ 'has-error': timeSheetForm.hasError('date') == true }">
				<label for="date" class="col-md-4 control-label">Date</label>

				<div class="col-md-3">
					<input type="text" id="date" name="date"
					       class="form-control"
					       placeholder="31/12/2015"
					       v-model="formData.date"
					       :value="timesheet ? timesheet.date : null"
					>
					<form-error-block :form="timeSheetForm" attribute="date"></form-error-block>
				</div>
			</div>
			<div class="form-group" :class="{ 'has-error': timeSheetForm.hasError('hours') == true }">
				<label for="hours" class="col-md-4 control-label">Hours</label>

				<div class="col-md-3">
					<input type="text" id="hours" name="hours"
					       class="form-control"
					       placeholder="12"
					       v-model="formData.hours"
					       :value="timesheet ? timesheet.hours : null"
					>
					<form-error-block :form="timeSheetForm" attribute="hours"></form-error-block>
				</div>
			</div>
			<div class="form-group" :class="{ 'has-error': timeSheetForm.hasError('description') == true }">
				<label for="description" class="col-md-4 control-label">Description</label>
				<div class="col-md-6">
					<textarea id="description" name="description"
					       class="form-control"
					       rows="10"
					       v-model="formData.description"
					>{{ timesheet ? timesheet.description : null }}</textarea>
					<form-error-block :form="timeSheetForm" attribute="description"></form-error-block>
				</div>
			</div>
		</form>

		<button slot="action"
		        type="button"
		        class="btn btn-info"
		        @click.prevent="createOrUpdateTimeSheet"
		        :disabled="timeSheetForm.busy"
		>
			<span v-if="timeSheetForm.busy">
				SUBMITTING...
			</span>
			<span v-else>
				SUBMIT
			</span>
		</button>
	</modal>
</template>