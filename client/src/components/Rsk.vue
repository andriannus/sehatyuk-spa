<template>
	<div>
		<!-- Header -->
		<section class="hero is-danger is-bold m-t-52">
			<div class="hero-body">
				<div class="container">
					<h1 class="title">
						Daftar Rumah Sakit Khusus
					</h1>

					<!-- Memuat Total RS Khusus -->
					<p class="subtitle" v-if="loading">
						<i class="fas fa-spin fa-spinner title"></i>
					</p>
					<!-- Akhir Memuat Total RS Khusus -->

					<p class="subtitle" v-if="!loading">Ada {{ count }} RS Khusus di DKI Jakarta</p>
				</div>
			</div>
		</section>
		<!-- Akhir Header -->

		<!-- Memuat Data RS Khusus -->
		<section class="section has-text-centered" v-if="loading">
			<i class="fas fa-spin fa-spinner title"></i>
			<p class="subtitle">Memuat data...</p>
		</section>
		<!-- Akhir Memuat Data RS Khusus -->

		<section class="section" v-if="!loading">
			<div class="columns is-mobile">
				<div class="column is-10 is-offset-1">
					<div class="field">
						<div class="control has-icons-right">
							<input class="input" type="text" v-model="query" placeholder="Cari nama RS disini..." @input="fetchData">
							<span class="icon is-small is-right">
								<i class="fas fa-search"></i>
							</span>
						</div>
					</div>

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

					<article class="message is-danger" v-for="(rsk, index) in newRsks" v-if="!loading && found">
						<div class="message-header">
							<p>RS {{ rsk.nama_rsk }}</p>
						</div>
						<div class="message-body">
							<div class="field">
								<label class="label">Alamat</label>
								<div class="control">
									<span>{{ rsk.location.alamat }}</span>
								</div>
							</div>
							<div class="field">
								<div class="control">
									<button class="button is-danger" @click="getRsk(rsk.id)">Lihat</button>
								</div>
							</div>
						</div>
					</article>
				</div>
			</div>
		</section>

		<!-- Modal Detail RS Khusus -->
		<b-modal :active.sync="visibleModal" :width="640">
			<div class="card">
				<header class="card-header">
					<p class="card-header-title">Detail RS Khusus</p>
				</header>

				<!-- Memuat Detail RS Khusus -->
				<section class="card-content has-text-centered" v-if="loadingRsk">
					<i class="fas fa-spin fa-spinner title"></i>
					<p class="subtitle">Memuat data...</p>
				</section>
				<!-- Akhir Memuat Detail RS Khusus -->

				<section class="card-content" v-if="!loadingRsk">
					<div class="field">
						<label class="label">Nama RS Khusus</label>
						<div class="control">
							<p>RS {{ rsk.nama_rsk }}</p>
						</div>
					</div>

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
								<button class="button is-danger" @click="showMap">Lihat Peta</button>
							</span>
						</div>
					</div>

					<div class="field">
						<label class="label">Website</label>
						<div class="control">
							<span v-if="!rsk.website">-</span>
							<a :href="'http://' + rsk.website" class="has-text-link" v-if="rsk.website" target="_blank">{{ rsk.website }}</a>
						</div>
					</div>

					<div class="field">
						<label class="label">Telepon</label>
						<div class="control">
							<span v-if="rsk.telepon[0] === ''">-</span>

							<div class="tags" v-if="rsk.telepon[0] != ''">
								<span class="tag is-medium is-danger is-rounded" v-for="(t, index) in rsk.telepon">{{ t }}</span>
							</div>
						</div>
					</div>

					<div class="field">
						<label class="label">Fax</label>
						<div class="control">
							<span v-if="rsk.faximile[0] === ''">-</span>
							
							<div class="tags" v-if="rsk.faximile[0] != ''">
								<span class="tag is-medium is-danger is-rounded" v-for="(f, index) in rsk.faximile">{{ f }}</span>
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
				</section>
			</div>
		</b-modal>
		<!-- Akhir Modal Detail RS Khusus -->
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
		rsk: {},
		count: '',
		query: '',
		found: '',
		visibleModal: false,
		loading: true,
		loadingRsk: true
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
		fetchData () {
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
		},

		// Mengambil data 1 RSK dari cURL
		getRsk (id) {
			this.switchModal()

			this.axios.get('getRumahSakitKhusus?id=' + id)
				.then(res => {
					this.rsk = res.data.data[0]
					this.loadingRsk = false
				})

				.catch(err => {
					alert('Terjadi error. Silahkan refresh halaman atau coba lagi nanti.')
				})
		},

		// Menampilkan Google Maps pada tab browser yang baru
		showMap () {
			let center = this.rsk.latitude + ',' + this.rsk.longitude
			let url = 'https://www.google.com/maps/search/?api=1&query=' + center
			window.open(url, '_blank')
		},

		// Mengaktifkan atau menonaktifkan 'modal'
		switchModal () {
			this.visibleModal = !this.visibleModal
			this.loadingRsk = true
		}
	}
}
</script>

<style>
.m-t-52 {
	margin-top: 52px;
}
</style>