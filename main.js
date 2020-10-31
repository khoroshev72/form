(function() {
    'use strict';
    window.addEventListener('load', function() {
        // Fetch all the forms we want to apply custom Bootstrap validation styles to
        var forms = document.getElementsByClassName('needs-validation');
        // Loop over them and prevent submission
        var validation = Array.prototype.filter.call(forms, function(form) {
            form.addEventListener('submit', function(event) {
                if (form.checkValidity() === false) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                    form.classList.add('was-validated');
                if (form.checkValidity() === true) {
                    $.ajax({
                        url: 'index.php',
                        type: 'POST',
                        data: $('#form').serialize(),
                        beforeSend: function () {
                            $('.loader').fadeIn()
                        },
                        success: function (responce) {
                            $('.loader').fadeOut('slow', function () {
                                var res = JSON.parse(responce)
                                if (res.result == 'ok') {
                                    $('#form').removeClass('was-validated').trigger('reset')
                                    $('#label-captcha').text(res.captcha)
                                    $('.responce').html('<div class="alert alert-success">Письмо успешно отправлено</div>');
                                } else if (res.result == 'error'){
                                    var errors = res.errors
                                    $('.responce').html(errors)
                                } else if (res.result == 'fail'){
                                    var error = `<div class="alert alert-danger">${res.error}</div>`
                                    $('.responce').html(error)
                                }
                            })
                        },
                        error: function () {
                            alert('Ooops...try again')
                        }
                    });
                    event.preventDefault();
                    event.stopPropagation();
                }

            }, false);
        });
    }, false);
})();