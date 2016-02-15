<script type="text/babel">
	export default {
		props: {
			currentPage: {
				type: Number,
				required: true,
				twoWay: true,
				default: 1
			},
			totalItems: {
				type: Number,
				required: true
			},
			perPage: {
				type: Number,
				default: 20
			},
			// How many pages to display in the list before and after the current page
			displayRange: {
				type: Number,
				default: 3
			}
		},

		computed: {
			totalPages: function () {
				return Math.ceil(this.totalItems / this.perPage);
			},
			pages: function () {
				return this.paginate();
			}
		},

		methods: {
			paginate() {
				var threshold = 2 * this.displayRange + 3,
					lowerLimit = this.displayRange + 3,
					upperLimit = this.totalPages - this.displayRange - 2,
					displayFirst,
					displayLast,
					rangeLocation,
					pages = [];

				// We need leading and/or trailing `...` b/c there are too many pages to display all at once
				if (this.totalPages > threshold) {
					// We're at the lower end of the page range: `1,2,3,4,5,...`
					if (this.currentPage < lowerLimit) {
						rangeLocation = 'lower';
						displayFirst = 1;
						displayLast = threshold;

						// We're at the upper end of the page range: `...,24,25,26,27,28`
					} else if (upperLimit < this.currentPage) {
						rangeLocation = 'upper';
						displayFirst = this.totalPages - 2 * this.displayRange - 1;
						displayLast = this.totalPages + 1;

						// We're somewhere in the middle of the page range: `...,11,12,13,14,...`
					} else {
						rangeLocation = 'middle';
						displayFirst = this.currentPage - this.displayRange;
						displayLast = this.currentPage + this.displayRange + 1;
					}
				} else {
					rangeLocation = 'n/a';
					displayFirst = 1;
					displayLast = this.totalPages + 1;
				}

				// Leading `...`
				if ('middle' === rangeLocation || 'upper' === rangeLocation) {
					pages.push({label: '...', value: this.currentPage - this.displayRange - 2});
				}

				// Page list
				for (let pageNumber of _.range(displayFirst, displayLast)) {
					pages.push({label: pageNumber, value: pageNumber});
				}

				// Trailing `...`
				if ('lower' === rangeLocation || 'middle' === rangeLocation) {
					pages.push({label: '...', value: this.currentPage + this.displayRange + 2})
				}

				return pages;
			},

			setPage(page) {
				if (page == '' || page < 1 || page > this.totalPages) return;

				if (this.currentPage != page) {
					this.currentPage = page;
					this.$dispatch('pagination.page.changed', page)
				}
			}
		}
	}
</script>

<template>
	<nav class="text-center" v-if="this.totalPages > 1">
		<ul class="pagination">
			<li :class="{ disabled: currentPage === 1 }">
				<a href="#" aria-label="Previous" @click.prevent="setPage(currentPage - 1)">
					<span aria-hidden="true">&laquo;</span>
				</a>
			</li>
			<li
				v-for="page in pages"
				:class="{ active: page.value == currentPage }"
			>
				<a href="#" @click.prevent="setPage(page.value)">{{ page.label }}</a>
			</li>
			<li :class="{ disabled: currentPage === totalPages }">
				<a href="#" aria-label="Next" @click.prevent="setPage(currentPage + 1)">
					<span aria-hidden="true">&raquo;</span>
				</a>
			</li>
		</ul>
	</nav>
</template>