<script type="text/babel">
	export default {
		props: {
			show: {
				type: Boolean,
				required: true,
				default: false,
				twoWay: true
			},
			title: {
				type: String,
				default: ''
			}
		},

		ready() {
			$(this.$el).on('hidden.bs.modal', e => {
				this.show = false;
				this.$dispatch('hidden.modal');
			});
		},

		watch: {
			show(val) {
				$(this.$el).modal(val ? 'show' : 'hide');
			}
		},

		methods: {
			close() {
				this.show = false;
				this.$dispatch('hidden.modal');
			}
		}
	}
</script>


<template>
	<div class="modal fade" tabindex="-1" role="dialog">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" @click="close">
						<span aria-hidden="true">&times;</span><span class="sr-only">Close</span>
					</button>
					<h4 class="modal-title">{{ title }}</h4>
				</div>
				<div class="modal-body">
					<slot></slot>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" @click="close">CLOSE</button>
					<slot name="action">
						<button type="button" class="btn btn-info" @click="close">SUBMIT</button>
					</slot>
				</div>
			</div>
		</div>
	</div>
</template>