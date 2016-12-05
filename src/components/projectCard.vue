<template>

	<div class="project-card"
		 :class="[ { 'project-card_active' : active }, { 'project-card_hidden' : hidden } ]">
		<router-link 
			:to="project.path"
			@mouseover.native="over()"
			@mouseleave.native="leave()"
			@click.native="open()"
			class="project-card__link" 
			>
			<img :src="project.imageSrc" class="project-card__img" alt="" />
			<div class="project-card__over"
				:style="[
					{ 'color': project.colorPrimary },
					{ 'border-color': project.colorPrimary },
					{ 'background-color': project.colorSecondary }
				]"> 
				<h3 class="project-card__title">{{ project.title }}</h3>
			</div>
		</router-link>
	</div>

</template>

<script>

	export default {
		name: 'projectCard',
		props: {
			project: Object,
			active: Boolean,
			hidden: Boolean
		},
		methods: {
			over () {
				this.$emit('over', this)
			},
			leave () {
				this.$emit('leave')
			},
			open () {
			}
		},
		events: {

		}
	}

</script>

<style lang="sass">

	@import "../sass/variables.sass"

	.project-card  
		position: relative
		width: 100%
		margin-right: 20px
		margin-bottom: 20px


	.project-card__link
		transition: border-color $speed, color $speed, opacity $speed

	.project-card__img
		max-width: 100%
		display: block

	.project-card__over  
		position: absolute
		border: solid 1px 
		width: 100%
		height: 100%
		z-index: 1
		top: 0
		left: 0
		padding: 20px
		opacity: 0
		transition: opacity $speed * 3
		display: flex
		align-items: flex-end

	.project-card__title
		margin: 0
		font-weight: normal

	.project-card_active
		padding: 0
		.project-card__over
			opacity: 1

	.project-card_hidden
		opacity: 0

	@media (min-width: $tablet-min)

		.project-card
			width: calc(50% - 20px)
			&:nth-child(even)
				margin-right: 0


</style>

