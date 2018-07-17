<template>
	<div>
		<section class="hero is-danger is-fullheight">
			<div class="hero-body">
				<div class="container has-text-centered">
					<h1 class="title">
						Selamat Datang di Website SEHAT YUK
					</h1>

					<p class="subtitle">
						Apa yang ingin Anda lihat?
					</p>


					<div class="columns is-mobile">
						<div class="column is-6 is-offset-3">
							<div class="columns">
								<div class="column">
									<router-link class="button is-danger is-inverted is-fullwidth" to="puskesmas">
										Puskesmas
									</router-link>
								</div>

								<div class="column">
									<router-link class="button is-danger is-inverted is-fullwidth" to="rsk">
										RS Khusus
									</router-link>
								</div>

								<div class="column">
									<router-link class="button is-danger is-inverted is-fullwidth" to="rsu">
										RS Umum
									</router-link>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>
	</div>
</template>

<script>
export default {
	name: 'Home',
	metaInfo: {
		title: 'Selamat Datang di Sehat Yuk'
	},
}
</script>
