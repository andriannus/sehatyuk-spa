<template>
	<div>
		<Navigation />

		<router-view />
		
		<back-to-top visibleoffset="800" bottom="50px" right="50px">
			<button class="button is-dark is-large">
				<i class="fas fa-chevron-circle-up"></i>
			</button>
		</back-to-top>
	</div>
</template>

<script>
import Navigation from './Navigation'

export default {
	name: 'Header',
	metaInfo: {
		title: 'Selamat Datang di Sehat Yuk'
	},

	components: {
		Navigation
	}
}
</script>
