document.addEventListener("DOMContentLoaded", function () {
  // Card click handler
  document.querySelectorAll(".sampah-card").forEach((card, index) => {
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

    // Staggered animation
    setTimeout(() => {
      card.style.opacity = "1";
      card.style.transform = "translateY(0)";
    }, index * 100);
  });
});
