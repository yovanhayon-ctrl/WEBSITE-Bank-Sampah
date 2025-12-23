// Auto-resizing textarea for alamat lengkap
document.addEventListener("DOMContentLoaded", () => {
  const textarea = document.querySelector(".alamat-textarea");

  if (!textarea) return;

  textarea.addEventListener("input", function () {
    this.style.height = "56px"; // reset ke tinggi awal
    this.style.height = this.scrollHeight + "px";
  });
});

// Handle form submission
document.getElementById('daftarForm').addEventListener('submit', function(e) {
  e.preventDefault(); // Prevent default form submission

  // Validate NIK (16 digits)
  const nik = document.getElementById('nik').value;
  if (!/^\d{16}$/.test(nik)) {
    alert('NIK harus berupa 16 digit angka');
    return;
  }

  // Validate phone number (Indonesian format)
  const phone = document.getElementById('no_whatsapp').value;
  if (!/^08\d{8,11}$/.test(phone)) {
    alert('Nomor WhatsApp harus diawali dengan 08 dan berisi 10-13 digit');
    return;
  }

  // Show loading state
  const submitBtn = this.querySelector('.btn-primary');
  const originalText = submitBtn.innerHTML;
  submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Mengirim...';
  submitBtn.disabled = true;

  // Get form data
  const formData = new FormData(this);

  // Send form data using fetch
  fetch('../backend/proses/simpan_pendaftaran.php', {
    method: 'POST',
    body: formData
  })
  .then(response => response.json())
  .then(result => {
    // Reset button
    submitBtn.innerHTML = originalText;
    submitBtn.disabled = false;

    if (result.status === 'success') {
      alert(result.message);
      this.reset(); // Reset form

      // Reset textarea height
      const textarea = document.querySelector(".alamat-textarea");
      if (textarea) {
        textarea.style.height = "56px";
      }
    } else {
      alert(result.message || 'Terjadi kesalahan saat mengirim data.');
    }
  })
  .catch(error => {
    console.error('Error:', error);
    // Reset button
    submitBtn.innerHTML = originalText;
    submitBtn.disabled = false;
    alert('Terjadi kesalahan saat mengirim data. Silakan coba lagi.');
  });
});

// Halaman otomatis rapih saat di buka
const textarea = document.querySelector(".alamat-textarea");
textarea.dispatchEvent(new Event("input"));
