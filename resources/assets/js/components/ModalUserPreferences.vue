<script type="text/babel">
	var Form = require('../helpers/Form');
	var UsersStore = require('../stores/UsersStore');

	export default {
		props: {
			title: {
				type: String,
				default: ''
			}
		},

		data() {
			return {
				show: false,
				user: null,
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
				.subscribe(user => {
					this.$set('user', user);
					this.$set('show', true);
					if (user) {
						this.$set('preferencesForm', new Form({
							preferredDailyHours: user.preferredDailyHours
						}));
					} else {
						this.$set('preferencesForm', this.resetFormData());
					}
				});
		},

		methods: {
			updateUserPreferences() {
				this.preferencesForm.send(
					UsersStore.updateUserPreferences(this.user, this.preferencesForm.data)
						.then(() => {
							// close modal
							this.close();
						}));
			},

			resetFormData() {
				return new Form({
					preferredDailyHours: null
				});
			},

			close() {
				this.show = false;
				this.user = null;
				this.preferencesForm = this.resetFormData();
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