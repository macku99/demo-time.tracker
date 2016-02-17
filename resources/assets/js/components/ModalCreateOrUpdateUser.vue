<script type="text/babel">
	var Form = require('../helpers/Form');
	var UsersStore = require('../stores/UsersStore');

	export default {
		props: {
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
				user: null,
				userForm: this.resetFormData(),
				dataProviders: {
					hours: _.range(24).map((value) => {
						return value + 1;
					})
				}
			};
		},

		ready() {
			when('show.create.or.update.user.modal')
				.subscribe(user => {
					this.$set('user', user);
					this.$set('show', true);
					if (user) {
						this.$set('userForm', new Form({
							name: user.name,
							email: user.email,
							password: null,
							password_confirmation: null,
							preferredDailyHours: user.preferredDailyHours
						}));
					} else {
						this.$set('userForm', this.resetFormData());
					}
				});
		},

		methods: {
			createOrUpdateUser() {
				if (this.type == 'update') {
					return this.updateUser();
				} else {
					return this.createUser();
				}
			},
			createUser() {
				var createUserPromise = UsersStore.storeUser(this.userForm.data);
				this.userForm.send(createUserPromise.then(() => {
					// fetch the users
					UsersStore.all();
					// close modal
					this.close();
				}));
			},
			updateUser() {
				var updateUserPromise = UsersStore.updateUser(this.user, this.userForm.data);
				this.userForm.send(updateUserPromise.then(() => {
					// fetch the users
					UsersStore.all();
					// close modal
					this.close();
				}));
			},

			resetFormData() {
				return new Form({
					name: null,
					email: null,
					password: null,
					password_confirmation: null,
					preferredDailyHours: 8
				});
			},

			close() {
				this.show = false;
				this.user = null;
				this.userForm = this.resetFormData();
			}
		},

		computed: {
			formData() {
				return this.userForm.data;
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
			<div class="form-group" :class="{ 'has-error': userForm.hasError('name') == true }">
				<label for="name" class="col-md-4 control-label">Name</label>

				<div class="col-md-6">
					<input type="text" id="name" name="name"
					       class="form-control"
					       placeholder="Joe Doe"
					       v-model="formData.name"
					       :value="user ? user.name : null"
					>
					<form-error-block :form="userForm" attribute="name"></form-error-block>
				</div>
			</div>
			<div class="form-group" :class="{ 'has-error': userForm.hasError('email') == true }">
				<label for="email" class="col-md-4 control-label">E-Mail Address</label>

				<div class="col-md-6">
					<input type="email" id="email" name="email"
					       class="form-control"
					       placeholder="joe@email.com"
					       v-model="formData.email"
					       :value="user ? user.email : null"
					>
					<form-error-block :form="userForm" attribute="email"></form-error-block>
				</div>
			</div>
			<div class="form-group" :class="{ 'has-error': userForm.hasError('password') == true }">
				<label for="password" class="col-md-4 control-label">Password</label>

				<div class="col-md-6">
					<input type="password" id="password" name="password"
					       class="form-control"
					       v-model="formData.password"
					       placeholder="min 6 chars"
					>
					<form-error-block :form="userForm" attribute="password"></form-error-block>
				</div>
			</div>
			<div class="form-group">
				<label for="password_confirmation" class="col-md-4 control-label">Confirm Password</label>

				<div class="col-md-6">
					<input type="password" id="password_confirmation" name="password_confirmation"
					       class="form-control"
					       v-model="formData.password_confirmation"
					       placeholder="same like password"
					>
				</div>
			</div>
			<div class="form-group" :class="{ 'has-error': userForm.hasError('preferredDailyHours') == true }">
				<label for="preferredDailyHours" class="col-md-4 control-label">Preferred Daily Hours</label>

				<div class="col-md-3">
					<select id="preferredDailyHours" name="preferredDailyHours"
					        class="form-control"
					        v-model="formData.preferredDailyHours"
					>
						<option v-for="hour in dataProviders.hours"
						        :value="hour"
						>
							{{ hour }} {{ hour | pluralize 'hour' }}
						</option>
					</select>
					<form-error-block :form="userForm" attribute="preferredDailyHours"></form-error-block>
				</div>
			</div>
		</form>

		<button slot="action"
		        type="button"
		        class="btn btn-info"
		        @click.prevent="createOrUpdateUser"
		        :disabled="userForm.busy"
		>
			<span v-if="userForm.busy">
				SUBMITTING...
			</span>
			<span v-else>
				SUBMIT
			</span>
		</button>
	</modal>
</template>