(() => {
    let filesList = document.querySelector(".files-list");
    let dropdownFileList = document.querySelector(".dropdown-file-list");
    let files = document.querySelector('#files');
    let filearea = document.querySelector('.filearea');
    let requestFiles = document.querySelector('.request-files');
    let requestFilesList = document.querySelector('.request-files-list');

    document.addEventListener("DOMContentLoaded", init, false);

    function init() {
        if (files) files.addEventListener('change', handleFileSelect, false);
        if (requestFilesList) requestFilesList.addEventListener('click', () => removeFile(event), false);
        if (filesList) filesList.addEventListener('click', () => removeFile(event), false);
    }

    function showFiles(files) {
        if (filesList) filesList.innerHTML = "";
        if (dropdownFileList) dropdownFileList.innerHTML = "";
        if (requestFilesList) requestFilesList.innerHTML = "";

        if (!files.length) {
            if (requestFilesList) requestFiles.style.visibility = '';
        }

        for (let i = 0; i < files.length; i++) {
            let f = files[i];

            if (filesList) {
                filesList.innerHTML += `
            <li>
                <button class="rounded file-button">
                    <img src="/local/templates/ades_help/assets/doc.svg" alt="">
                    <div class="filename">${f.name}</div>
    
                    <img src="/local/templates/ades_help/assets/cross.svg" data-remove="${i}" alt="">
                </button>
            </li>`;
            }

            if (dropdownFileList) {
                dropdownFileList.innerHTML += `
            <li class="dropdown-file-list-item">
                <img class="dropdown-file-list-icon" src="/local/templates/ades_help/assets/doc.svg" alt="">
                <span class="dropdown-file-list-filename">${f.name}</span>
                <img class="dropdown-file-list-remove-icon" src="/local/templates/ades_help/assets/cross.svg"
                     alt="" data-remove="${i}">
            </li>`;
            }

            if (requestFilesList) {
                requestFiles.style.visibility = 'visible';
                requestFilesList.innerHTML += `
                 <li class="request-files-list-item">
                    <img class="request-files-list-icon" src="/local/templates/ades_help/assets/doc.svg" alt="">
                    <span class="request-files-list-filename">${f.name}</span>
                    <img class="request-files-list-remove-icon" src="/local/templates/ades_help/assets/cross.svg"
                         alt="" data-remove="${i}">
                </li>
            `;
            }

        }
    }

    function handleFileSelect(e) {
        if (!e.target.files) return;

        let files = e.target.files;
        showFiles(files);

    }

    function removeFile(event) {
        if (!event.target.dataset.remove) return;

        let dataTransfer = new DataTransfer();
        for (let i = 0; i < files.files.length; i++) {
            if (i == event.target.dataset.remove) continue;
            dataTransfer.items.add(files.files[i]);
        }
        files.files = dataTransfer.files;

        showFiles(files.files);
    }
})();