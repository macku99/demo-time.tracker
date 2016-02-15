var Form = (formData) => {
	return {
		data: formData,
		fullErrors: {},
		errors: [],
		busy: false,
		successful: false,

		/**
		 * Send the form to the back-end server.
		 * This function will automatically clear old errors, update "busy" status, etc.
		 */
		send(sendFormPromise) {
			return new Promise((resolve, reject) => {
				this.startProcessing();

				sendFormPromise.then(
					(response) => {
						this.finishProcessing();

						resolve(response);
					},
					(errors) => {
						this.fullErrors = errors;
						this.setErrors(errors);
						this.busy = false;

						reject(errors);
					}
				);
			});
		},

		startProcessing() {
			this.fullErrors = {};
			this.errors = [];
			this.busy = true;
			this.successful = false;
		},

		finishProcessing() {
			this.busy = false;
			this.successful = true;
		},

		setErrors(errors) {
			if (typeof errors === 'object') {
				this.errors = _.flatten(_.toArray(errors));
			} else {
				this.errors.push('Something went wrong. Please try again.');
			}
		},

		hasError(field) {
			return _.indexOf(_.keys(this.fullErrors), field) > -1;
		},

		getError(field) {
			if (this.hasError(field)) {
				return _.first(this.fullErrors[field]);
			}
		}
	};
};

module.exports = Form;