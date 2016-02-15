<script type="text/babel">
	var UsersStore = require('../stores/UsersStore');

	export default {
		data() {
			return {
				users: [],
				pagination: {
					currentPage: 1,
					totalPages: 1,
					perPage: 20
				},
				columns: [
					'#', 'Name', 'E-mail Address'
				],
				modal: {
					title: 'Create User',
					type: 'create'
				}
			}
		},

		ready() {
			// retrieve users
			UsersStore.all();

			// subscribe to the USERS_LIST_RETRIEVED event
			when(UsersStore.USERS_LIST_RETRIEVED).subscribe(response => {
				this.$set('users', response.data);
				this.$set('pagination.currentPage', response.meta.pagination.current_page);
				this.$set('pagination.totalPages', response.meta.pagination.total);
				this.$set('pagination.perPage', response.meta.pagination.per_page);
			});

			// subscribe to the USER_CREATED event
			when(UsersStore.USER_CREATED).subscribe(() => {
				this.success('The user has been successfully created.');
			});

			// subscribe to the USER_UPDATED event
			when(UsersStore.USER_UPDATED).subscribe(() => {
				this.success('The user has been successfully updated.');
			});

			// subscribe to the USER_UPDATED event
			when(UsersStore.USER_REMOVED).subscribe(() => {
				this.success('The user has been successfully removed.');
			});
		},

		methods: {
			showCreateUserModal() {
				this.modal = {
					type: 'create',
					title: 'Create User'
				};
				when('show.create.or.update.user.modal')
					.broadcast(null);
			},
			showUpdateUserModal(user) {
				this.modal = {
					type: 'update',
					title: 'Update User'
				};
				when('show.create.or.update.user.modal')
					.broadcast(user);
			},

			removeUser(user) {
				sweetalert({
					title: "Are you sure?",
					text: "Are you sure you want to remove this user?",
					type: "warning",
					showCancelButton: true
				}, () => {
					UsersStore.removeUser(user).then(() => {
						// fetch the users
						UsersStore.all();
					});
				});
			},

			whenPaginationPageHasChanged(page) {
				// fetch the users for the page
				UsersStore.all(page);
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
			'modal-create-or-update-user': require('../components/ModalCreateOrUpdateUser.vue'),
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
				@click.prevent="showCreateUserModal()"
			>
				Create User
			</button>
		</div>
		<div class="col-md-3 col-md-offset-3">
			<!--input type="text" value="01/01/15 - 01/08/15" class="form-control" data-provide="datepicker"-->
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
		<tr v-for="user in users">
			<th scope="row">{{ user.id }}</th>
			<td>{{ user.name }}</td>
			<td>{{ user.email }}</td>
			<td class="text-right">
				<div class="btn-group">
					<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown"
					        aria-haspopup="true" aria-expanded="false">
						Actions <span class="caret"></span>
					</button>
					<ul class="dropdown-menu">
						<li>
							<a href="/users/{{ user.id }}/timesheets">Time Sheets</a>
						</li>
						<li role="separator" class="divider"></li>
						<li>
							<a href="#" @click.prevent="showUpdateUserModal(user)">Update</a>
						</li>
						<li><a href="#" @click.prevent="removeUser(user)">Remove</a></li>
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
		v-if="users"
	>
	</pagination>

	<modal-create-or-update-user :type="modal.type" :title="modal.title"></modal-create-or-update-user>

</template>