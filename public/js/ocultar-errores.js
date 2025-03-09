document.addEventListener("DOMContentLoaded", function () {
    const fields = document.querySelectorAll("input, select");

    fields.forEach((field) => {
        field.addEventListener("input", function () {
            const errorElement = field.closest(".error-row, .form-group")?.querySelector(".error-message");
            if (errorElement) {
                errorElement.remove();
            }
        });
    });

    // Manejar los radio buttons correctamente
    const radioButtons = document.querySelectorAll('input[type="radio"]');

    radioButtons.forEach((radio) => {
        radio.addEventListener("change", function () {
            const groupName = radio.getAttribute("name");

            const questionContainer = radio.closest(".question-row, .form-group, .table-row");
            if (questionContainer) {
                const groupRadios = document.querySelectorAll(`input[name="${groupName}"]`);
                groupRadios.forEach((r) => {
                    const errorElement = r.closest(".error-row, .form-group, .table-row")?.querySelector(".error-row");
                    if (errorElement) {
                        errorElement.remove();
                    }
                });
            }    
        });
    });
});
