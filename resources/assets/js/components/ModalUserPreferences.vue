<script type="text/babel">
	var Form = require('../helpers/Form');
	var UsersStore = require('../stores/UsersStore');

	export default {
		props: {
			userId: {
				type: Number,
				required: true
			},
			preferredDailyHours: {
				type: Number,
				required: true,
				default: null
			},
			title: {
				type: String,
				default: ''
			}
		},

		data() {
			return {
				show: false,
				preferencesForm: this.resetFormData(),
				dataProviders: {
					hours: _.range(24).map((value) => {
						return value + 1;
					})
				}
			};
		},

		ready() {
			when('show.user.preferences.modal')
				.subscribe(() => {
					this.$set('show', true);
					if (this.preferredDailyHours) {
						this.$set('preferencesForm', new Form({
							preferredDailyHours: this.preferredDailyHours
						}));
					} else {
						this.$set('preferencesForm', this.resetFormData());
					}
				});
		},

		methods: {
			updateUserPreferences() {
				this.preferencesForm.send(
					UsersStore.updateUserPreferences(this.userId, this.preferencesForm.data)
						.then(() => {
							// close modal
							this.close();

							// cache the preferredDailyHours
							this.preferredDailyHours = this.preferencesForm.data.preferredDailyHours
						}));
			},

			resetFormData() {
				return new Form({
					preferredDailyHours: null
				});
			},

			close() {
				this.show = false;
				//this.preferencesForm = this.resetFormData();
			}
		},

		computed: {
			formData() {
				return this.preferencesForm.data;
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
			<div class="form-group" :class="{ 'has-error': preferencesForm.hasError('preferredDailyHours') == true }">
				<label for="preferredDailyHours" class="col-md-4 control-label">Preferred Daily Hours</label>

				<div class="col-md-3">
					<select id="preferredDailyHours" name="preferredDailyHours"
					        class="form-control"
					        v-model="formData.preferredDailyHours"
					>
						<option :value="null">please select</option>
						<option v-for="hour in dataProviders.hours"
						        :value="hour"
						>
							{{ hour }} {{ hour | pluralize 'hour' }}
						</option>
					</select>
					<form-error-block :form="preferencesForm" attribute="preferredDailyHours"></form-error-block>
				</div>
			</div>
		</form>

		<button slot="action"
		        type="button"
		        class="btn btn-info"
		        @click.prevent="updateUserPreferences"
		        :disabled="preferencesForm.busy"
		>
			<span v-if="preferencesForm.busy">
				SUBMITTING...
			</span>
			<span v-else>
				SUBMIT
			</span>
		</button>
	</modal>
</template>