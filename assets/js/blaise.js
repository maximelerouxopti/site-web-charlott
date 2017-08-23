$(function() {
    var form = $('#subForm'),
        requiredFields = $("input[required]"),
        emphasize = "inset 0px 2px 5px 0px rgba(0, 0, 0, 0.05), 0px 1px 0px 0px rgba(255, 255, 255, 0.025), inset 0px 0px 2px 1px #f02e33";
    
    $(form).submit(function(event) {
        // Prevent the default behavior
        event.preventDefault();
        
        // Form validity
        var isValid = true;
        form.find(requiredFields).each(function() {
            $(this).css('box-shadow', '');

            // If field is empty
            if (!$.trim($(this).val())) {
                $(this).css('box-shadow', emphasize);
                isValid = false;
            }

            // Email validity
            var email_reg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
            if ($(this).attr("type") == "email" && !email_reg.test($.trim($(this).val()))) {
                $(this).css('box-shadow', emphasize);
                isValid = false;
            }
        });

        // Ajax request if the form is valid
        if(isValid) {
            var formData = $(form).serialize(),
                button = $("#submitButton");
            $.ajax({
                type: 'POST',
                url: $(form).attr('action'),
                data: formData
            }).done(function(response) {
                button.css('background-color', 'green');
                button.val("Inscription validée");
                button.prop("disabled", true);
            }).fail(function(data) {
                button.css('background-color', 'red');
                button.val("Erreur");
            });
        }

        // Reset the error while editing
        form.find(requiredFields).keyup(function () {
            $(this).css('box-shadow', '');
        });
    });

    var cat = $("#category .categories article");

    $(cat).click(function(event) {
        $(".style8 .description article div#no-cat").hide();
        $(".style8 .description article div[id^='cat']").hide();
        $(".style8 .description article div#"+$(this).data("cat")).show();
    })
});