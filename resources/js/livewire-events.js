window.addEventListener("success-alert", (event) => {
    const isDarkMode = document.documentElement.classList.contains("dark");
    const { title, text } = event.detail;
    Swal.fire({
        title,
        text,
        icon: "success",
        background: isDarkMode ? "#1f2937" : "#f9fafb",
        color: isDarkMode ? "#f9fafb" : "#111827",
        confirmButtonColor: isDarkMode ? "#3b82f6" : "#2563eb",
    });
});

/* Alerta de intento de borrado */
window.addEventListener("delete-attempt", (event) => {
    const isDarkMode = document.documentElement.classList.contains("dark");
    const { title, text, model, id } = event.detail;

    Swal.fire({
        title,
        text,
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Sí, eliminar",
        cancelButtonText: "Cancelar",
        background: isDarkMode ? "#1f2937" : "#f9fafb",
        color: isDarkMode ? "#f9fafb" : "#111827",
        confirmButtonColor: isDarkMode ? "#ef4444" : "#dc2626",
        cancelButtonColor: isDarkMode ? "#3b82f6" : "#2563eb",
    }).then((result) => {
        if (result.isConfirmed) {
            const deleteEvent = new CustomEvent(`delete${model}`, {
                detail: { ...event.detail, id },
            });
            window.dispatchEvent(deleteEvent);
        }
    });
});

/* ALERTA DE ERROR EN ACTUALIZACIÓN */

window.addEventListener("validation-alert", (event) => {
    const isDarkMode = document.documentElement.classList.contains("dark");
    const { errors } = event.detail;

    Swal.fire({
        title: "No se ha realizado ningún cambio",
        text: `${errors}.`,
        icon: "error",
        background: isDarkMode ? "#1f2937" : "#f9fafb",
        color: isDarkMode ? "#f9fafb" : "#111827",
        confirmButtonColor: isDarkMode ? "#3b82f6" : "#2563eb",
    });
});

window.addEventListener("error-alert", (event) => {
    const isDarkMode = document.documentElement.classList.contains("dark");
    const { errors, title, text } = event.detail;

    Swal.fire({
        title,
        text,
        icon: "error",
        background: isDarkMode ? "#1f2937" : "#f9fafb",
        color: isDarkMode ? "#f9fafb" : "#111827",
        confirmButtonColor: isDarkMode ? "#3b82f6" : "#2563eb",
    });
});

window.addEventListener("info-alert", (event) => {
    const isDarkMode = document.documentElement.classList.contains("dark");
    const { title, text } = event.detail;

    Swal.fire({
        title,
        text,
        icon: "info",
        background: isDarkMode ? "#1f2937" : "#f9fafb",
        color: isDarkMode ? "#f9fafb" : "#111827",
        confirmButtonColor: isDarkMode ? "#3b82f6" : "#2563eb",
    });
});

document.addEventListener("DOMContentLoaded", function () {
    // Escuchar evento Livewire personalizado
    Livewire.on("copias-creadas", function (data) {
        const { barCodeIDs, cantidad } = data;

        Swal.fire({
            title: "¿Descargar etiquetas?",
            text: `Se han registrado ${cantidad}ejemplares. ¿Deseas descargar las etiquetas con sus códigos de barra?`,
            icon: "success",
            showCancelButton: true,
            confirmButtonText: "Sí, descargar",
            cancelButtonText: "No, gracias",
            customClass: {
                confirmButton:
                    "bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700",
                cancelButton:
                    "bg-gray-300 text-gray-800 px-4 py-2 rounded hover:bg-gray-400",
            },
            buttonsStyling: false,
        }).then((result) => {
            if (result.isConfirmed) {
                // Construir la URL con los IDs para el PDF
                const url =
                    `/etiquetas/pdf?` +
                    barCodeIDs.map((id) => `ids[]=${id}`).join("&");
                window.open(url, "_blank");
                console.log("URL generada:", url);
            }
        });
    });
});
