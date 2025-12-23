document.addEventListener("DOMContentLoaded", function () {
    // Fetch waste types from backend
    fetch('../api/get_jenis_sampah.php')
        .then(response => response.json())
        .then(data => {
            const sampahGrid = document.getElementById('sampahGrid');
            const loadingMessage = document.getElementById('loadingMessage');

            // Remove loading message
            if (loadingMessage) {
                loadingMessage.remove();
            }

            if (data.status === 'success' && data.data.length > 0) {
                // Create cards for each waste type
                data.data.forEach((sampah, index) => {
                    const harga = parseInt(sampah.harga_per_kg);
                    const card = document.createElement('div');
                    card.className = 'sampah-card';
                    card.setAttribute('data-harga', harga);

                    // Get a random icon for the waste type
                    const icons = [
                        'fa-bottle-water', 'fa-box', 'fa-coins', 'fa-leaf',
                        'fa-shopping-bag', 'fa-gem', 'fa-recycle', 'fa-trash'
                    ];
                    const randomIcon = icons[Math.floor(Math.random() * icons.length)];

                    card.innerHTML = `
                        <i class="fas ${randomIcon} sampah-icon"></i>
                        <h3>${sampah.nama_sampah}</h3>
                        <div class="harga">Rp ${harga.toLocaleString('id-ID')}/kg</div>
                        <p>Harga terbaru untuk ${sampah.nama_sampah}. Harga bisa berubah sewaktu-waktu.</p>
                    `;

                    // Add click handler to the card
                    card.addEventListener("click", function () {
                        const nama = this.querySelector("h3").textContent;
                        const harga = parseInt(this.dataset.harga).toLocaleString("id-ID");

                        // Create modal
                        const modal = document.createElement("div");
                        modal.className = "harga-modal";
                        modal.innerHTML = `
                                    <div class="modal-content">
                                        <h3>${nama}</h3>
                                        <div class="harga-big">Rp ${harga}/kg</div>
                                        <p>Harga berlaku per kilogram. Minimum setor 1kg.</p>
                                        <button class="btn btn-primary" onclick="this.parentElement.parentElement.remove()">
                                            Tutup
                                        </button>
                                    </div>
                                `;
                        document.body.appendChild(modal);

                        // Auto remove after 3s
                        setTimeout(() => modal.remove(), 4000);
                    });

                    // Add staggered animation
                    card.style.opacity = "0";
                    card.style.transform = "translateY(20px)";
                    card.style.transition = "opacity 0.3s ease, transform 0.3s ease";

                    sampahGrid.appendChild(card);

                    setTimeout(() => {
                        card.style.opacity = "1";
                        card.style.transform = "translateY(0)";
                    }, index * 100);
                });
            } else {
                // Show message if no data
                const noDataMessage = document.createElement('div');
                noDataMessage.className = 'text-center';
                noDataMessage.innerHTML = `
                    <i class="fas fa-exclamation-triangle fa-2x"></i>
                    <p>Belum ada data jenis sampah yang tersedia.</p>
                `;
                sampahGrid.appendChild(noDataMessage);
            }
        })
        .catch(error => {
            console.error('Error fetching waste types:', error);
            const sampahGrid = document.getElementById('sampahGrid');
            const loadingMessage = document.getElementById('loadingMessage');

            if (loadingMessage) {
                loadingMessage.remove();
            }

            const errorMessage = document.createElement('div');
            errorMessage.className = 'text-center';
            errorMessage.innerHTML = `
                <i class="fas fa-exclamation-circle fa-2x"></i>
                <p>Terjadi kesalahan saat memuat data jenis sampah.</p>
            `;
            sampahGrid.appendChild(errorMessage);
        });
});
