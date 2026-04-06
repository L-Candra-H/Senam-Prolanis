// File: public/js/main.js
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    form.addEventListener('submit', function(e) {
        // Contoh validasi sederhana dengan JSON response
        let data = new FormData(form);
        // Lakukan pengecekan misalnya untuk nomor kartu BPJS memiliki 13 digit.
        if(data.get('nomor_kartu_bpjs').length !== 13){
            e.preventDefault();
            alert("Nomor Kartu BPJS harus 13 digit!");
        }
    });
});

