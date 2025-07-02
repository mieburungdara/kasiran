<!-- Konten utama dari view spesifik berakhir di sini -->
        </div> <!-- End .container-fluid di dalam content-wrapper -->
    </div> <!-- End .content-wrapper yang dibuka di sidebar -->
</div> <!-- End .main-wrapper yang dibuka di header -->

<footer class="footer mt-auto py-3 bg-light">
  <div class="container text-center">
    <span class="text-muted">Aplikasi Kasir &copy; <?php echo date('Y'); ?> - Dibuat dengan CodeIgniter 3</span>
  </div>
</footer>

<!-- Load jQuery -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<!-- Load jQuery UI (untuk autocomplete di kasir) -->
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
<!-- Load Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<!-- Load Chart.js (jika digunakan di dashboard) -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<!-- Custom script (opsional) -->
<script src="<?php echo base_url('assets/js/script.js'); ?>"></script>

<script>
    $(document).ready(function () {
        // Inisialisasi tooltip Bootstrap
        $('[data-toggle="tooltip"]').tooltip();

        // Hapus alert secara otomatis setelah beberapa detik
        window.setTimeout(function() {
            $(".alert").fadeTo(500, 0).slideUp(500, function(){
                $(this).remove();
            });
        }, 5000); // 5 detik
    });
</script>

</body>
</html>
