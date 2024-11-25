$( document ).ready( function () {
    adjuntar_archivos();
});

/* ========================= Documentos Adjuntos en el Formulario de Contacto ===================== */
var adjuntar_archivos = () => {
    const dropArea = $(".drag-area");
    const dragText = dropArea.find("label");
    const inputFile = $("#inputFile");

    dropArea.on("mouseenter", function () {
        dropArea.addClass("active");
    });
    dropArea.on("mouseleave", function () {
        dropArea.removeClass("active");
    });

    inputFile.on("change", function (event) {
        event.preventDefault();
        const files = this.files;
        dropArea.addClass("active");
        showFile(files);
    });

    dropArea.on("dragover", function (event) {
        event.preventDefault();
        dropArea.addClass("active");
        dragText.html('<i class="bi bi-cloud-upload" aria-hidden="true"></i> Suelta para subir los archivos');
    });


    dropArea.on("dragleave", function () {
        dropArea.removeClass("active");
        dragText.html('<i class="bi bi-cloud-upload" aria-hidden="true"></i> Arrastra y suelta para cargar archivos');
    });


    dropArea.on("drop", function (event) {
        event.preventDefault();
        const files = event.originalEvent.dataTransfer.files;
        inputFile[0].files = files;
        showFile(files);
    });


    function showFile(files) {
        $("#preview").html("")
        $.each(files, function (index, file) {
            let fileSize = file.size;
            const maxSize = 100 * 1024 * 1024;
            let fileType = file.type; //getting selected file type
            let validExtensions = ["image/jpeg", "image/jpg", "image/png", "application/pdf"]; //adding some valid image extensions in array
            if (fileSize <= maxSize){
                if (validExtensions.includes(fileType)) {
                //if user selected file is an image file
                let fileReader = new FileReader(); //creating new FileReader object
                fileReader.onload = () => {
                    const fileInfo = `<tr><td style="font-size: 12px; font-weight: 400; color:#495057;">${file.name}</td>
                                        <td style="font-size: 12px; font-weight: 400; color:#495057;">Tamaño: ${formatFileSize(fileSize)}</td>
                                        </tr>`;
                    $("#preview").append(fileInfo);
                };
                fileReader.readAsDataURL(file);
                } else {
                    alert('Solo se permiten archivos con extención pdf, jpge, jpg y png.', 'Formato archivo no permitido');
                }
            } else {
                alert('El archivo excede el tamaño máximo permitido (100 MB).', 'Excede el tamaño máximo');
            }
        });
        dragText.html('<i class="bi bi-cloud-upload" aria-hidden="true"></i> Arrastra y suelta para cargar archivos');
        dropArea.removeClass("active");
    }
}

function formatFileSize(bytes) {
    const sizes = ["Bytes", "KB", "MB", "GB", "TB"];
    if (bytes === 0) {
        return "0 Byte";
    }
    const i = parseInt(Math.floor(Math.log(bytes) / Math.log(1024)));
    return Math.round(bytes / Math.pow(1024, i), 2) + " " + sizes[i];
}

/* ========================= Fin Documentos Adjuntos en el Formulario de Contacto ===================== */

/* ========================= Input Pais en formulario Contacto ===================== */

const countries = [
    "Argentina", "Bolivia", "Brasil", "Chile", "Colombia", "Costa Rica", "Cuba",
    "Ecuador", "El Salvador", "España", "Guatemala", "Honduras", "México",
    "Nicaragua", "Panamá", "Paraguay", "Perú", "Puerto Rico", "República Dominicana",
    "Uruguay", "Venezuela"
];

const input = document.getElementById('pais');
const suggestionsList = document.getElementById('suggestions');
let selectedIndex = -1;

input.addEventListener('input', showSuggestions);
input.addEventListener('keydown', handleKeyDown);
input.addEventListener('focus', showSuggestions);
suggestionsList.addEventListener('click', handleSuggestionClick);

document.addEventListener('click', function(e) {
    if (e.target !== input && e.target !== suggestionsList) {
        suggestionsList.style.display = 'none';
    }
});

function showSuggestions() {
    const inputValue = input.value.toLowerCase();
    const filteredCountries = countries.filter(country =>
        country.toLowerCase().startsWith(inputValue)
    );

    suggestionsList.innerHTML = '';
    if (filteredCountries.length > 0 && inputValue) {
        filteredCountries.forEach(country => {
            const li = document.createElement('li');
            li.textContent = country;
            suggestionsList.appendChild(li);
        });
        suggestionsList.style.display = 'block';
    } else {
        suggestionsList.style.display = 'none';
    }
    selectedIndex = -1;
}

function handleKeyDown(e) {
    const suggestions = suggestionsList.querySelectorAll('li');
    if (e.key === 'ArrowDown') {
        selectedIndex = (selectedIndex < suggestions.length - 1) ? selectedIndex + 1 : selectedIndex;
        updateSelectedSuggestion();
    } else if (e.key === 'ArrowUp') {
        selectedIndex = (selectedIndex > 0) ? selectedIndex - 1 : 0;
        updateSelectedSuggestion();
    } else if (e.key === 'Enter' && selectedIndex >= 0) {
        input.value = suggestions[selectedIndex].textContent;
        suggestionsList.style.display = 'none';
    }
}

function updateSelectedSuggestion() {
    const suggestions = suggestionsList.querySelectorAll('li');
    suggestions.forEach((suggestion, index) => {
        if (index === selectedIndex) {
            suggestion.classList.add('selected');
            suggestion.scrollIntoView({ block: 'nearest' });
        } else {
            suggestion.classList.remove('selected');
        }
    });
}

function handleSuggestionClick(e) {
    if (e.target.tagName === 'LI') {
        input.value = e.target.textContent;
        suggestionsList.style.display = 'none';
    }
}

/* ========================= Fin Input Pais en formulario Contacto ===================== */