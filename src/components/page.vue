<template>
	<div class="page"
		 style="{ color: colorPrimary }">
		<h2 class="page__title"
			:style="{ color: colorPrimary }">{{ title }}</h2>
		<div class="page__description" 
			 v-html="description"></div>
		
	</div>
</template>

<script>
	import Axios from 'axios/dist/axios.js' 

	export default {
		name: 'page',
		data () {
			return {
				title: '',
				description: '',
				colorPrimary: '#000',
				colorSecondary: '#fff'
			}
		},
		methods: {
			changeLinksColor (color) {
				this.$nextTick(() => {
					var links = document.querySelectorAll('.project a')
					for (let i = 0; i < links.length; i++) {
						links[i].style.color = color
					}					
				})
			}
		},
		beforeRouteEnter (to, from, next) {
			if (cache.get(to.path)) {
				next(vm => { 
					let post = cache.get(to.path)
					vm.title = post.title
					vm.description = post.description
					bus.$emit('setDefaultColors')
					bus.$emit('siteTitle', post.title)
				})
			} else {
				Axios.get(config.ajaxUrl, {
					params: {
						action: 'page',
						slug: to.params.slug
					}
				})
				.then(
					(response) => {
						next(vm => { 
							if (response.data.length > 0) {
								let post = response.data[0]
								cache.set(to.path, post)
								vm.title = post.title
								vm.description = post.description
								bus.$emit('setDefaultColors')
								bus.$emit('siteTitle', post.title)
							} else {
								next('/404')
							}
						})
					},
					(response) => {
						next('/404')
					}
				)		
			}
			
		}
	}
</script>


