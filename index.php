<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mind The Minute DJ</title>
    
<style>
    :root {
        --primary: #38bdf8;
        --success: #22c55e;
        --danger: #ef4444;
        --bg: #0f172a;
        --card-bg: #1e293b;
        --text: #f8fafc;
        --text-muted: #94a3b8;
        --border: #334155;
        --input-bg: #0f172a;
    }
    * { box-sizing: border-box; margin: 0; padding: 0; font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; }
    body { background-color: var(--bg); color: var(--text); display: flex; justify-content: center; align-items: flex-start; min-height: 100vh; padding: 20px; }
    
    /* Širší kontejner pro velké monitory */
    .container { width: 100%; max-width: 900px; background: var(--card-bg); padding: 25px; border-radius: 20px; border: 1px solid var(--border); }
    
    h2 { font-size: 24px; text-align: center; margin-bottom: 25px; font-weight: 800; color: white; }
    
    /* Vylepšené rozvržení pro širší editor */
    .playlist-builder { max-height: 500px; overflow-y: auto; padding: 10px; margin-bottom: 15px; border: 1px solid var(--border); border-radius: 10px; background: #0f172a; }
    
    /* Grid layout pro řádky editoru */

    .playlist-row { 

        display: flex; 

        flex-direction: column; 

        align-items: stretch; 
        gap: 15px; 
        padding: 8px; 
        border-bottom: 1px solid var(--border); 
    }
    
    .playlist-row-header { display: flex; align-items: center; gap: 5px; }
    .playlist-row-header span { font-size: 13px; font-weight: 700; color: var(--text-muted); min-width: 50px; }
    
    .select-audio { padding: 8px; font-size: 13px; border: 1px solid var(--border); border-radius: 8px; background: #1e293b; color: white; width: 100%; }
    .input-text-desc { padding: 8px 12px; font-size: 13px; border: 1px solid var(--border); border-radius: 8px; background: #1e293b; color: white; width: 100%; }
    
    /* Ostatní styly zachovány */

    .input-number { font-size: 24px; padding: 10px; width: 100px; text-align: center; border-radius: 10px; border: 1px solid var(--border); background: var(--input-bg); color: white; }
    .setup-box { display: flex; flex-direction: column; align-items: center; margin-bottom: 20px; }
    .controls { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 20px; }
    .btn { padding: 16px; cursor: pointer; border: none; border-radius: 14px; font-weight: 700; transition: all 0.2s; }
    .btn-start { background-color: var(--success); color: white; grid-column: span 2; font-size: 18px; }
    .btn-next { background-color: var(--primary); color: #000; }
    .btn-stop { background-color: var(--danger); color: white; }
    
    /* Toggle button style */
    .btn-toggle { grid-column: span 2; font-size: 18px; padding: 16px; border-radius: 14px; border: none; font-weight: 700; cursor: pointer; transition: all 0.2s; color: white; }
    .btn-toggle.stopped { background-color: var(--success); }
    .btn-toggle.playing { background-color: var(--danger); }

    /* Back button style */
    .btn-back { background: #334155; color: white; }
    
    .info-box { background: var(--input-bg); border: 1px solid var(--border); padding: 20px; border-radius: 16px; text-align: center; margin-bottom: 20px; }

    #countdown { font-size: 32px; color: var(--text-muted); }
    

    #status { font-size: 40px; font-weight: bold; margin-bottom: 10px; }
    #activityText { font-size: 48px; font-weight: 800; color: #f8fafc; margin: 15px 0; }
    .editor-container { display: none; background: #162030; padding: 15px; border-radius: 14px; border: 1px solid var(--border); margin-top: 10px; }
    .btn-save-playlist { background-color: var(--primary); color: #000; width: 100%; padding: 15px; border-radius: 10px; font-weight: 700; border: none; cursor: pointer; margin-top: 10px; }

.btn-play { background: #334155; color: white; padding: 5px 10px; border-radius: 5px; cursor: pointer; margin-left: 5px; border: none; }

.random-mode-label {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    margin-top: 15px;
    padding: 10px 15px;
    background: #1e293b;
    border: 1px solid #334155;
    border-radius: 8px;
    cursor: pointer;
    transition: background 0.2s;
}
.random-mode-label:hover { background: #2d3e56; }
</style>

</head>
<body>


<div class="container">
    <h2>Mind The Minute DJ</h2>
    
    <div class="setup-box">
        <label for="minuteInput">Minuta k přehrání</label>
        <input type="number" id="minuteInput" class="input-number" min="1" max="60" value="1">
        <label class="random-mode-label"><input type="checkbox" id="randomModeToggle"> Použít náhodné MP3 pro prázdná pole</label>
    </div>
    
    <div class="controls">
        <button id="playToggle" class="btn btn-toggle stopped" aria-pressed="false" type="button">Spustit přehrávání</button>
        <button id="backBtn" class="btn btn-back" disabled>Zpět</button>
        <button id="nextBtn" class="btn btn-next" disabled>Vpřed (Next)</button>
    </div>
    
    <div class="volume-box">
        <label for="volumeSlider">Hlasitost</label>
        <input type="range" id="volumeSlider" class="volume-slider" min="0" max="1" step="0.05" value="0.8">
    </div>

    <!-- UPRAVENÝ INFORMAČNÍ PANEL S TEXTOVÝM ŘÁDKEM -->
    <div class="info-box">
        <div id="status">Aktuální stav</div>
        <div id="activityText">- - -</div> <!-- Sem skočí text např. "Jízda vlakem" -->
        <div id="fileName">Načítám data ze serveru...</div>
        <div id="countdown">00:00</div>
        <div class="progress-container">
            <div id="progressBar"></div>
        </div>
    </div>

    <!-- INTERAKTIVNÍ PANEL SEZNAMU -->
    <div class="editor-section">
        <button id="toggleEditorBtn" class="btn-toggle-editor">⚙️ Upravit společné pořadí</button>
        <div id="editorContainer" class="editor-container">
            <p><strong>Vyber soubory a doprovodné texty pro každou minutu.</strong><br>Pokud text nevyplníš, zůstane prázdný.</p>
            
            <div id="playlistBuilder" class="playlist-builder"></div>
            
            <button id="savePlaylistBtn" class="btn-save-playlist">Uložit a sdílet se všemi</button>
        </div>
    </div>

    <div class="footer-actions">
        <button id="resetPositionBtn" class="btn-text">Vynulovat moje minuty</button>
    </div>

<div class="setup-box">
    <!-- Nový výběr playlistu -->
    <label for="playlistSelect">Výběr playlistu:</label>
    <select id="playlistSelect" class="select-audio" style="margin-bottom: 15px;">
        <option value="playlist.txt">Výchozí (playlist.txt)</option>
        <option value="playlist_predstaveni.txt">Představení</option>
        <option value="playlist_cokoliv.txt">Cokoliv</option>
    </select>

   </div>

<script>
    const MINUTE_DURATION_SEC = 60; 
    const MINUTE_STORAGE_KEY = 'looper_current_minute_text_v1'; 
    const PHP_SCRIPT_URL = 'save_playlist.php'; 
    const DEFAULT_FALLBACK_FILE = 'klapnuti.mp3'; 
    let previewAudio = new Audio();

// ... (začátek skriptu)
const playlistSelect = document.getElementById('playlistSelect');
let currentPlaylistFileName = 'playlist.txt';

// Přidání posluchače pro změnu playlistu
playlistSelect.addEventListener('change', (e) => {
    currentPlaylistFileName = e.target.value;
    loadPlaylistFromServer(); // Znovu načte data pro nový soubor
});
 
   function playPreview(fileName) {
        if (!fileName) return;
        previewAudio.pause();
        previewAudio.src = 'audio/' + fileName;
        previewAudio.play();
    }

    let serverMp3Files = []; 
    let playlist = []; // Bude obsahovat objekty { file: "...", text: "..." }
    
    let currentMinute = 1;
    let audio = null;
    let countdownInterval = null; 
    let targetEndTime = 0; 
    let secondsLeft = MINUTE_DURATION_SEC;
    let currentVolume = 0.8;

    let useRandomMode = false;
    let isPlaying = false; // nový stav přehrávání
    let lastStartedMinute = null; // pamatuje kterou minutu jsme naposledy spustili
    // DOM Prvky
    const playToggle = document.getElementById('playToggle');
    const backBtn = document.getElementById('backBtn');
    const nextBtn = document.getElementById('nextBtn');
    const toggleEditorBtn = document.getElementById('toggleEditorBtn');
    const editorContainer = document.getElementById('editorContainer');
    const playlistBuilder = document.getElementById('playlistBuilder');
    const savePlaylistBtn = document.getElementById('savePlaylistBtn');
    const resetPositionBtn = document.getElementById('resetPositionBtn');
    const volumeSlider = document.getElementById('volumeSlider');
    const minuteInput = document.getElementById('minuteInput');
    const randomModeToggle = document.getElementById('randomModeToggle');
    const statusDiv = document.getElementById('status');
    const activityTextDiv = document.getElementById('activityText');
    const fileNameDiv = document.getElementById('fileName');
    const countdownDiv = document.getElementById('countdown');
    const progressBar = document.getElementById('progressBar');

    // Back click tracking
    let backClickCount = 0;
    let backLastClickTime = 0;
    let backBaseMinute = null;
    let backResetTimer = null;
    const BACK_THRESHOLD_MS = 500; // 0.5s

    window.addEventListener('DOMContentLoaded', async () => {
        const savedMinute = localStorage.getItem(MINUTE_STORAGE_KEY);
        if (savedMinute) {
            currentMinute = parseInt(savedMinute);
            minuteInput.value = currentMinute;
        }
        await fetchAvailableMp3Files();
        loadPlaylistFromServer();
    });

    async function fetchAvailableMp3Files() {
        try {
            const response = await fetch(`${PHP_SCRIPT_URL}?action=get_mp3_list`);



serverMp3Files = await response.json();  
} catch (error) {  
console.error(error);  
statusDiv.innerText = "Chyba spojení se složkou audio/";  
}  
}


// Upravená funkce pro načítání - nyní používá parametr file
function loadPlaylistFromServer() {
    fetch(`${PHP_SCRIPT_URL}?file=${encodeURIComponent(currentPlaylistFileName)}`)
    .then(response => response.text())
    .then(text => {
        const cleanText = text.trim();
        playlist = [];
        let lines = cleanText === "" ? [] : cleanText.split('\n');
        
        for (let i = 0; i < 60; i++) {
            if (lines[i]) {
                const parts = lines[i].split('|');
                playlist.push({
                    file: parts[0] ? parts[0].trim() : "",
                    text: parts[1] ? parts[1].trim() : ""
                });
            } else {
                playlist.push({ file: "", text: "" });
            }
        }
        statusDiv.innerText = `Synchronizováno: ${currentPlaylistFileName}`;
        updateNextFilePreview();
        buildEditorUI();
    });
}



// Vygenerování 60 řádků: každá minuta má Select a pod ním Input pro text  

function buildEditorUI() {  
    playlistBuilder.innerHTML = "";
    for (let i = 0; i < 60; i++) {  
        const row = document.createElement('div');  
        row.className = 'playlist-row';
        const rowHeader = document.createElement('div');  
        rowHeader.className = 'playlist-row-header';
        const label = document.createElement('span');  
        label.innerText = `${i + 1}. minuta:`;
        const select = document.createElement('select');  
        select.className = 'select-audio';
        const emptyOption = document.createElement('option');  
        emptyOption.value = "";  
        emptyOption.innerText = `- Výchozí (${DEFAULT_FALLBACK_FILE}) -`;  
        select.appendChild(emptyOption);
        serverMp3Files.forEach(fileName => {  
            if (fileName === DEFAULT_FALLBACK_FILE) return;  
            const option = document.createElement('option');  
            option.value = fileName;  
            option.innerText = fileName;  
            if (playlist[i].file === fileName) option.selected = true;  
            select.appendChild(option);  
        });
        const playBtn = document.createElement('button');  
        playBtn.className = 'btn-play';  
        playBtn.innerText = '▶️';  
        playBtn.onclick = (e) => { e.preventDefault(); playPreview(select.value); };  
        rowHeader.appendChild(label);  
        rowHeader.appendChild(select);  
        rowHeader.appendChild(playBtn);
        const inputDesc = document.createElement('input');  
        inputDesc.type = 'text';  
        inputDesc.className = 'input-text-desc';
        inputDesc.placeholder = 'Doprovodný text...';  
        inputDesc.value = playlist[i].text;
        row.appendChild(rowHeader);  
        row.appendChild(inputDesc);  
        playlistBuilder.appendChild(row);  
    }  
}

function updateNextFilePreview() {  
const nextItem = playlist[currentMinute - 1];  
const nextFile = nextItem && nextItem.file ? nextItem.file : DEFAULT_FALLBACK_FILE;  
const nextText = nextItem && nextItem.text ? `"${nextItem.text}"` : "(Bez popisu)";

activityTextDiv.innerText = nextItem && nextItem.text ? nextItem.text : "- - -";  
fileNameDiv.innerText = `Připraveno: ${currentMinute}. minuta [ Audio: ${nextFile} | Popis: ${nextText} ]`;  
}

function playCurrentMinute() {  
if (currentMinute > 60) {  
stopPlayback();  
statusDiv.innerText = "Program dokončen";  
activityTextDiv.innerText = "Konec programu";  
fileNameDiv.innerText = "Hodinový cyklus úspěšně skončil.";  
countdownDiv.innerText = "00:00";  
progressBar.style.width = "0%";  
currentMinute = 1;  
minuteInput.value = 1;  
localStorage.removeItem(MINUTE_STORAGE_KEY);  
return;  
}

// zapamatuj si, kterou minutu startujeme (pro Back logiku)
lastStartedMinute = currentMinute;

let currentItem = playlist[currentMinute - 1];  
let currentFileName = currentItem && currentItem.file ? currentItem.file.trim() : "";  
let currentText = currentItem && currentItem.text ? currentItem.text.trim() : "";  
let isUsingFallback = false;

if (currentFileName === "") {  
    if (useRandomMode && serverMp3Files.length > 0) {  
        currentFileName = serverMp3Files[Math.floor(Math.random() * serverMp3Files.length)];  
    } else {  
        currentFileName = DEFAULT_FALLBACK_FILE;  
        isUsingFallback = true;  
    }  
}

// Zobrazení popisku velkým písmem na střed screenu  
statusDiv.innerText = ` ${currentMinute}. min / 60`;  
activityTextDiv.innerText = currentText !== "" ? currentText : "- - -"; // Pokud text chybí, zobrazí se pomlčky  
fileNameDiv.innerText = isUsingFallback ? `Zvuk: ${currentFileName} (Výchozí)` : `Zvuk: ${currentFileName}`;  
minuteInput.value = currentMinute;

localStorage.setItem(MINUTE_STORAGE_KEY, currentMinute);

if (audio) {  
audio.pause();  
audio.currentTime = 0;  
}

audio = new Audio(`audio/${currentFileName}`);  
audio.volume = currentVolume;  
audio.play().catch(error => {  
console.error(error);  
fileNameDiv.innerText = `Chyba: Soubor "${currentFileName}" chybí ve složce audio/!`;  
});

targetEndTime = Date.now() + (MINUTE_DURATION_SEC * 1000);  
secondsLeft = MINUTE_DURATION_SEC;

updateDisplay();  
currentMinute++;  
}

function tick() {  
const now = Date.now();  
const timeLeftMs = targetEndTime - now;  
secondsLeft = Math.ceil(timeLeftMs / 1000);

if (secondsLeft <= 0) {  
playCurrentMinute();  
} else {  
updateDisplay();  
}  
}

function updateDisplay() {  
const displaySecVal = secondsLeft < 0 ? 0 : secondsLeft;  
const displaySec = displaySecVal < 10 ? `0${displaySecVal}` : displaySecVal;  
countdownDiv.innerText = `00:${displaySec}`;  
progressBar.style.width = `${(displaySecVal / MINUTE_DURATION_SEC) * 100}%`;  
}

toggleEditorBtn.addEventListener('click', async () => {  
if (editorContainer.style.display === 'block') {  
editorContainer.style.display = 'none';  
toggleEditorBtn.innerText = "⚙️ Upravit společné pořadí";  
} else {  
await fetchAvailableMp3Files();  
loadPlaylistFromServer();  
editorContainer.style.display = 'block';  
toggleEditorBtn.innerText = "❌ Skrýt editor skladeb";  
}  
});


// Upravená funkce pro ukládání - přidává parametr do URL
savePlaylistBtn.addEventListener('click', () => {
    const rows = document.querySelectorAll('.playlist-row');
    let newPlaylistLines = [];
    rows.forEach(row => {
        const selectVal = row.querySelector('.select-audio').value;
        const textVal = row.querySelector('.input-text-desc').value.trim();
        newPlaylistLines.push(`${selectVal}|${textVal}`);
    });

    const textToSave = newPlaylistLines.join('\n');
    savePlaylistBtn.disabled = true;
    savePlaylistBtn.innerText = "Ukládám...";

    // Volání s parametrem file
    fetch(`${PHP_SCRIPT_URL}?file=${encodeURIComponent(currentPlaylistFileName)}`, {
        method: 'POST',
        body: textToSave,
        headers: { 'Content-Type': 'text/plain' }
    })

.then(response => {  
if (response.ok) {  
alert("Pořadí a texty úspěšně uloženy pro všechny!");  
loadPlaylistFromServer();  
editorContainer.style.display = 'none';  
toggleEditorBtn.innerText = "⚙️ Upravit společné pořadí";  
} else {  
alert("Chyba při ukládání na server.");  
}  
})  
.catch(error => alert("Nelze se připojit k serveru."))  
.finally(() => {  
savePlaylistBtn.disabled = false;  
savePlaylistBtn.innerText = "Uložit a sdílet se všemi";  
});
});

volumeSlider.addEventListener('input', (e) => {  
currentVolume = parseFloat(e.target.value);  
if (audio) audio.volume = currentVolume;  
});

// Toggle button behavior: start / stop as one
playToggle.addEventListener('click', () => {
    if (!isPlaying) {
        // start playback
        if (countdownInterval === null && audio === null) {
            currentMinute = parseInt(minuteInput.value) || 1;
        }
        isPlaying = true;
        playToggle.classList.add('playing');
        playToggle.classList.remove('stopped');
        playToggle.setAttribute('aria-pressed', 'true');
        playToggle.textContent = 'Zastavit';

        nextBtn.disabled = false;
        backBtn.disabled = false;
        minuteInput.disabled = true;
        toggleEditorBtn.disabled = true;

        playCurrentMinute();
        countdownInterval = setInterval(tick, 250);
    } else {
        // stop playback
        stopPlayback();
    }
});

// Back button: restart current minute on first click; quick multiple clicks go further back
backBtn.addEventListener('click', () => {
    if (!isPlaying || lastStartedMinute === null) return;
    const now = Date.now();
    if (now - backLastClickTime <= BACK_THRESHOLD_MS) {
        backClickCount++;
    } else {
        backClickCount = 1;
        backBaseMinute = lastStartedMinute; // remember which minute sequence started from
    }
    backLastClickTime = now;

    // reset the click-count after threshold so new sequences start fresh
    if (backResetTimer) clearTimeout(backResetTimer);
    backResetTimer = setTimeout(() => { backClickCount = 0; backBaseMinute = null; }, BACK_THRESHOLD_MS + 50);

    if (backClickCount === 1) {
        // restart current minute
        if (audio) {
            try { audio.currentTime = 0; } catch (e) { console.warn('Cannot reset currentTime', e); }
            audio.play().catch(() => {});
        }
        targetEndTime = Date.now() + (MINUTE_DURATION_SEC * 1000);
        secondsLeft = MINUTE_DURATION_SEC;
        updateDisplay();
    } else {
        // go back (backClickCount - 1) minutes from base
        const stepsBack = backClickCount - 1;
        const targetMinute = Math.max(1, backBaseMinute - stepsBack);
        // set currentMinute to target and play it
        currentMinute = targetMinute;
        playCurrentMinute();
    }
});

function stopPlayback() {
    // stop interval and audio
    if (countdownInterval) {
        clearInterval(countdownInterval);
        countdownInterval = null;
    }
    if (audio) audio.pause();

    nextBtn.disabled = true;
    backBtn.disabled = true;
    minuteInput.disabled = false;
    toggleEditorBtn.disabled = false;

    localStorage.setItem(MINUTE_STORAGE_KEY, currentMinute);
    statusDiv.innerText = "Přehrávání zastaveno";
    countdownDiv.innerText = "Pauza";
    updateNextFilePreview();

    // update toggle UI
    isPlaying = false;
    playToggle.classList.remove('playing');
    playToggle.classList.add('stopped');
    playToggle.setAttribute('aria-pressed', 'false');
    playToggle.textContent = 'Spustit přehrávání';
}

// replace previous next/start/stop wiring
nextBtn.addEventListener('click', playCurrentMinute);

resetPositionBtn.addEventListener('click', () => {  
if (countdownInterval !== null) stopPlayback();  
localStorage.removeItem(MINUTE_STORAGE_KEY);  
currentMinute = 1;  
minuteInput.value = 1;  
statusDiv.innerText = "Moje pozice resetována";  
updateNextFilePreview();  
countdownDiv.innerText = "00:00";  
progressBar.style.width = "0%";  
});
    async function saveMinuteToServer() {
        try {
            await fetch(`${PHP_SCRIPT_URL}?action=save_minute`, {
                method: 'POST',
                body: new URLSearchParams({ minute: currentMinute }),
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
            });
        } catch (error) {
            console.error("Chyba při ukládání minuty:", error);
        }
    }

    minuteInput.addEventListener('change', () => {
        currentMinute = parseInt(minuteInput.value);
        saveMinuteToServer();
        updateNextFilePreview();
    });
    randomModeToggle.addEventListener('change', (e) => { useRandomMode = e.target.checked; });
</script>

</body>
</html>
