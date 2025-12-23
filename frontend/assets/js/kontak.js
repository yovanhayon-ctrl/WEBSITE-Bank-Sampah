// Handle contact form submission
document.addEventListener('DOMContentLoaded', function() {
  const kontakForm = document.getElementById('kontakForm');
  
  if (kontakForm) {
    kontakForm.addEventListener('submit', function(e) {
      e.preventDefault(); // Prevent default form submission
      
      // Show loading state
      const submitBtn = this.querySelector('.btn-primary');
      const originalText = submitBtn.innerHTML;
      submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Mengirim...';
      submitBtn.disabled = true;
      
      // Get form data
      const formData = new FormData(this);
      
      // Send form data using fetch
      fetch('../backend/proses/simpan_kontak.php', {
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
        } else {
          alert(result.message || 'Terjadi kesalahan saat mengirim pesan.');
        }
      })
      .catch(error => {
        console.error('Error:', error);
        // Reset button
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
        alert('Terjadi kesalahan saat mengirim pesan. Silakan coba lagi.');
      });
    });
  }
});