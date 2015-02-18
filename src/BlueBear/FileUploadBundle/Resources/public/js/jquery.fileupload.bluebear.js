(function($) {
    $.fn.blueBearFileUpload = function(options) {
        var widgetAlert = function(widget, text) {
            if (text) {
                widget.find('.alert').html(text).show();
            } else {
                widget.find('.alert').html('').hide();
            }
        };
        
        $(this).each(function() {
            var widget = $(this);

            widget.find('.fileupload-file .close').click(function(){
                widget.find('.fileupload-file').hide();
                widget.find('.fileupload-button').removeClass('disabled');
                widget.find('input[type="hidden"]').val('');
            });

            var defaultOptions = {
                dataType: 'json',
                add: function(e, data) {
                    var size    = data.originalFiles[0]['size'];
                    var maxsize = widget.find('input[type="file"]').attr('data-maxsize');
                    if (size == 0) {
                        widgetAlert(widget, 'File size is null');
                        return;
                    }
                    if (maxsize && size > maxsize) {
                        widgetAlert(widget, 'Exceeded maximum file size : ' + maxsize / 1000 / 1000 + 'Mb');
                        return;
                    }
                    widgetAlert(widget);
                    data.process().done(function () {
                        data.submit();
                    });
                },
                start: function(e) {
                    widgetAlert(widget);
                    widget.find('.progress')
                        .show()
                        .find('.progress-bar')
                        .css('width', '0%');
                },
                done: function(e, data) {
                    widgetAlert(widget);
                    if (data.result.files && data.result.files[0] && data.result.files[0].error) {
                        var error = data.result.files[0].error;
                        if (data._error_messages[error]) {
                            error = data._error_messages[error];
                        }
                        widget.find('.progress').hide();
                        widgetAlert(widget, error);
                        return;
                    }
                    var file = data.result[0];
                    widget.find('.help-block').remove();
                    widget.find('.fileupload-file').show();
                    widget.find('.fileupload-button').addClass('disabled');
                    widget.find('.fileupload-filename').html(file.originalFileName);
                    widget.find('.progress').hide();
                    widget.find('input[type="hidden"]').val(file.id);
                },
                fail: function(e, data) {
                    widget.find('.progress').hide();
                    widgetAlert(widget, 'An unknown error occurred during file upload');
                },
                progressall: function(e, data) {
                    var progress = parseInt(data.loaded / data.total * 100, 10);
                    widget.find('.progress-bar').css('width', progress + '%');
                },
                _error_messages: {
                    'error.whitelist': "File type not allowed",
                    'error.blacklist': "File type not allowed",
                    'error.maxsize': "Maximum file size exceeded"
                }
            };

            if (options) {
                jQuery.extend(defaultOptions, options);
            }

            widget.find('input[type="file"]').fileupload(defaultOptions);
        });
    };
    $(document).on('ready', function () {
        $('.fileupload-widget').blueBearFileUpload();
    });
})(jQuery);