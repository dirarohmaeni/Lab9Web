<?php
// footer.php - harus menutup .page-box lalu menutup body/html
?>
    </div> <!-- end .page-box -->

    <footer class="app-footer">
      <div class="container">
        <small>Praktikum 9 - Modular • Universitas Pelita Bangsa &copy; <?= date('Y') ?></small>
      </div>
    </footer>

    <style>
    .app-footer {
        margin-top: 40px;
        padding: 16px;
        text-align: center;
        background: #e9f9ee;
        border-top: 1px solid #d3ecd8;
        color: #2d6b3a;
        font-size: 14px;
        border-radius: 10px;
    }

    /* Layout helper supaya footer tidak tertimpa oleh konten singkat */
    html, body { height: 100%; }
    </style>

  <!-- Bootstrap JS (bundle) -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
