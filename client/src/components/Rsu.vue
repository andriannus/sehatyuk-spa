<template>
	<div>
		<!-- Header -->
		<section class="hero is-danger is-bold m-t-52">
			<div class="hero-body">
				<div class="container">
					<h1 class="title">
						Daftar Rumah Sakit Umum
					</h1>

					<!-- Memuat Total RS Umum -->
					<p class="subtitle" v-if="loading">
						<i class="fas fa-spin fa-spinner title"></i>
					</p>
					<!-- Akhir Memuat Total RS Umum -->

					<p class="subtitle" v-if="!loading">Ada {{ count }} RS Umum di DKI Jakarta</p>
				</div>
			</div>
		</section>
		<!-- Akhir Header -->

		<!-- Memuat Data RS Umum -->
		<section class="section has-text-centered" v-if="loading">
			<i class="fas fa-spin fa-spinner title"></i>
			<p class="subtitle">Memuat data...</p>
		</section>
		<!-- Akhir Memuat Data RS Umum -->

		<section class="section" v-if="!loading">
			<div class="columns is-mobile">
				<div class="column is-10 is-offset-1">

					<!-- Pencarian -->
					<div class="field">
						<div class="control has-icons-right">
							<input class="input" type="text" v-model="query" placeholder="Cari nama RS disini..." @input="fetchData">
							<span class="icon is-small is-right">
								<i class="fas fa-search"></i>
							</span>
						</div>
					</div>
					<!-- Akhir Pencarian -->

					<hr>

					<!-- RS Umum Tidak Ditemukan -->
					<div class="box has-text-centered" v-if="!found">
						<p class="title">
							<i class="fas fa-sad-tear fa-2x"></i>
						</p>
						<p class="subtitle">
							RS <strong>{{ query }}</strong> tidak ditemukan
						</p>
					</div>
					<!-- Akhir RS Umum Tidak Ditemukan -->

					<!-- Collapse Data RSU -->
					<b-collapse
						class="card"
						:open="isOpen"
						v-for="(rsu, index) in pagRsus.data"
						:key="rsu.id"
						v-if="!loading && newRsus && found"
					>
						<div slot="trigger" slot-scope="props" class="card-header">
							<p class="card-header-title">
								RS {{ rsu.nama_rsu }}
							</p>
							<a class="card-header-icon">
								<b-icon
									class="has-text-danger"
									pack="fas"
									:icon="props.open ? 'caret-down' : 'caret-up'"
								></b-icon>
							</a>
						</div>

						<div class="card-content">
							<div class="field">
								<label class="label">Jenis RS Umum</label>
								<div class="control">
									<span>{{ rsu.jenis_rsu }}</span>
								</div>
							</div>

							<div class="field">
								<label class="label">Alamat</label>
								<div class="control">
									<p>{{ rsu.location.alamat }}</p>
									<span>
										<button class="button is-danger" @click="showMap">Lihat Peta</button>
									</span>
								</div>
							</div>

							<div class="field">
								<label class="label">Website</label>
								<div class="control">
									<span v-if="!rsu.website">-</span>
									<a :href="'http://' + rsu.website" class="has-text-link" v-if="rsu.website" target="_blank">{{ rsu.website }}</a>
								</div>
							</div>

							<div class="field">
								<label class="label">Telepon</label>
								<div class="control">
									<span v-if="rsu.telepon[0] === ''">-</span>

									<div class="tags" v-if="rsu.telepon[0] != ''">
										<span class="tag is-medium is-danger is-rounded" v-for="(t, index) in rsu.telepon">{{ t }}</span>
									</div>
								</div>
							</div>

							<div class="field">
								<label class="label">Fax</label>
								<div class="control">
									<span v-if="rsu.faximile[0] === ''">-</span>
									
									<div class="tags" v-if="rsu.faximile[0] != ''">
										<span class="tag is-medium is-danger is-rounded" v-for="(f, index) in rsu.faximile">{{ f }}</span>
									</div>
								</div>
							</div>

							<div class="field">
								<label class="label">Email</label>
								<div class="control">
									<span v-if="!rsu.email">-</span>

									<span v-if="rsu.email">{{ rsu.email }}</span>
								</div>
							</div>
						</div>
					</b-collapse>
					<!-- Akhir Collapse Data RSU -->

					<!-- Pagination -->
					<div class="has-text-centered m-t-10" v-if="nextPage">
						<button class="button is-danger" @click="fetchData(nextPage)">Lebih Banyak</button>
					</div>
					<!-- Akhir Pagination -->
				</div>
			</div>
		</section>
	</div>
</template>

<script>
export default {
	name: 'Rsu',
	metaInfo: {
		title: 'Daftar Rumah Sakit Umum | Sehat Yuk'
	},

	data: () => ({
		rsus: [],
		newRsus: [],
		pagRsus: [],
		rsu: {},
		nextPage: '',
		count: '',
		query: '',
		found: '',
		isOpen: false,
		loading: true
	}),

	mounted() {
		this.getData()
	},

	methods: {
		// Mengambil data seluruh RSU dari cURL
		getData () {
			this.axios.get('getRumahSakitUmum')
				.then(res => {
					this.rsus = res.data.data
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

			this.newRsus = []
			let query = this.query.toLowerCase()
			this.rsus.map((rsu) => {
				if (rsu.nama_rsu.toLowerCase().indexOf(query) !== -1) {
					this.newRsus.push(rsu)
				}
			})

			if (this.newRsus.length < 1) {
				this.found = false

			} else {
				this.found = true
			}

			this.loading = false

			if (page) {
				let res = this.paginator(this.newRsus, page, 5)
				this.pagRsus.data.push(...res.data)
				this.nextPage = res.nextPage

			} else {
				this.pagRsus = this.paginator(this.newRsus, 1, 5)
				this.nextPage = this.pagRsus.nextPage
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

		// Menampilkan Google Maps pada tab browser yang baru
		showMap () {
			let center = this.rsu.latitude + ',' + this.rsu.longitude
			let url = 'https://www.google.com/maps/search/?api=1&query=' + center
			window.open(url, '_blank')
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
</style>
