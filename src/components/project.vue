<template>
	<div class="project"
		 style="{ color: colorPrimary }">
		<h2 class="project__title"
			:style="{ color: colorPrimary }">{{ title }}</h2> 
			<a v-if="online" v-bind:href="url" target="_blank">view site</a><br>
		<div class="project__description" 
			 v-html="description"></div>

		<div class="browser" v-if="images.length > 0">
			<ul class="browser__list clearfix">
				<li v-for="(image, index) in images" class="browser__tab">
					<button @click="loadTab(image)" class="browser__button">{{ index + 1 }}</button>
				</li>
			</ul>
			<div class="browser__image" v-if="activeImage !== false">
				<img v-bind:src="activeImage" alt="">
			</div>
		</div>
		
	</div>
</template>

<script>
	import Axios from 'axios/dist/axios.js' 

	export default {
		name: 'project',
		data () {
			return {
				title: '',
				description: '',
				online: false,
				url: '',
				colorPrimary: '#000',
				colorSecondary: '#fff',
				images: [],
				activeImage: false
			}
		},
		watch: {
			images (val, old) {
				if (val.length > 0) {
					this.activeImage = this.images[0]
				}
			}
		},
		methods: {
			changeLinksColor (color) {
				this.$nextTick(() => {
					var links = document.querySelectorAll('.project a')
					for (let i = 0; i < links.length; i++) {
						links[i].style.color = color
					}					
					var links = document.querySelectorAll('.browser__button')
					for (let i = 0; i < links.length; i++) {
						links[i].style.color = color
						links[i].style.borderColor = color
					}					
				})
			},
			loadTab (image) {
				this.activeImage = image
			}
		},
		beforeRouteEnter (to, from, next) {
			if (cache.get(to.path)) {
				next(vm => { 
					let post = cache.get(to.path)
					vm.title = post.title
					vm.description = post.description
					vm.online = post.online
					vm.url = post.url
					vm.colorPrimary = post.colorPrimary
					vm.colorSecundary = post.colorSecundary
					vm.images = post.images
					vm.changeLinksColor(post.colorPrimary)
					bus.$emit('projectCardOver', post)
					bus.$emit('siteTitle', post.title)
				})
			} else {
				Axios.get(config.ajaxUrl, {
					params: {
						action: 'project',
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
								vm.online = post.online
								vm.url = post.url
								vm.colorPrimary = post.colorPrimary
								vm.colorSecundary = post.colorSecundary
								vm.images = post.images
								vm.changeLinksColor(post.colorPrimary)
								bus.$emit('projectCardOver', post)
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

<style lang="sass">

	@import "../sass/variables.sass"

	.project__title
		margin-bottom: 10px
		display: inline-block
		font-weight: normal

	.project__description a
		text-decoration: underline

	.browser__tab
		float: left;

	.browser__button
		background: transparent
		border: solid 1px transparent
		outline: none

	.browser__tab:not(:last-child) .browser__button
		border-right-width: 0

	.browser__image
		display: block
		margin-top: 20px


</style>

