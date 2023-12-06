$( document ).ready( function () {
    adjuntar_archivos();
});

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

function onSubmit(token) {
    document.getElementById("miFormulario").submit();
}

function validarFormulario() {
    // Obtenemos el valor del input
    var validador = document.getElementById('validador').value;
    // Verificamos si el mensaje está vacío
    if (validador === "") {
        // Permitimos que el formulario se envíe
        return true;
    } else {
        // Evitamos que el formulario se envíe
        return false;
    }
}