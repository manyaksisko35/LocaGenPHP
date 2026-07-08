<?php
require_once 'includes/db.php';
require_once 'includes/header.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
?>

<style>
    body {
        background-color: #05010a !important;
        color: #f0e6ff !important;
        font-family: 'Consolas', 'Courier New', monospace !important;
    }
    .neon-container {
        background-color: #05010a;
        padding: 40px 30px;
        border-radius: 12px;
        border: 1px solid #450082;
        box-shadow: inset 0 0 50px rgba(69, 0, 130, 0.1), 0 0 30px rgba(176, 38, 255, 0.05);
        margin-top: 50px;
    }
    .neon-title {
        font-size: 2.5em;
        color: #b026ff;
        text-shadow: 0 0 10px #b026ff, 0 0 25px #8c1aff, 0 0 40px #450082;
        letter-spacing: 2px;
        text-align: center;
        margin-bottom: 5px;
    }
    .neon-subtitle {
        color: #00f0ff;
        letter-spacing: 3px;
        text-shadow: 0 0 8px rgba(0, 240, 255, 0.5);
        text-align: center;
        margin-bottom: 30px;
        border-bottom: 1px dashed #450082;
        padding-bottom: 20px;
    }
    .form-control, .form-select {
        background-color: #0a0214 !important;
        border: 1px solid #450082 !important;
        color: #00f0ff !important;
        border-radius: 6px;
        font-family: 'Consolas', monospace;
        padding: 12px;
    }
    .form-control:focus, .form-select:focus {
        border-color: #b026ff !important;
        box-shadow: 0 0 15px rgba(176, 38, 255, 0.3) !important;
        outline: none;
    }
    .form-label {
        color: #a28bce !important;
        font-weight: bold;
        font-size: 1.1em;
        margin-bottom: 10px;
    }
    .form-label span {
        color: #00f0ff;
    }
    .btn-cyber {
        background: transparent;
        color: #b026ff;
        border: 1px solid #b026ff;
        border-radius: 6px;
        font-family: 'Consolas', monospace;
        font-weight: bold;
        font-size: 1.2em;
        padding: 15px;
        width: 100%;
        transition: 0.3s all;
        box-shadow: 0 0 15px rgba(176, 38, 255, 0.1);
        text-transform: uppercase;
        letter-spacing: 2px;
    }
    .btn-cyber:hover:not(:disabled) {
        background: #b026ff;
        color: #000;
        box-shadow: 0 0 25px rgba(176, 38, 255, 0.6);
        cursor: pointer;
    }
    .btn-cyber:disabled {
        border-color: #450082;
        color: #450082;
        cursor: not-allowed;
    }

    .progress {
        background-color: #0a0214;
        border: 1px solid #450082;
        border-radius: 6px;
        height: 30px;
    }
    .progress-bar {
        background: linear-gradient(90deg, #450082, #b026ff);
        color: #f0e6ff !important;
        font-weight: bold;
        text-shadow: 1px 1px 2px #000;
        font-size: 1.1em;
    }
    .status-text {
        color: #6a4b8c;
        font-size: 0.9em;
        text-align: center;
        margin-bottom: 10px;
    }
</style>

<div class="row justify-content-center">
    <div class="col-md-8 col-lg-6">
        <div class="neon-container">
            
            <h1 class="neon-title">&gt;_ LOCAGEN_PRO</h1>
            <h5 class="neon-subtitle">sys.universal_translator</h5>

            <form id="translateForm">
                <div class="mb-4">
                    <label for="lang_file" class="form-label"><span>[+]</span> TARGET_JSON_FILE:</label>
                    <input class="form-control" type="file" id="lang_file" name="lang_file" accept=".json" required>
                </div>
                
                <div class="mb-5">
                    <label for="target_lang" class="form-label"><span>[+]</span> SELECT_MARKET:</label>
                    <select class="form-select" id="target_lang" name="target_lang" required>
                        <option value="Turkish">tr_TR (Turkish)</option>
                        <option value="English">en_US (English)</option>
                        <option value="German">de_DE (German)</option>
                        <option value="Spanish">es_ES (Spanish)</option>
                        <option value="French">fr_FR (French)</option>
                        <option value="Italian">it_IT (Italian)</option>
                    </select>
                </div>

                <button type="submit" id="submitBtn" class="btn-cyber">
                    [ INJECT_TRANSLATION ]
                </button>
            </form>

            <div id="progressArea" class="mt-4" style="display:none;">
                <p class="status-text" id="progressText">&gt;_ Establishing connection to AI core...</p>
                <div class="progress">
                    <div id="progressBar" class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: 0%;">0%</div>
                </div>
            </div>

            <div id="resultArea" class="mt-4" style="display:none;"></div>

        </div>
    </div>
</div>

<script>
document.getElementById('translateForm').addEventListener('submit', function(e) {
    e.preventDefault(); 
    
    let form = this;
    let formData = new FormData(form);
    let submitBtn = document.getElementById('submitBtn');
    let progressArea = document.getElementById('progressArea');
    let progressBar = document.getElementById('progressBar');
    let resultArea = document.getElementById('resultArea');
    let progressText = document.getElementById('progressText');

    submitBtn.disabled = true;
    submitBtn.innerText = "[ PROCESSING_DATA... ]";
    progressArea.style.display = 'block';
    resultArea.style.display = 'none';
    progressBar.style.width = '0%';
    progressBar.innerHTML = '0%';

    let width = 0;
    let animation = setInterval(function() {
        if (width >= 90) {
            clearInterval(animation);
            progressText.innerText = ">_ Awaiting neural network response...";
        } else {
            width += Math.floor(Math.random() * 5) + 1;
            if(width > 90) width = 90;
            progressBar.style.width = width + '%';
            progressBar.innerHTML = width + '%';
        }
    }, 400);

    fetch('process.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json()) 
    .then(data => {
        clearInterval(animation);
        progressBar.style.width = '100%';
        progressBar.innerHTML = '100%';
        progressText.innerText = ">_ Localization sequence complete!";
        
        setTimeout(() => {
            progressArea.style.display = 'none';
            submitBtn.disabled = false;
            submitBtn.innerText = "[ INJECT_ANOTHER_FILE ]";
            
            if (data.status === 'success') {
                resultArea.innerHTML = `
                    <div style="background: linear-gradient(180deg, #13002b, #0a0214); border: 1px solid #00f0ff; border-radius: 8px; padding: 25px; box-shadow: 0 0 20px rgba(0, 240, 255, 0.1); text-align: center; position: relative;">
                        <h3 style="color: #00f0ff; margin-top: 0; border-bottom: 1px dashed #00f0ff; padding-bottom: 10px;">
                            [+] SYSTEM_SUCCESS
                        </h3>
                        <p style="color: #e0d4f5; font-size: 1.1em; margin: 20px 0;">
                            Target Language: <strong style="color: #b026ff;">${data.target_lang}</strong>
                        </p>
                        <a href="${data.file_url}" download class="btn-cyber" style="display: block; text-decoration: none; border-color: #00f0ff; color: #00f0ff; box-shadow: 0 0 10px rgba(0,240,255,0.2);">
                            [ DOWNLOAD_LOCALIZED_FILE ]
                        </a>
                    </div>
                `;
                form.reset();
            } else {
                resultArea.innerHTML = `
                    <div style="background: #1a0505; border: 1px solid #ff003c; padding: 20px; border-radius: 8px; color: #ff003c; text-align: center; box-shadow: 0 0 15px rgba(255,0,60,0.2);">
                        <strong>[!] CRITICAL_ERROR:</strong><br> ${data.message}
                    </div>
                `;
            }
            resultArea.style.display = 'block';
        }, 800);
    })
    .catch(error => {
        clearInterval(animation);
        progressArea.style.display = 'none';
        submitBtn.disabled = false;
        submitBtn.innerText = "[ INJECT_TRANSLATION ]";
        
        resultArea.innerHTML = `
            <div style="background: #1a0505; border: 1px solid #ff003c; padding: 20px; border-radius: 8px; color: #ff003c; text-align: center;">
                <strong>[!] CONNECTION_LOST:</strong><br> Failed to reach API endpoints.
            </div>
        `;
        resultArea.style.display = 'block';
    });
});
</script>

<?php require_once 'includes/footer.php'; ?>