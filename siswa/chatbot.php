<style>
#chatbotCBT {
  font-family: "Segoe UI", sans-serif;
  position: fixed;
  bottom: 20px;
  right: 20px;
  width: 320px;
  max-height: 500px;
  display: flex;
  flex-direction: column;
  background: white;
  border-radius: 18px;
  box-shadow: 0 10px 20px rgba(0,0,0,0.1);
  overflow: hidden;
  z-index: 9999;
  border: 1px solid #e0e0e0;
  transition: transform 0.3s ease, opacity 0.3s ease;
}

#chatbotCBT.minimized {
  max-height: 48px;
  overflow: visible;
}

#chatbotCBT.closed {
  opacity: 0;
  pointer-events: none;
  transform: translateY(100px);
  transition: opacity 0.3s ease, transform 0.3s ease;
}

#chatbotCBT .header {
  background: linear-gradient(135deg,rgb(76, 96, 175),rgb(15, 47, 149));
  color: white;
  padding: 12px 16px;
  font-weight: bold;
  display: flex;
  align-items: center;
  gap: 8px;
  font-size: 16px;
  user-select: none;
}

#chatbotCBT .header i {
  font-size: 18px;
  cursor: default;
}

#chatbotCBT .header .btn-minimize,
#chatbotCBT .header .btn-close {
  background: transparent;
  border: none;
  color: white;
  font-size: 20px;
  cursor: pointer;
  padding: 0 6px;
  line-height: 1;
  user-select: none;
}

#chatbotCBT .header .btn-minimize:hover,
#chatbotCBT .header .btn-close:hover {
  color:rgb(80, 64, 174);
}

#chatbotCBT .body {
  flex: 1;
  padding: 12px;
  overflow-y: auto;
  display: flex;
  flex-direction: column;
  gap: 8px;
  background: #f9f9f9;
}

#chatbotCBT .footer {
  display: flex;
  border-top: 1px solid #eee;
  background: #fff;
}

#chatbotCBT input {
  flex: 1;
  padding: 10px 12px;
  border: none;
  font-size: 14px;
  outline: none;
}

#chatbotCBT button.send-btn {
  background: transparent;
  border: none;
  padding: 0 14px;
  font-size: 18px;
  color: #4CAF50;
  cursor: pointer;
}

.bot, .user {
  max-width: 80%;
  padding: 10px 14px;
  border-radius: 18px;
  font-size: 14px;
  line-height: 1.4;
  white-space: pre-wrap;
}

.bot {
  background: #ffffff;
  align-self: flex-start;
  border: 1px solid #e0e0e0;
}

.user {
  background: #dcf8c6;
  align-self: flex-end;
}

#btnOpenChatbot {
  position: fixed;
  bottom: 20px;
  right: 20px;
  background:rgb(54, 62, 144);
  color: white;
  border-radius: 50%;
  width: 48px;
  height: 48px;
  font-size: 24px;
  display: none;
  justify-content: center;
  align-items: center;
  cursor: pointer;
  box-shadow: 0 4px 10px rgba(0,0,0,0.2);
  z-index: 10000;
  user-select: none;
  transition: background-color 0.3s ease;
}

#btnOpenChatbot.new-message::after {
  content: "";
  position: absolute;
  top: 8px;
  right: 8px;
  width: 10px;
  height: 10px;
  background: red;
  border-radius: 50%;
  box-shadow: 0 0 4px red;
}

#btnOpenChatbot:hover {
  background-color:rgb(102, 88, 208);
}
</style>

<!-- Chatbot container -->
<div id="chatbotCBT" class="minimized closed" role="dialog" aria-label="Chatbot Asisten CBT">
  <div class="header" role="banner" aria-live="polite">
    <i><i class="fa-solid fa-robot"></i></i> Asisten CBT
    <button class="btn-minimize" title="Minimize Chatbot" aria-label="Minimize Chatbot" onclick="event.stopPropagation(); minimizeChatbot()">—</button>
    <button class="btn-close" title="Close Chatbot" aria-label="Close Chatbot" onclick="event.stopPropagation(); closeChatbot()">✕</button>
  </div>
  <div class="body" id="cbtBody" style="display:none;" role="log" aria-live="polite" aria-atomic="false"></div>
  <div class="footer" style="display:none;">
    <input type="text" id="cbtInput" placeholder="Ketik pertanyaan..." aria-label="Masukkan pertanyaan" onkeydown="if(event.key==='Enter'){sendCBT()}" />
    <button class="send-btn" aria-label="Kirim pertanyaan" onclick="sendCBT()">➤</button>
  </div>
</div>

<!-- Tombol open kecil -->
<div id="btnOpenChatbot" title="Buka Asisten CBT" aria-label="Buka Asisten CBT" role="button" tabindex="0" onclick="openChatbot()" onkeydown="if(event.key==='Enter' || event.key===' ') openChatbot()"><i class="fa-solid fa-robot"></i></div>

<script>
const chatbot = document.getElementById("chatbotCBT");
const btnOpen = document.getElementById("btnOpenChatbot");
const body = chatbot.querySelector(".body");
const footer = chatbot.querySelector(".footer");
const btnMinimize = chatbot.querySelector(".btn-minimize");
const btnClose = chatbot.querySelector(".btn-close");
const input = document.getElementById("cbtInput");

let faqCBT = {};

fetch("get_faq.php")
  .then(res => res.json())
  .then(data => {
    faqCBT = data;
    if (!welcomed) {
      welcomeMessage();
      welcomed = true;
    }
  });

// Tambah chat ke layar
function appendCBT(text, sender) {
  const div = document.createElement("div");
  div.className = sender;
  div.innerHTML = text.replace(/\n/g, "<br>");
  body.appendChild(div);
  body.scrollTop = body.scrollHeight;
  if (sender === "bot" && chatbot.classList.contains("closed")) {
    showNewMessageNotif();
  }
}

// Tampilkan pesan selamat datang
function welcomeMessage() {
  const welcomeText = `Halo! 👋 Saya Asisten CBT.<br>Kamu bisa tanya hal-hal berikut:<br>` +
    Object.keys(faqCBT).map(k => `- ${k}`).join("<br>");
  appendCBT(welcomeText, "bot");
}

// Buka chatbot
let welcomed = false;
function openChatbot() {
  chatbot.classList.remove("closed", "minimized");
  body.style.display = "flex";
  footer.style.display = "flex";
  btnOpen.style.display = "none";
  btnMinimize.textContent = "—";
  removeNewMessageNotif();
  input.focus();

  if (!welcomed) {
    welcomeMessage();
    welcomed = true;
  }
}

// Minimize
function minimizeChatbot() {
  chatbot.classList.add("minimized");
  body.style.display = "none";
  footer.style.display = "none";
  btnOpen.style.display = "flex";
  btnMinimize.textContent = "+";
}

// Tutup
function closeChatbot() {
  chatbot.classList.add("closed");
  btnOpen.style.display = "flex";
  body.style.display = "none";
  footer.style.display = "none";
}

// Tombol indikator pesan baru
function showNewMessageNotif() {
  btnOpen.classList.add("new-message");
}
function removeNewMessageNotif() {
  btnOpen.classList.remove("new-message");
}

// Fuzzy Matching
function similarity(str1, str2) {
  str1 = str1.toLowerCase();
  str2 = str2.toLowerCase();
  let longer = str1.length > str2.length ? str1 : str2;
  let shorter = str1.length > str2.length ? str2 : str1;
  let longerLength = longer.length;
  if (longerLength === 0) return 1.0;
  return (longerLength - levenshtein(longer, shorter)) / longerLength;
}
function levenshtein(a, b) {
  const matrix = Array.from({ length: b.length + 1 }, (_, i) => [i]);
  for (let j = 0; j <= a.length; j++) matrix[0][j] = j;
  for (let i = 1; i <= b.length; i++) {
    for (let j = 1; j <= a.length; j++) {
      matrix[i][j] = Math.min(
        matrix[i - 1][j - 1] + (a[j - 1] === b[i - 1] ? 0 : 1),
        matrix[i][j - 1] + 1,
        matrix[i - 1][j] + 1
      );
    }
  }
  return matrix[b.length][a.length];
}

// Fungsi kirim pertanyaan
function sendCBT() {
  const text = input.value.trim();
  if (!text) return;
  appendCBT(text, "user");

  const lower = text.toLowerCase();
  let bestMatch = "";
  let bestScore = 0;

  for (const key in faqCBT) {
    const score = similarity(lower, key);
    if (score > bestScore) {
      bestScore = score;
      bestMatch = key;
    }
  }

  if (bestScore >= 0.6) {
    appendCBT(faqCBT[bestMatch], "bot");
  } else if (bestScore >= 0.3) {
    appendCBT(`Mungkin maksud kamu: <b>"${bestMatch}"</b>?<br>👉 ${faqCBT[bestMatch]}`, "bot");
  } else {
    const suggestion = Object.keys(faqCBT).slice(0, 5).map(k => `- ${k}`).join("<br>");
    appendCBT("Maaf, saya belum mengerti pertanyaan itu. Coba gunakan kata kunci seperti:<br>" + suggestion, "bot");
  }

  input.value = "";
  input.focus();
}

// Event handler
btnOpen.onclick = openChatbot;
btnMinimize.onclick = minimizeChatbot;
btnClose.onclick = closeChatbot;

// Awal load
window.onload = () => {
  closeChatbot();
};
</script>

