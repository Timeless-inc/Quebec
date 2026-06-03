// Client-side upload validation: blocks files larger than configured limits
(function () {
    function bytesToKb(bytes) {
        return Math.ceil(bytes / 1024);
    }

    function getLimits() {
        try {
            const meta = document.querySelector('meta[name="upload-limits"]');
            if (!meta) return null;
            return JSON.parse(meta.getAttribute('content'));
        } catch (e) {
            return null;
        }
    }

    function showInlineError(input, message) {
        // Try to find a nearby .file-name element to place the error after, otherwise insert after input
        let container = input.closest('div') || input.parentElement;
        let fileNameElem = container ? container.querySelector('.file-name') : null;
        let errorElem = container ? container.querySelector('.file-error') : null;
        if (!errorElem) {
            // Use project alert styles (red) to match existing UI
            errorElem = document.createElement('div');
            errorElem.className = 'file-error bg-red-50 border-l-4 border-red-500 text-red-700 p-3 rounded-md mt-2 text-sm';
            errorElem.setAttribute('role', 'alert');
            if (fileNameElem && fileNameElem.parentElement) {
                fileNameElem.parentElement.insertBefore(errorElem, fileNameElem.nextSibling);
            } else if (container) {
                container.appendChild(errorElem);
            } else {
                input.parentElement.appendChild(errorElem);
            }
        }
        let strongElem = errorElem.querySelector('strong.font-semibold');
        if (!strongElem) {
            strongElem = document.createElement('strong');
            strongElem.className = 'font-semibold';
            errorElem.replaceChildren(strongElem);
        }
        strongElem.textContent = message;
    }

    function showInlineWarning(input, message) {
        let container = input.closest('div') || input.parentElement;
        let warnElem = container ? container.querySelector('.file-warning') : null;
        if (!warnElem) {
            warnElem = document.createElement('div');
            warnElem.className = 'file-warning bg-yellow-50 border-l-4 border-yellow-400 text-yellow-700 p-2 rounded-md mt-2 text-sm';
            warnElem.setAttribute('role', 'status');
            if (container) container.appendChild(warnElem);
            else input.parentElement.appendChild(warnElem);
        }
        let strongElem = warnElem.querySelector('strong.font-semibold');
        if (!strongElem) {
            strongElem = document.createElement('strong');
            strongElem.className = 'font-semibold';
            warnElem.replaceChildren(strongElem);
        }
        strongElem.textContent = message;
    }

    function clearInlineError(input) {
        let container = input.closest('div') || input.parentElement;
        let errorElem = container ? container.querySelector('.file-error') : null;
        if (errorElem) errorElem.remove();
    }

    function fileIsPdf(fileName) {
        return fileName.toLowerCase().endsWith('.pdf');
    }

    function fileIsImage(fileName) {
        return /\.(jpg|jpeg|png|webp)$/i.test(fileName);
    }

    function getImageDimensions(file) {
        return new Promise((resolve, reject) => {
            const reader = new FileReader();
            reader.onload = function (ev) {
                const img = new Image();
                img.onload = function () {
                    resolve({ width: img.width, height: img.height, dataUrl: ev.target.result });
                };
                img.onerror = function () { reject(new Error('Erro ao ler dimensoes da imagem')); };
                img.src = ev.target.result;
            };
            reader.onerror = function () { reject(new Error('Erro ao ler arquivo')); };
            reader.readAsDataURL(file);
        });
    }

    function compressImageFile(file, maxDimension, targetKb) {
        return new Promise((resolve, reject) => {
            const reader = new FileReader();
            reader.onload = function (ev) {
                const img = new Image();
                img.onload = function () {
                    let sw = img.width;
                    let sh = img.height;
                    let dw = sw;
                    let dh = sh;
                    if (sw > maxDimension || sh > maxDimension) {
                        const scale = Math.min(maxDimension / sw, maxDimension / sh);
                        dw = Math.max(1, Math.round(sw * scale));
                        dh = Math.max(1, Math.round(sh * scale));
                    }

                    const canvas = document.createElement('canvas');
                    canvas.width = dw;
                    canvas.height = dh;
                    const ctx = canvas.getContext('2d');
                    ctx.drawImage(img, 0, 0, dw, dh);

                    // attempt qualities descending until under target or min quality
                    (function tryQuality(quality) {
                        canvas.toBlob(function (blob) {
                            if (!blob) return reject(new Error('Falha ao gerar blob')); 
                            const kb = Math.ceil(blob.size / 1024);
                            if (kb <= targetKb || quality <= 0.5) {
                                resolve(blob);
                            } else {
                                tryQuality(quality - 0.1);
                            }
                        }, 'image/webp', quality);
                    })(0.9);
                };
                img.onerror = function () { reject(new Error('Erro ao carregar imagem para compressão')); };
                img.src = ev.target.result;
            };
            reader.onerror = function () { reject(new Error('Erro ao ler arquivo')); };
            reader.readAsDataURL(file);
        });
    }

    async function handleFilesChange(input, limits) {
        clearInlineError(input);
        const files = Array.from(input.files || []);
        if (!files.length) return true;

        const form = input.closest('form');
        const isApproveForm = !!(form && form.classList && form.classList.contains('approve-form'));
        if (isApproveForm) {
            limits = Object.assign({}, limits, {
                pdf: 5120,
                image: 10485760,
                client_compress_trigger_kb: 0,
                client_warning_kb: 0,
            });
        }

        const maxWidth = Number(limits.target_width || limits.image_target_max_width || 2048);
        const imageLimitKb = Number(limits.image || limits.image_upload_max_kb || 5120);
        const warnThresholdKb = Number(limits.client_warning_kb || 3072);
        const clientCompressTriggerKb = Number(limits.client_compress_trigger_kb || warnThresholdKb);
        const clientMaxPixels = Number(limits.client_max_pixels || Math.floor(Number(limits.image_max_pixels || 30000000) * 0.6));
        const pdfLimitKb = Number(limits.pdf || 2048);
        const totalLimitKb = Number(limits.total_per_request || limits.total_per_request_kb || 10240);

        const dt = new DataTransfer();
        for (let i = 0; i < files.length; i++) {
            const f = files[i];
            const name = (f.name || '').toLowerCase();

            if (fileIsPdf(name)) {
                const kb = Math.ceil(f.size / 1024);
                if (kb > pdfLimitKb) {
                    showInlineError(input, `❌ PDF "${f.name}" (${kb} KB) excede o limite de ${pdfLimitKb} KB.`);
                    try { input.value = ''; } catch (err) { }
                    return false;
                }
                dt.items.add(f);
                continue;
            }

            if (fileIsImage(name)) {
                const kb = Math.ceil(f.size / 1024);
                let dims = null;
                try {
                    dims = await getImageDimensions(f);
                } catch (e) {
                    dims = null;
                }

                const pixels = dims ? (dims.width * dims.height) : 0;
                const isHighResolution = !!dims && (dims.width > maxWidth || dims.height > maxWidth || pixels > clientMaxPixels);
                const shouldCompress = kb > imageLimitKb || kb >= clientCompressTriggerKb || isHighResolution;

                if (shouldCompress) {
                    try {
                        const targetKb = kb > imageLimitKb ? imageLimitKb : Math.min(imageLimitKb, Math.max(clientCompressTriggerKb, warnThresholdKb));
                        const blob = await compressImageFile(f, maxWidth, targetKb);
                        const newFile = new File([blob], f.name.replace(/\.[^.]+$/, '') + '.webp', { type: blob.type });
                        const newKb = Math.ceil(newFile.size / 1024);
                        if (newKb <= imageLimitKb) {
                            dt.items.add(newFile);
                            continue;
                        }

                        showInlineError(input, `❌ Imagem "${f.name}" (${kb} KB) não pode ser reduzida abaixo de ${imageLimitKb} KB.`);
                        try { input.value = ''; } catch (err) { }
                        return false;
                    } catch (e) {
                        showInlineError(input, `❌ Erro ao comprimir "${f.name}": ${e.message}`);
                        try { input.value = ''; } catch (err) { }
                        return false;
                    }
                }

                dt.items.add(f);
                continue;
            }

            // other files: enforce image limit as fallback
            const kbOther = Math.ceil(f.size / 1024);
            if (kbOther > imageLimitKb) {
                showInlineError(input, `❌ Arquivo "${f.name}" (${kbOther} KB) excede o limite de ${imageLimitKb} KB.`);
                try { input.value = ''; } catch (err) { }
                return false;
            }
            dt.items.add(f);
        }

        // Replace input files with possibly compressed files
        input.files = dt.files;

        // Validate total size AFTER potential compression
        const totalUploadKb = Array.from(input.files).reduce((sum, f) => sum + Math.ceil(f.size / 1024), 0);
        if (totalUploadKb > totalLimitKb) {
            showInlineError(
                input,
                `⚠️ Tamanho total (${totalUploadKb} KB) excede o limite de ${totalLimitKb} KB (${(totalLimitKb / 1024).toFixed(1)} MB). Remova alguns arquivos.`
            );
            try { input.value = ''; } catch (err) { }
            return false;
        }
        
        // update any UI filename display with file details
        const container = input.closest('div') || input.parentElement;
        const fileNameElem = container ? container.querySelector('.file-name') : null;
        if (fileNameElem) {
            const details = Array.from(input.files).map(f => {
                const sizeKb = Math.ceil(f.size / 1024);
                return `${f.name} (${sizeKb} KB)`;
            }).join(', ');
            
            if (input.files.length > 0) {
                fileNameElem.innerHTML = `✅ <strong>${input.files.length} arquivo(s)</strong> selecionado(s) - Total: <strong>${totalUploadKb} KB</strong><br><small>${details}</small>`;
                fileNameElem.className = 'file-name text-sm text-green-600 mt-2';
            } else {
                fileNameElem.textContent = 'Nenhum arquivo selecionado';
                fileNameElem.className = 'file-name text-gray-500 text-sm mt-2';
            }
        }

        return true;
    }

    function attachValidation(input, limits) {
        if (!input) return;
        input.addEventListener('change', function (e) {
            handleFilesChange(input, limits).then((isValid) => {
                input.dataset.uploadValid = isValid ? 'true' : 'false';
                updateFormSubmitState(input);
            }).catch(() => {
                input.dataset.uploadValid = 'false';
                updateFormSubmitState(input);
            });
        });
    }

    function updateFormSubmitState(input) {
        // Find the closest form
        const form = input.closest('form');
        if (!form) return;

        // Determine submit buttons inside form
        const submitButtons = form.querySelectorAll('button[type="submit"], input[type="submit"]');

        // If any file input has data-upload-valid === 'false', disable submit
        const fileInputs = form.querySelectorAll('input[type="file"]');
        let anyInvalid = false;
        let anyEmptyRequired = false;
        fileInputs.forEach(fi => {
            if (fi.dataset.uploadValid === 'false') anyInvalid = true;
            if (fi.required && (!fi.files || fi.files.length === 0)) anyEmptyRequired = true;
        });

        const disable = anyInvalid || anyEmptyRequired;
        submitButtons.forEach(btn => btn.disabled = disable);
    }

    document.addEventListener('DOMContentLoaded', function () {
        const limits = getLimits();
        if (!limits) return;

        // Normalize keys to expected names
        if (!limits.image && limits.image_upload_max_kb) limits.image = limits.image_upload_max_kb;
        if (!limits.target_width && limits.image_target_max_width) limits.target_width = limits.image_target_max_width;

        // Attach to all file inputs on the page
        const fileInputs = document.querySelectorAll('input[type="file"]');
        fileInputs.forEach(input => {
            // Initialize state
            input.dataset.uploadValid = 'true';
            attachValidation(input, limits);
        });

        // Watch for dynamically added file inputs (the page generates inputs on demand)
        const observer = new MutationObserver(function(mutationsList) {
            for (const mutation of mutationsList) {
                if (mutation.type === 'childList' && mutation.addedNodes.length) {
                    mutation.addedNodes.forEach(node => {
                        if (!(node instanceof HTMLElement)) return;
                        const addedFileInputs = node.querySelectorAll ? node.querySelectorAll('input[type="file"]') : [];
                        if (node.matches && node.matches('input[type="file"]')) {
                            // node itself is an input
                            node.dataset.uploadValid = 'true';
                            attachValidation(node, limits);
                        }
                        addedFileInputs.forEach(fi => {
                            fi.dataset.uploadValid = 'true';
                            attachValidation(fi, limits);
                        });
                    });
                }
            }
        });

        observer.observe(document.body, { childList: true, subtree: true });

        // Prevent form submission if client-side validations fail
        const forms = document.querySelectorAll('form');
        forms.forEach(form => {
            form.addEventListener('submit', function (e) {
                // check all file inputs inside form
                const fileInputs = form.querySelectorAll('input[type="file"]');
                for (const fi of fileInputs) {
                    if (fi.dataset.uploadValid === 'false') {
                        e.preventDefault();
                        fi.focus();
                        const container = fi.closest('div') || fi.parentElement;
                        const err = container ? container.querySelector('.file-error') : null;
                        if (err) {
                            err.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        }
                        return false;
                    }
                    if (fi.required && (!fi.files || fi.files.length === 0)) {
                        e.preventDefault();
                        fi.focus();
                        return false;
                    }
                }
            });
        });
    });
})();
