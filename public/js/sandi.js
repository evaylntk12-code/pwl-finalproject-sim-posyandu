// Tombol mata: tampilkan atau sembunyikan isi kolom kata sandi
document.addEventListener('click', function (e) {
    var tombol = e.target.closest('.mata');
    if (!tombol) return;

    var input = tombol.parentElement.querySelector('input');
    var tampil = input.type === 'password';

    input.type = tampil ? 'text' : 'password';
    tombol.setAttribute('aria-pressed', tampil);
    tombol.setAttribute('aria-label', tampil ? 'Sembunyikan kata sandi' : 'Tampilkan kata sandi');
    tombol.querySelector('i').className = tampil ? 'fa-solid fa-eye-slash' : 'fa-solid fa-eye';
});