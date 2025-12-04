/* assets/js/main.js
   Fungsi:
   - konfirmasi hapus
   - preview gambar sebelum upload
   - validasi form sederhana
   - toggle menu sederhana (jika dibutuhkan)
*/

document.addEventListener('DOMContentLoaded', function () {

  // 1) Konfirmasi Hapus (gunakan class .confirm-delete pada link/button)
  document.querySelectorAll('.confirm-delete').forEach(function(el){
    el.addEventListener('click', function(e){
      const ok = confirm('Yakin ingin menghapus data ini?');
      if (!ok) e.preventDefault();
    });
  });

  // 2) Preview Gambar (file input memakai id="fileGambar" dan img preview id="previewImg")
  const fileInput = document.querySelector('#fileGambar');
  const previewImg = document.querySelector('#previewImg');
  if (fileInput && previewImg) {
    fileInput.addEventListener('change', function () {
      const f = this.files[0];
      if (!f) {
        previewImg.src = '';
        previewImg.style.display = 'none';
        return;
      }
      const allowed = ['image/png','image/jpeg','image/jpg','image/webp'];
      if (!allowed.includes(f.type)) {
        alert('Tipe file tidak diizinkan. Gunakan PNG/JPG/WEBP.');
        this.value = '';
        return;
      }
      const reader = new FileReader();
      reader.onload = function (evt) {
        previewImg.src = evt.target.result;
        previewImg.style.display = 'inline-block';
      };
      reader.readAsDataURL(f);
    });
  }

  // 3) Simple Form Validation (form with class .validate-form)
  document.querySelectorAll('.validate-form').forEach(function(form){
    form.addEventListener('submit', function(e){
      // find required fields
      let ok = true;
      form.querySelectorAll('[data-required]').forEach(function(inp){
        if (!inp.value || inp.value.trim() === '') {
          ok = false;
          inp.style.borderColor = '#ff6b6b';
          inp.scrollIntoView({behavior: 'smooth', block: 'center'});
        } else {
          inp.style.borderColor = '';
        }
      });
      if (!ok) {
        e.preventDefault();
        alert('Lengkapi field yang ditandai dulu.');
      }
    });
  });

  // 4) Toggle simple mobile menu (if any element .nav-toggle and .nav-menu)
  const toggle = document.querySelector('.nav-toggle');
  const menu = document.querySelector('.nav-menu');
  if (toggle && menu) {
    toggle.addEventListener('click', function(){
      menu.classList.toggle('open');
    });
  }

});
