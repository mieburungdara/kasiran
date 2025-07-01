// Custom JavaScript can be added here

$(document).ready(function() {
    // Contoh: Sidebar toggle (jika Anda ingin sidebar bisa di-minimize)
    // $('#sidebarCollapse').on('click', function () {
    //     $('.sidebar').toggleClass('active');
    // });

    // Script untuk autocomplete pencarian produk di halaman kasir
    if ($("#search_produk_kasir").length) {
        $("#search_produk_kasir").autocomplete({
            source: function(request, response) {
                $.ajax({
                    url: "<?php echo base_url('kasir/transaksi/cari_produk'); ?>",
                    type: 'GET',
                    dataType: "json",
                    data: {
                        term: request.term
                    },
                    success: function(data) {
                        response(data);
                    }
                });
            },
            select: function(event, ui) {
                event.preventDefault(); // Mencegah nilai default (nama produk) masuk ke input
                $('#search_produk_kasir').val(''); // Kosongkan input setelah dipilih

                // Tambahkan produk ke form untuk ditambahkan ke keranjang
                // Ini bisa berupa pengisian form tersembunyi atau langsung trigger submit
                // Untuk contoh ini, kita isi form:
                $('#produk_id_kasir').val(ui.item.id);
                $('#nama_produk_display_kasir').val(ui.item.nama_produk); // Input display only
                $('#harga_produk_display_kasir').val(ui.item.harga); // Input display only
                $('#stok_produk_display_kasir').val(ui.item.stok); // Input display only
                $('#jumlah_kasir').val(1).focus(); // Set jumlah default 1 dan fokus

                // Atau bisa langsung tambahkan ke keranjang jika strukturnya mendukung
                // $('#form_tambah_keranjang_kasir').submit();
            },
            minLength: 1, // Minimal karakter sebelum pencarian dimulai
            messages: {
                noResults: '',
                results: function() {}
            }
        }).data("ui-autocomplete")._renderItem = function(ul, item) {
            // Custom rendering untuk menampilkan info tambahan (misal: harga, stok)
            return $("<li>")
                .append("<div>" + item.label + "<br><small>Harga: Rp " + parseFloat(item.harga).toLocaleString('id-ID') + "</small></div>")
                .appendTo(ul);
        };
    }


    // Fungsi untuk menghitung kembalian secara otomatis di form pembayaran
    if ($("#bayar_transaksi").length && $("#total_belanja_display").length) {
        function hitungKembalian() {
            var totalBelanjaText = $("#total_belanja_display").text().replace(/[^0-9]/g, '');
            var totalBelanja = parseFloat(totalBelanjaText) || 0;
            var bayar = parseFloat($("#bayar_transaksi").val().replace(/[^0-9]/g, '')) || 0;
            var kembalian = bayar - totalBelanja;

            if (kembalian >= 0) {
                $("#kembalian_transaksi_display").text("Rp " + kembalian.toLocaleString('id-ID'));
                $("#kembalian_transaksi_display").removeClass("text-danger").addClass("text-success");
            } else {
                $("#kembalian_transaksi_display").text("Rp " + kembalian.toLocaleString('id-ID') + " (Kurang)");
                $("#kembalian_transaksi_display").removeClass("text-success").addClass("text-danger");
            }
        }

        $("#bayar_transaksi").on("keyup input", function() {
            hitungKembalian();
        });

        // Panggil sekali saat load untuk inisialisasi jika ada nilai awal
        hitungKembalian();
    }

    // Print Struk
    $('#btn-print-struk').on('click', function() {
        window.print();
    });

});
```
