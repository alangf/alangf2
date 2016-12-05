<template>
	<div class="project-list">
		<projectCard v-for="project in projects" 
			:project="project"
			:active="activeProject === project"
			:hidden="activeProject !== false && activeProject !== project"
			@over="over(project)"
			@leave="leave()"
			>
		</projectCard>
	</div> 
</template>

<script>
	import Axios from 'axios/dist/axios.js' 
	import ProjectCard from '../components/projectCard.vue' 

	export default {
		name: 'projectList',
		data () {
			return {
				projects: [],
				activeProject: false
			}
		},
		components: {
			'projectCard' : ProjectCard 
		},
		beforeRouteEnter (to, from, next) {
			console.log(to.path)
			if (cache.get(to.path)) {
				let list = cache.get(to.path)
				bus.$emit('siteTitle', list.title)
				bus.$emit('setDefaultColors')
				next(vm => { 
					vm.projects = list.projects
				})
			} else {
				Axios.get(config.ajaxUrl, {
					params: {
						action: 'category',
						category: to.meta.category
					}
				})
				.then(
					(response) => {
						next(vm => { 
							cache.set(to.path, response.data)
							vm.projects = response.data.projects
							bus.$emit('siteTitle', response.data.title)
							bus.$emit('setDefaultColors')
						})
					},
					(response) => {
						next('/404')
					}
				)
			}
		},
		methods: {
			over (project) {
				this.activeProject = project
				bus.$emit('projectCardOver', project)
			},
			leave () {
				this.activeProject = false	
				bus.$emit('setDefaultColors')
			},
			openProject (project) {

			}
		}
	}
</script>

<style lang="sass">

	.project-list
		display: flex
		flex-wrap: wrap
				
</style>

