<style>
  :root {
  --primary: #00bcd4;
  --secondary: #00796b;
  --danger: #ff5722;
  --warning: #ff9800;
  --dark: #121212;
  --light: #f5f5f5;
  --success: #4caf50;
  --gray-light: #e0e0e0;
  --gray-dark: #444;
}

* {
  box-sizing: border-box;
  margin: 0;
  padding: 0;
}

body {
  font-family: 'Poppins', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  background: linear-gradient(135deg, #000000, #4b0000, #2e004f);
  color: var(--gray-dark);
  min-height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 20px;
}

.game-container {
  background: var(--gray-dark);
  border-radius: 12px;
  box-shadow: 0 4px 12px rgba(0,0,0,0.08);
  width: 100%;
  max-width: 480px;
  padding: 30px 25px;
}

h2 {
  text-align: center;
  color: var(--primary);
  margin-bottom: 25px;
  font-weight: 700;
  font-size: 1.8rem;
}

#info {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: 16px;
  margin-bottom: 30px;
}

.info-item {
  background: var(--gray-light);
  color: var(--gray-dark);
  font-weight: 600;
  padding: 12px 15px;
  border-radius: 8px;
  display: flex;
  align-items: center;
  gap: 8px;
  font-size: 0.95rem;
  user-select: none;
  transition: background-color 0.2s ease;
  cursor: default;
}

.info-item:hover {
  background: var(--primary);
  color: white;
}

.info-item i {
  font-size: 1.2rem;
}

#kata-scramble {
  background: var(--primary);
  color: white;
  font-weight: 700;
  font-size: 2rem;
  text-align: center;
  border-radius: 12px;
  padding: 22px 15px;
  letter-spacing: 0.12em;
  margin-bottom: 25px;
  user-select: none;
}

#input-container {
  display: flex;
  justify-content: center;
  gap: 8px;
  margin-bottom: 25px;
  flex-wrap: wrap;
}

.input-letter {
  width: 38px;
  height: 38px;
  border: 1px solid var(--gray-light);
  border-radius: 6px;
  font-size: 1.1rem;
  text-align: center;
  text-transform: uppercase;
  padding: 5px;
  transition: border-color 0.3s ease;
  background: #fff;
}

.input-letter:focus {
  outline: none;
  border-color: var(--primary);
  box-shadow: 0 0 6px var(--primary);
}

.input-letter.revealed {
  background-color: var(--success);
  font-weight: 700;
  border-color: var(--success);
  color: white;
}

.button-group {
  display: flex;
  gap: 12px;
}

button {
  flex: 1;
  padding: 14px 0;
  border-radius: 8px;
  font-weight: 700;
  font-size: 1rem;
  border: none;
  cursor: pointer;
  color: white;
  background-color: var(--primary);
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
  transition: background-color 0.3s ease;
  user-select: none;
}

button:hover {
  background-color: #0097a7;
}

#bantuan-btn {
  background-color: var(--warning);
  color: var(--dark);
}

#bantuan-btn:hover {
  background-color: #ffb74d;
}

#keluar-btn {
  background-color: red;
  color: var(--light);
}

#keluar-btn:hover {
  background-color:rgb(240, 149, 13);
}

/* Responsive */
@media (max-width: 480px) {
  #info {
    grid-template-columns: 1fr;
  }
  
  .button-group {
    flex-direction: column;
  }
  
  button {
    width: 100%;
  }
}

</style>