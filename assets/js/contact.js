







var fileList = {};
var fileArreglo = [];
var historialArchivos = [];




$( document ).ready( function () {
    adjuntar_archivos();
});



var adjuntar_archivos = () => {
    const dropArea = $(".drag-area");
    const dragText = dropArea.find("label");
    const dragToUploadForm = $("#dragToUploadForm");
    const inputFile = dropArea.find("#inputFile");
    let files = {};


    /*dragToUploadForm.on("submit", function (e) {
        console.log(files)
        e.preventDefault();
        if (files) {
            this.submit();
        }
    });*/


    dropArea.on("mouseenter", function () {
        dropArea.addClass("active");
    });
    dropArea.on("mouseleave", function () {
        dropArea.removeClass("active");
    });


    inputFile.on("change", function (e) {
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
        files = event.originalEvent.dataTransfer.files;
        showFile(files);
    });


    function showFile(files) {
        $.each(files, function (index, file) {
            let fileSize = file.size;
            const maxSize = 200 * 1024 * 1024;
            if (!fileExists(file)) {
                if (fileSize <= maxSize) {
                    fileList[Object.keys(fileList).length] = file
                    fileArreglo = Object.values(fileList);
                    const fileInfo = `<tr><td style="font-size: 12px; font-weight: 400; color:#495057;">${file.name}</td>
<td style="font-size: 12px; font-weight: 400; color:#495057;">Tamaño: ${formatFileSize(fileSize)}</td>
<td class ="btn-end"><button href="#" class='btn btn-raised btn-outline-primary btn-min-width mr-1 mb-0 btn_quitar' title='Eliminar archivo'><i class="bi bi-trash3"></i></button></td></tr>`;
                    $("#preview").append(fileInfo);
                } else {
                   alert('El archivo excede el tamaño máximo permitido (200 MB).', 'Excede el tamaño máximo');
                }
            }
        });
        dragText.html('<i class="bi bi-cloud-upload" aria-hidden="true"></i> Arrastra y suelta para cargar archivos');
        dropArea.removeClass("active");
    }


    function fileExists(file) {
        const fileListValues = Object.values(fileList);
        return fileListValues.some((f) => f.name === file.name && f.size === file.size);
    }
}


$("#preview").on("click", ".btn_quitar", function () {
    fileList = {};
    var fila = $(this).closest('tr');
    var numeroFila = fila.index();
    fileArreglo.splice(numeroFila, 1);
    fileArreglo.forEach(function (elemento, indice) {
        fileList[indice] = elemento;
    });
    $(this).closest("tr").remove();
});


function formatFileSize(bytes) {
    const sizes = ["Bytes", "KB", "MB", "GB", "TB"];
    if (bytes === 0) {
        return "0 Byte";
    }
    const i = parseInt(Math.floor(Math.log(bytes) / Math.log(1024)));
    return Math.round(bytes / Math.pow(1024, i), 2) + " " + sizes[i];
}

