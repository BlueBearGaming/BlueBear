var PencilForm = {
    init: function (container) {
        container.find('.add-new-pencil-set').on('click', function () {
            var url = $(this).attr('href');
            var ajaxForm = container.find('.pencil-set-container');

            $.ajax({
                url: url,
                type: 'post',
                success: function (response) {
                    ajaxForm.html(response.data);
                    ajaxForm.removeClass('hide');
                    PencilForm.bindAjaxForm(ajaxForm);
                },
                error: function () {
                    alert('An error has occurred !');
                }
            });

            return false;
        });
    },

    bindAjaxForm: function (ajaxForm) {
        ajaxForm.find('form').on('submit', function () {
            var form = $(this);

            $.ajax({
                url: form.attr('action'),
                data: form.serialize(),
                type: 'post',
                success: function (response) {
                    if (response.code == 'ok') {
                        var select = form.parents('.form-group').find('select').empty();
                        var pencilSets = response.data;

                        $.each(pencilSets, function (index, pencil) {
                            select.append($('<option value="' + pencil.id + '">' + pencil.label + '</option>'));
                        });
                        ajaxForm.addClass('hide');
                    }
                    else {
                        ajaxForm.html(response.data);
                        PencilForm.bindAjaxForm(ajaxForm);
                    }

                }
            });
            return false;
        });
        ajaxForm.off().find('.submit .cancel').on('click', function () {
            ajaxForm.empty().addClass('hide');
            return false;
        });
    }
};

var Backoffice = {
    init: function () {
        // alert on deletion
        $('.item-delete').on('click', function () {
            return confirm('It will be deleted!!! Are ou really really really sure ? 100% ?');
        });
    }
};

$(document).on('ready', function () {
    Backoffice.init();
});