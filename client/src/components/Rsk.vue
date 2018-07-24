<template>
	<div>
		<!-- Tampilan Loading -->
		<b-loading :is-full-page="isFullPage" :active="loading"></b-loading>
		<!-- Akhir Tampilan Loading -->

		<!-- Header -->
		<section class="hero is-success is-bold m-t-52">
			<div class="hero-body">
				<div class="container">
					<h1 class="title">
						Daftar Rumah Sakit Khusus
					</h1>

					<p class="subtitle" v-if="!loading">Ada {{ count }} RS Khusus di DKI Jakarta</p>
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
							<input class="input" type="text" v-model="query" placeholder="Cari nama RS disini..." @input="fetchData()">
							<span class="icon is-small is-right">
								<i class="fas fa-search"></i>
							</span>
						</div>
					</div>
					<!-- Akhir Pencarian -->

					<hr>

					<!-- RS Khusus Tidak Ditemukan -->
					<div class="box has-text-centered" v-if="!found">
						<p class="title">
							<i class="fas fa-sad-tear fa-2x"></i>
						</p>
						<p class="subtitle">
							RS <strong>{{ query }}</strong> tidak ditemukan
						</p>
					</div>
					<!-- Akhir RS Khusus Tidak Ditemukan -->

					<!-- Collapse Data RSK -->
					<b-collapse
						class="card"
						:open="isOpen"
						v-for="(rsk, index) in pagRsks.data"
						:key="rsk.id"
						v-if="!loading && newRsks && found"
					>
						<div slot="trigger" slot-scope="props" class="card-header">
							<p class="card-header-title">
								RS {{ rsk.nama_rsk }}
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
								<label class="label">Jenis RS Khusus</label>
								<div class="control">
									<span>{{ rsk.jenis_rsk }}</span>
								</div>
							</div>

							<div class="field">
								<label class="label">Alamat</label>
								<div class="control">
									<p>{{ rsk.location.alamat }}</p>
									<span>
										<button class="button is-success" @click="showMap(index)">Tampilkan Peta</button>
									</span>
								</div>
							</div>

							<div class="field">
								<label class="label">Website</label>
								<div class="control">
									<span v-if="!rsk.website">-</span>
									<a :href="'http://' + rsk.website" class="has-text-success" v-if="rsk.website" target="_blank">{{ rsk.website }}</a>
								</div>
							</div>

							<div class="field">
								<label class="label">Telepon</label>
								<div class="control">
									<span v-if="rsk.telepon[0] === ''">-</span>

									<div class="tags" v-if="rsk.telepon[0] != ''">
										<span class="tag is-medium is-success is-rounded" v-for="(t, index) in rsk.telepon">{{ t }}</span>
									</div>
								</div>
							</div>

							<div class="field">
								<label class="label">Fax</label>
								<div class="control">
									<span v-if="rsk.faximile[0] === ''">-</span>
									
									<div class="tags" v-if="rsk.faximile[0] != ''">
										<span class="tag is-medium is-success is-rounded" v-for="(f, index) in rsk.faximile">{{ f }}</span>
									</div>
								</div>
							</div>

							<div class="field">
								<label class="label">Email</label>
								<div class="control">
									<span v-if="!rsk.email">-</span>

									<span v-if="rsk.email">{{ rsk.email }}</span>
								</div>
							</div>
						</div>
					</b-collapse>
					<!-- Akhir Collapse Data RSK -->

					<!-- Pagination -->
					<div class="has-text-centered m-t-10" v-if="nextPage">
						<button class="button is-success" @click="fetchData(nextPage)">Lebih Banyak</button>
					</div>
					<!-- Akhir Pagination -->
				</div>
			</div>
		</section>

		<div class="modal maps" :class="{ 'is-active': isActive }">
			<div class="modal-background"></div>
			<div class="modal-card">
				 <section class="modal-card-body maps-card">
				 	<div ref="map" style="height: 100%"></div>
				 </section>
			</div>
			<button class="modal-close is-large" aria-label="close" @click="isActive = false"></button>
		</div>
	</div>
</template>

<script>
export default {
	name: 'Rsk',
	metaInfo: {
		title: 'Daftar Rumah Sakit Khusus | Sehat Yuk'
	},

	data: () => ({
		rsks: [],
		newRsks: [],
		pagRsks: [],
		rsk: {},
		nextPage: '',
		count: '',
		query: '',
		found: '',
		isOpen: false,
		isActive: false,
		isFullPage: true,
		loading: true
	}),

	mounted() {
		this.getData()
	},

	methods: {
		// Mengambi data seluruh RSK dari cURL
		getData () {
			this.axios.get('getRumahSakitKhusus')
				.then(res => {
					this.rsks = res.data.data
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

			this.newRsks = []
			let query = this.query.toLowerCase()
			this.rsks.map((rsk) => {
				if (rsk.nama_rsk.toLowerCase().indexOf(query) !== -1) {
					this.newRsks.push(rsk)
				}
			})

			if (this.newRsks.length < 1) {
				this.found = false

			} else {
				this.found = true
			}

			this.loading = false

			if (page) {
				let res = this.paginator(this.newRsks, page, 5)
				this.pagRsks.data.push(...res.data)
				this.nextPage = res.nextPage

			} else {
				this.pagRsks = this.paginator(this.newRsks, 1, 5)
				this.nextPage = this.pagRsks.nextPage
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
			const lat = this.rsks[index].location.latitude
			const lng = this.rsks[index].location.longitude

			const latLng = new google.maps.LatLng(lat, lng)

			const map = new google.maps.Map(this.$refs.map, {
				center: latLng,
				zoom: 19,
				disableDefaultUI: true
			});

			const marker = new google.maps.Marker({
				position: latLng,
				map: map
			})

			this.isActive = true
		}
	}
}
</script>

<style>
.m-t-52 {
	margin-top: 52px;
}

.m-t-10 {
	margin-top: 10px;
}

.maps {
	z-index: 1010;
}

.maps-card {
	height: 75vh;
}
</style>
