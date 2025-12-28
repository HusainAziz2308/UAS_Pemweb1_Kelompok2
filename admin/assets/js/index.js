// pesan.php
const inputJumlah = document.getElementById('jumlah');
const totalView = document.getElementById('total-view');
const hargaSatuan = <?= $kopi['harga']; ?>;

inputJumlah.addEventListener('input', function () {
    let total = this.value * hargaSatuan;
    if (total < 0) total = 0;
    totalView.innerText = 'Rp ' + total.toLocaleString('id-ID');
});