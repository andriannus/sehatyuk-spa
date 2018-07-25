<template>
	<div id="puskesmas">
		<!-- Tampilan Loading -->
		<b-loading :is-full-page="isFullPage" :active="loading"></b-loading>
		<!-- Akhir Tampilan Loading -->

		<!-- Header -->
		<section class="hero is-success is-bold m-t-52">
			<div class="hero-body">
				<div class="container">
					<h1 class="title">
						Daftar Puskesmas
					</h1>

					<p class="subtitle" v-if="!loading">Ada {{ count }} Puskesmas di DKI Jakarta</p>
				</div>
			</div>
		</section>
		<!-- Akhir Header -->

		<section class="section" v-if="!loading">
			<div class="columns is-mobile">
				<div class="column is-10 is-offset-1">

					<!-- Pencarian -->
					<div class="field">
						<div class="control has-icons-right">
							<input class="input" type="text" v-model="query" placeholder="Cari nama puskesmas disini..." @input="fetchData()">
							<span class="icon is-small is-right">
								<i class="fas fa-search"></i>
							</span>
						</div>
					</div>
					<!-- Akhir Pencarian -->

					<hr>

					<!-- Puskesmas Tidak Ditemukan -->
					<div class="box has-text-centered" v-if="!found">
						<p class="title">
							<i class="fas fa-sad-tear fa-2x"></i>
						</p>
						<p class="subtitle">
							Puskesmas <strong>{{ query }}</strong> tidak ditemukan
						</p>
					</div>
					<!-- Akhir Puskesmas Tidak Ditemukan -->

					<!-- Collapse Data Puskesmas -->
					<b-collapse
						class="card"
						:open="isOpen"
						v-for="(puskesmas, index) in pagPuskesmases.data"
						:key="puskesmas.id"
						v-if="!loading && newPuskesmases && found"
					>
						<div slot="trigger" slot-scope="props" class="card-header">
							<p class="card-header-title">
								{{ puskesmas.nama_Puskesmas }}
							</p>
							<a class="card-header-icon">
								<b-icon
									class="has-text-success"
									pack="fas"
									:icon="props.open ? 'caret-down' : 'caret-up'"
								></b-icon>
							</a>
						</div>

						<div class="card-content">
							<div class="field">
								<label class="label">Alamat</label>
								<div class="control">
									<span>
										<button class="button is-success" @click="showMap(index)">
											<span>Tampilkan Peta</span>
											<span class="icon">
												<i class="fas fa-eye"></i>
											</span>
										</button>
									</span>
								</div>
							</div>

							<div class="field">
								<label class="label">Kepala Puskesmas</label>
								<div class="control">
									<span>{{ puskesmas.kepala_puskesmas }}</span>
								</div>
							</div>

							<div class="field">
								<label class="label">Telepon</label>
								<div class="control">
									<span v-if="puskesmas.telepon[0] === ''">-</span>

									<div class="tags" v-if="puskesmas.telepon[0] != ''">
										<span class="tag is-medium is-success is-rounded" v-for="(t, index) in puskesmas.telepon">{{ t }}</span>
									</div>
								</div>
							</div>

							<div class="field">
								<label class="label">Fax</label>
								<div class="control">
									<span v-if="puskesmas.faximile[0] === ''">-</span>
									
									<div class="tags" v-if="puskesmas.faximile[0] != ''">
										<span class="tag is-medium is-success is-rounded" v-for="(f, index) in puskesmas.faximile">{{ f }}</span>
									</div>
								</div>
							</div>

							<div class="field">
								<label class="label">Email</label>
								<div class="control">
									<span v-if="!puskesmas.email">-</span>
									
									<span v-if="puskesmas.email">{{ puskesmas.email }}</span>
								</div>
							</div>
						</div>
					</b-collapse>
					<!-- Akhir Collapse Data Puskesmas -->

					<!-- Pagination -->
					<div class="has-text-centered m-t-10" v-if="nextPage">
						<button class="button is-success" @click="fetchData(nextPage)">Lebih Banyak</button>
					</div>
					<!-- Akhir Pagination -->
				</div>
			</div>
		</section>

		<div class="modal maps" :class="{ 'is-active': isActive }" v-if="placeId">
			<div class="modal-background"></div>
			<div class="modal-card">
				 <section class="modal-card-body maps-card">
				 	<iframe
				 		width="100%" height="100%" frameborder="0" style="border:0"
				 		:src="'https://www.google.com/maps/embed/v1/place?q=place_id:' + placeId + '&key=AIzaSyBru3kP1mWUB_iF2okk_H3bDH3-ImcG4hc'"
				 		allowfullscreen
				 	></iframe>
				 </section>
			</div>
			<button class="modal-close is-large" aria-label="close" @click="reset"></button>
		</div>

		<back-to-top visibleoffset="800" bottom="50px" right="50px" v-if="!isActive">
			<button class="button is-dark is-large">
				<i class="fas fa-chevron-circle-up"></i>
			</button>
		</back-to-top>
	</div>
</template>

<script>
export default {
	name: 'Puskesmas',
	metaInfo: {
		title: 'Daftar Puskesmas | Sehat Yuk'
	},

	data: () => ({
		puskesmases: [],
		newPuskesmases: [],
		pagPuskesmases: [],
		puskesmas: {},
		placeId: '',
		nextPage: '',
		count: '',
		query: '',
		found: '',
		isOpen: false,
		isActive: false,
		isFullPage: true,
		loading: true,
		visibleInfoWindow: 'none'
	}),

	mounted() {
		this.getData()
	},

	methods: {
		// Mengambil data seluruh puskesmas dari cURL
		getData () {
			this.axios.get('getPuskesmas')
				.then(res => {
					this.puskesmases = res.data.data
					this.count = res.data.data.length
					this.fetchData()
					this.loading = false
				})

				.catch(err => {
					alert('Terjadi error. Silahkan refresh halaman atau coba lagi nanti.')
				})
		},

		// Membuat array baru untuk membuat fitur 'cari'
		fetchData (page) {
			this.loading = true

			this.newPuskesmases = []
			let query = this.query.toLowerCase()
			this.puskesmases.map((puskesmas) => {
				if (puskesmas.nama_Puskesmas.toLowerCase().indexOf(query) !== -1) {
					this.newPuskesmases.push(puskesmas)
				}
			})

			if (this.newPuskesmases.length < 1) {
				this.found = false

			} else {
				this.found = true
			}

			this.loading = false

			if (page) {
				let res = this.paginator(this.newPuskesmases, page, 5)
				this.pagPuskesmases.data.push(...res.data)
				this.nextPage = res.nextPage

			} else {
				this.pagPuskesmases = this.paginator(this.newPuskesmases, 1, 5)
				this.nextPage = this.pagPuskesmases.nextPage
			}
		},

		// Membuat Pagination
		paginator (arr, page, perPage)
		{
			const offset = (page - 1) * perPage
			const totalPage = Math.ceil(arr.length / perPage)
			const data = arr.slice(offset, offset + perPage)

			return {
				page: page,
				perPage: perPage,
				prevPage: page - 1 ? page - 1 : null,
				nextPage: (totalPage > page) ? page + 1 : null,
				total: arr.length,
				totalPage: totalPage,
				data: data
			}
		},

		// Menampilkan Peta
		showMap (index) {
			const app = this.$parent.$children[1]
			const geocoder = new google.maps.Geocoder
			
			const lat = this.puskesmases[index].location.latitude
			const lng = this.puskesmases[index].location.longitude

			const latLng = {
				lat: lat,
				lng: lng
			}

			geocoder.geocode({ 'location': latLng }, function (results, status) {
				if (status !== 'OK') {
					alert('Terjadi error. Silahkan refresh halaman')
				}

				app.placeId = results[0].place_id
				app.isActive = true
			})

			this.$snackbar.open({
				type: 'is-danger',
				message: 'Lokasi peta mungkin tidak akurat. Silahkan cek kembali',
				duration: 5000
			})
		},

		reset () {
			this.placeId = ''
			this.isActive = false
		}
	}
}
</script>

<style>
#puskesmases {
	min-height: 92vh;
}

.m-t-52 {
	margin-top: 52px;
}

.m-t-10 {
	margin-top: 10px;
}

.maps {
	z-index: 1000;
}

.maps-card {
	height: 75vh;
	overflow: hidden;
}
</style>
