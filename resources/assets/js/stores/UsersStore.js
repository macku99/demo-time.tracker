var UsersStore = {
	USERS_LIST_RETRIEVED: 'TIMESHEETS_LIST_RETRIEVED',
	USER_DETAILS_RETRIEVED: 'TIMESHEET_DETAILS_RETRIEVED',
	USER_CREATED: 'USER_CREATED',
	USER_UPDATED: 'USER_UPDATED',
	USER_REMOVED: 'USER_REMOVED',
	USER_PREFERENCES_UPDATED: 'USER_PREFERENCES_UPDATED',

	all(page) {
		Vue.http.get('/api/users' + (page ? '?page=' + page : ''))
			.then(
				response => {
					when(this.USERS_LIST_RETRIEVED)
						.broadcast(response.data);
				},
				response => {
					// handle error
					console.log(response.data);
				}
			);
	},

	find(userId) {
		Vue.http.get('/api/users/' + userId)
			.then(
				response => {
					when(this.USER_DETAILS_RETRIEVED)
						.broadcast(response.data);
				},
				response => {
					// handle error
					console.log(response.data);
				}
			);
	},

	storeUser(data) {
		return new Promise((resolve, reject) => {
			Vue.http.post('/api/users', data)
				.then(
					response => {
						when(this.USER_CREATED)
							.broadcast(response.data);

						resolve(response.data);
					},
					response => {
						reject(response.data);
					}
				);
		});
	},

	updateUser(user, data) {
		return new Promise((resolve, reject) => {
			Vue.http.put('/api/users/' + user.id, data)
				.then(
					response => {
						when(this.USER_UPDATED)
							.broadcast(response.data);

						resolve(response.data);
					},
					response => {
						reject(response.data);
					}
				);
		});
	},

	removeUser(user) {
		return new Promise((resolve, reject) => {
			Vue.http.delete('/api/users/' + user.id)
				.then(
					response => {
						when(this.USER_REMOVED)
							.broadcast(response.data);

						resolve(response.data);
					},
					response => {
						reject(response.data);
					}
				);
		});
	},

	updateUserPreferences(userId, data) {
		return new Promise((resolve, reject) => {
			Vue.http.put('/api/users/preferences/' + userId, data)
				.then(
					response => {
						when(this.USER_PREFERENCES_UPDATED)
							.broadcast(response.data);

						resolve(response.data);
					},
					response => {
						reject(response.data);
					}
				);
		});
	}

};

module.exports = UsersStore;