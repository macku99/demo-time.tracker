<script type="text/babel">
	export default {
		props: {
			form: {
				type: Object,
				required: true
			},
			attribute: {
				type: String,
				required: true
			}
		}
	}
</script>

<template>
	<span class="help-block" v-if="form.hasError(attribute)">
		<strong>{{ form.getError(attribute) }}</strong>
	</span>
</template>