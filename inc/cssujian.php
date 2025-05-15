<style>
.tampilan img {
  max-width: 700px !important;
  max-height: 150px !important;
  width: 100%;
  object-fit: contain;
  display: block;
}
img#gbrsoal {
  max-width: 700px !important;
  max-height: 150px !important;
  width: 100%;
  object-fit: contain;
  display: block;
}
/* Kustomisasi Border untuk header card */
.custom-card-header {
        border-bottom: 1px solid #343a40; /* Garis bawah header card */
        background-color: #f8f9fa; /* Warna latar belakang header */
        padding: 10px; /* Padding tambahan untuk header */
        font-weight: bold; /* Agar teks header lebih tebal */
    }
    .custom-radio-spacing {
    margin-right: 100px; /* Menambah jarak kanan antar radio button */
    }
    input[type="radio"]:not(:checked) {
        border-color: black;  /* Warna border hitam */
    }
    input[type="radio"]:checked {
        background-color: green;  /* Warna latar belakang hijau */
        border-color: green;  /* Warna border hijau */
    }
    input[type="checkbox"]:not(:checked) {
        border-color: black;  /* Warna border hitam */
    }
    input[type="checkbox"]:checked {
        background-color: green;  /* Warna latar belakang hijau */
        border-color: green;  /* Warna border hijau */
    }
.table, .table th, .table td {
  border: 1px solid black !important;
}
.table {
  border-collapse: collapse !important;
}
table {
  border-collapse: collapse;
  width: 100%;
}

td {
  border: 1px solid black;
  text-align: center;
  vertical-align: middle;
  height: 50px;  /* supaya kelihatan vertical center */
}

.question-nav-container {
  margin-top: 20px;
}
.question-nav-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 10px;
  cursor: pointer;
  padding: 10px;
  border-radius: 5px;
  background-color: #f8f9fa;
}
.question-nav {
  display: flex;
  flex-wrap: wrap;
  gap: 5px;
  max-height: 200px;
  overflow: hidden;
  transition: max-height 0.3s ease-out;
  margin-bottom: 10px;
}
.question-nav.collapsed {
  max-height: 0;
}
.question-nav-btn {
  width: 40px;
  height: 40px;
  display: flex;
  align-items: center;
  justify-content: center;
  border: 1px solid #ddd;
  border-radius: 4px;
  cursor: pointer;
  transition: all 0.3s;
}
.question-nav-btn:hover {
  background-color: #f0f0f0;
}
.question-nav-btn.active {
  background-color: <?= $warna_tema ?>;
  color: white;
  border-color: <?= $warna_tema ?>;
}
.question-nav-btn.answered {
  background-color: #d4edda;
  border-color: #c3e6cb;
}
.navigation-buttons {
  display: flex;
  justify-content: space-between;
  margin-top: 5px;
}
.tempatsoal {
  min-height:380px;
  height:100%;
  display: flex;
  flex-wrap: wrap;
  gap: 2px;
  max-height: 200px;
  overflow-y: auto;
  transition: max-height 0.3s ease-out;
}
.dropdown-wide {
  min-width: 220px;
}
.navbar-bg.sticky-top {
  position: sticky;
  top: 0;
  z-index: 1030;
  background-color: var(--adminkit-body-bg) !important;
  backdrop-filter: blur(10px);
  -webkit-backdrop-filter: blur(10px);
}
html, body {
  height: 100%;
  margin: 0;
  overflow: hidden;
}
.wrapper {
  display: flex;
  flex-direction: column;
  height: 100vh;
}
main.content {
  flex: 1;
  overflow-y: auto;
  padding-bottom: 20px;
  margin-bottom: 120px;
}

/* Loading Spinner Styles */
#loadingOverlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(255,255,255,0.8);
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 9999;
  display: none;
}
.spinner-container {
  text-align: center;
}
.spinner-border {
  width: 3rem;
  height: 3rem;
}

#soal.sidebar-dropdown a {
    background-color: rgba(0, 0, 0, 0.15); /* Warna gelap dengan transparansi */
    padding: 10px 25px;
    margin-top: -1px; /* Untuk menghilangkan gap */
}
.question-nav-container {
    display: block;
    margin-top: 10px;
}

.question-nav-container.show {
    display: none;
}
</style>