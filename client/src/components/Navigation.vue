<template>
	<div>
		<nav class="navbar is-fixed-top is-success" role="navigation" aria-label="main navigation">
			<div class="navbar-brand">
				<a class="navbar-item">
					SEHAT YUK
				</a>

				<a role="button" class="navbar-burger has-text-white" :class="{ 'is-active': isActive }" aria-label="menu" aria-expanded="false" @click="switchMenu">
					<span aria-hidden="true"></span>
					<span aria-hidden="true"></span>
					<span aria-hidden="true"></span>
				</a>
			</div>

			<div class="navbar-menu" :class="{ 'is-active': isActive }">
				<div class="navbar-start">
					<div class="navbar-item">
						<div class="field is-grouped">
							<p class="control">
								<a class="button is-dark" href="https://github.com/andriannus/sehatyuk-spa" target="_blank">
									<span class="icon">
										<i class="fab fa-github"></i>
									</span>
									<span>Github</span>
								</a>
							</p>
						</div>
					</div>
				</div>

				<div class="navbar-end">
					<router-link class="navbar-item" to="/">Beranda</router-link>
					<router-link class="navbar-item" to="puskesmas">Puskesmas</router-link>
					<router-link class="navbar-item" to="rsk">Rumah Sakit Khusus</router-link>
					<router-link class="navbar-item" to="rsu">Rumah Sakit Umum</router-link>
				</div>
			</div>
		</nav>
	</div>
</template>

<script>
export default {
	data: () => ({
		isActive: false
	}),

	methods: {
		switchMenu () {
			this.isActive = !this.isActive
		}
	}
}
</script>