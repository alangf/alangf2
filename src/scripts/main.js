import Promise from 'promise-polyfill/promise.js'; 
import Vue from 'vue/dist/vue.common.js'   
import VueRouter from 'vue-router'   
import Axios from 'axios/dist/axios.js' 
import ProjectList from '../components/projectList.vue'    
import Project from '../components/project.vue'    
import Page from '../components/page.vue'    

Vue.use(VueRouter) 

// simple cache
cache = {
	store: {},
	get (key) {
		return (this.store.hasOwnProperty(key)) ? this.store[key] : false
	}, 
	set (key, val) {
		this.store[key] = val
	}
}

bus = new Vue({
	data: {
		cache: {}
	}
})
if (!window.Promise) {
	window.Promise = Promise;
}

var p404 = Vue.component('p404', { 
	template: `<div class="view-404">
		<h1>404</h1>
		<p><router-link to="/">Home</a></p>
		</div>`
})
 
const router = new VueRouter({
	mode: 'history',
	base: config.baseUrl,
	routes: [ 
		{
			name: 'project-list',
			path: '/',
			component: ProjectList,
			meta: {
				category: 'projects'
			}
		},
		{
			path: '/en',
			redirect: '/'
		},
		{
			path: '/projects',
			redirect: '/'
		},
		{
			name: 'project',
			path: '/projects/:slug',
			component: Project,
			alias: [
				'/en/projects/:slug', '/es/projects/:slug'
			]
		},
		{
			name: 'contact',
			path: '/contact',
			component: Page,
			alias: [
				'/en/contact', '/es/contacto'
			]
		},
		{
			name: '404',
			path: '*',
			component: p404,
			alias: '/404'
		},
	]
})

new Vue({
	router,
	el: '#app',
	components: {
		'projectList': ProjectList,
		'project': Project,
		'contact': Page,
		'p404': p404
	},
	data: {
		colorPrimary: '#000',
		colorSecondary: '#fff',
		activeProject: false,
		lang: 'es'
	},
	methods: {
		changeColors (primary, secondary) {
			document.body.style.backgroundColor = secondary
			document.body.style.color = primary
			var sidebarLinks = document.querySelectorAll('.sidebar a')
			for (i = 0; i < sidebarLinks.length; i++) {
				sidebarLinks[i].style.color = primary
			}
		}
	},
	created ()  {
		this.lang = config.lang

		bus.$on('projectCardOver', (project) =>  { 
			this.changeColors(project.colorPrimary, project.colorSecondary)
		})

		bus.$on('setDefaultColors', () => { 
			this.changeColors(this.colorPrimary, this.colorSecondary)
		})

		bus.$on('siteTitle', (title) => {
			document.title = title + ' - ' + config.siteTitle
		})
	}
})