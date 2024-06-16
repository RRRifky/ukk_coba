document.addEventListener("DOMContentLoaded", function() {
  // Temukan elemen div berdasarkan ID
  var targetDiv = document.getElementById("targetDiv");

  // Periksa apakah elemen div ditemukan
  if (targetDiv) {
    // Tambahkan gaya langsung ke elemen div
    targetDiv.style.width = "100vh";
    targetDiv.style.height = "100vh";
    targetDiv.style.position = "fixed";
    // targetDiv.style.right = "0px";
    // targetDiv.style.bottom = "0px";
    targetDiv.style.opacity = "0.5";
    targetDiv.style.zIndex = "1000";
    targetDiv.style.color = "red";
    // tambahkan teks ke dalam elemen div
    targetDiv.textContent = "woilah cik";
  }
});