# Sehat Yuk
> Temukan instansi kesehatan di daerah DKI Jakarta (SPA)

## Apa yang Berbeda?
1. Single Page Application!

## Alat yang Digunakan
1. PHP - CodeIgniter `3.1.9`
2. CSS - Buefy `Bulma for Vue.js`
3. Font - Font Awesome `5.1.0`
4. JavaScript - Vue.js `2.5.2`
5. API dari Jakarta Smart City `Data Rumah Sakit Umum`, `Data Rumah Sakit Khusus`, dan `Data Puskesmas`

## Yang Diperlukan
1. Node.js dan npm

## Cara Pemasangan
1. Kloning atau Unduh Proyek ini
2. Jika Unduh, ubah nama Proyek ke `sehatyuk-spa` dan pindahkan ke `xampp/htdocs`, `var/www/html` atau sejenisnya
3. Buka berkas `application/controllers/Api.php`
4. Ubah `API_KEY` menjadi `API_KEY Milik Anda`

## Mode Development
1. Buka direktori `client` dan install package.json
```
# sehatyuk-spa/client

npm install
npm run dev
```
2. Buka file `index.js` pada direktori `src/router`
3. Hapus `base`
4. Silahkan Kunjungi `http://localhost:8080/`

## Mode Production (Default)
1. Buka direktori `client` dan install package.json
```
# sehatyuk-spa/client

npm install
npm run build
```
2. Silahkan Kunjungi `http://localhost/sehatyuk-spa/`

## Cara Mendapatkan API_KEY
Silahkan Kunjungi [Jakarta Smart City](http://api.jakarta.go.id/)

## Lisensi
[MIT](https://github.com/andriannus/sehatyuk-spa/blob/master/LICENSE)