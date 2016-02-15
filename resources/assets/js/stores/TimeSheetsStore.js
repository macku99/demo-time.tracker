var TimeSheetsStore = {
	TIMESHEETS_LIST_RETRIEVED: 'TIMESHEETS_LIST_RETRIEVED',
	TIMESHEET_DETAILS_RETRIEVED: 'TIMESHEET_DETAILS_RETRIEVED',
	TIMESHEET_CREATED: 'TIMESHEET_CREATED',
	TIMESHEET_UPDATED: 'TIMESHEET_UPDATED',
	TIMESHEET_REMOVED: 'TIMESHEET_REMOVED',

	allOfUser(userId, page) {
		Vue.http.get('/api/users/' + userId + '/timesheets' + (page ? '?page=' + page : ''))
			.then(
				response => {
					when(this.TIMESHEETS_LIST_RETRIEVED)
						.broadcast(response.data);
				},
				response => {
					// handle error
					console.log(response.data);
				}
			);
	},

	find(userId, timeSheetId) {
		Vue.http.get('/api/users/' + userId + '/timesheets/' + timeSheetId)
			.then(
				response => {
					when(this.TIMESHEET_DETAILS_RETRIEVED)
						.broadcast(response.data);
				},
				response => {
					// handle error
					console.log(response.data);
				}
			);
	},

	storeTimeSheet(userId, data) {
		return new Promise((resolve, reject) => {
			Vue.http.post('/api/users/' + userId + '/timesheets', data)
				.then(
					response => {
						when(this.TIMESHEET_CREATED)
							.broadcast(response.data);

						resolve(response.data);
					},
					response => {
						reject(response.data);
					}
				);
		});
	},

	updateTimeSheet(userId, timesheet, data) {
		return new Promise((resolve, reject) => {
			Vue.http.put('/api/users/' + userId + '/timesheets/' + timesheet.id, data)
				.then(
					response => {
						when(this.TIMESHEET_UPDATED)
							.broadcast(response.data);

						resolve(response.data);
					},
					response => {
						reject(response.data);
					}
				);
		});
	},

	removeTimeSheet(userId, timesheet) {
		return new Promise((resolve, reject) => {
			Vue.http.delete('/api/users/' + userId + '/timesheets/' + timesheet.id)
				.then(
					response => {
						when(this.TIMESHEET_REMOVED)
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

module.exports = TimeSheetsStore;