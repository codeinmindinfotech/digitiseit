// $(function() {
//     $('#company_id').select2({
//         tags: true,
//         placeholder: "-- Select or Add Company --",
//         width: '100%'
//     });

//     // Auto update folder path
//     $('#company_id').on('change', function() {
//         let opt = $("#company_id option:selected");
//         let folderPath = opt.data('folder-path') || opt.text();
//         $('#directory_name').val(folderPath);
//     });
// });

document.addEventListener('DOMContentLoaded', function () {

    // Initialize Select2
    $('.select-2').select2({
        width: '100%',
        placeholder: "-- Select Company --",
        allowClear: true
    });

    // Update directory_name on company change
    $('#company_id').on('change', function () {
        const selected = $("#company_id option:selected");
        const folderPath = selected.data('folder-path') || selected.text();
        $('#directory_name').val(folderPath);
    });

    // Form validation
    $('form').on('submit', function(e) {
        let valid = true;

        $(this).find('input[required], select[required], textarea[required]').each(function() {
            let value;

            // Handle Select2 fields
            if ($(this).hasClass('select-2')) {
                value = $(this).val();
            } else {
                value = $(this).val().trim();
            }

            // Remove previous errors
            $(this).removeClass('is-invalid');
            $(this).next('.invalid-feedback').remove();

            // Required validation
            if (!value || (Array.isArray(value) && value.length === 0)) {
                valid = false;
                $(this).addClass('is-invalid');
                $(this).after('<div class="invalid-feedback">This field is required</div>');
            }

            // Email validation
            if ($(this).attr('type') === 'email' && value) {
                const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!regex.test(value)) {
                    valid = false;
                    $(this).addClass('is-invalid');
                    $(this).after('<div class="invalid-feedback">Invalid email address</div>');
                }
            }
        });

        if (!valid) e.preventDefault(); // Stop submission if any field is invalid
    });
});
