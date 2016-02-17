var Helpers = {
	success(message) {
		sweetalert({
			title: 'Congratulations!',
			text: message,
			type: 'success',
			timer: 2000,
			showConfirmButton: false,
			allowOutsideClick: true
		});
	},

	mixins: {
	}
};

module.exports = Helpers;