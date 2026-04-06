<footer class="bg-light text-left py-3 mt-5">
    <p>&copy; <?= date("Y") ?> Senam Prolanis | Sehat Bersama</p>
</footer>

<!-- Loading Screen Disabler -->
<script>
window.addEventListener("load", function() {
    let loadingScreen = document.getElementById("loading-screen");
    if (loadingScreen) {
        loadingScreen.style.display = "none";
    }
});
</script>

<!-- Auto-Logout Setelah 5 Menit Tidak Aktif -->
<script>
let timeout;
const logoutAfter = 5 * 60 * 1000; // 5 menit (dalam ms)

function resetTimer() {
    clearTimeout(timeout);
    timeout = setTimeout(() => {
        window.location.href = 'logout.php';
    }, logoutAfter);
}

['click', 'mousemove', 'keydown', 'scroll', 'touchstart'].forEach(evt =>
    document.addEventListener(evt, resetTimer)
);

resetTimer(); // Mulai timer saat halaman dibuka
</script>
</body>
</html>
