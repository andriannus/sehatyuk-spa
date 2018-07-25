<template>
	<div>
		<Navigation />

		<router-view />
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
