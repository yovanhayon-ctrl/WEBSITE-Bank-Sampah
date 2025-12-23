// Auto-resizing textarea for alamat lengkap
document.addEventListener("DOMContentLoaded", () => {
  const textarea = document.querySelector(".alamat-textarea");

  if (!textarea) return;

  textarea.addEventListener("input", function () {
    this.style.height = "56px"; // reset ke tinggi awal
    this.style.height = this.scrollHeight + "px";
  });
});

// Halaman otomatis rapih saat di buka
const textarea = document.querySelector(".alamat-textarea");
textarea.dispatchEvent(new Event("input"));
