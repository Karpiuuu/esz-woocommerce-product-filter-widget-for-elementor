(function ($) {
    'use strict';
    $(document).ready(function () {

        if ($('#esz-term-filter-type').length > 0) {
            var EszTermFilterType = {
                eszFilterType: $('#esz-term-filter-type'),
                eszTermFieldGroupToggle: function (eszFilterTypeVal) {
                    if (eszFilterTypeVal === 'image') {
                        $('.esz-term-image-field').show()
                        $('.esz-term-color-field').hide()
                        $('.esz-term-color-field-2').hide()
                    } else if (eszFilterTypeVal === 'color') {
                        $('.esz-term-color-field').show()
                        $('.esz-term-color-field-2').hide()
                        $('.esz-term-image-field').hide()
                    } else if (eszFilterTypeVal === 'gradient') {
                        $('.esz-term-color-field').show()
                        $('.esz-term-color-field-2').show()
                        $('.esz-term-image-field').hide()
                    }
                }
            }

            EszTermFilterType.eszFilterType.change(function () {
                EszTermFilterType.eszTermFieldGroupToggle($(this).val());
            })
            EszTermFilterType.eszTermFieldGroupToggle(EszTermFilterType.eszFilterType.val());


            function eszTermMediaUpload(button_class) {
                var eszCustomMedia = true,
                    eszOrigSendAttachment = wp.media.editor.send.attachment;
                $('body').on('click', button_class, function (e) {
                    var button_id = '#' + $(this).attr('id');
                    var send_attachment_bkp = wp.media.editor.send.attachment;
                    var button = $(button_id);
                    eszCustomMedia = true;
                    wp.media.editor.send.attachment = function (props, attachment) {
                        if (eszCustomMedia) {
                            $('#esz-term-image-id').val(attachment.id);
                            $('#esz-term-image-wrapper').html('<img class="custom-media-image" src="" style="margin:0;padding:0;max-height:100px;float:none;" />');
                            $('#esz-term-image-wrapper .custom-media-image').attr('src', attachment.url).css('display', 'block');
                        } else {
                            return eszOrigSendAttachment.apply(button_id, [props, attachment]);
                        }
                    }
                    wp.media.editor.open(button);
                    return false;
                });
            }

            eszTermMediaUpload('.esz-tax-media-button.button');
            $('body').on('click', '.esz-tax-media-remove', function () {
                $('#esz-term-image-id').val('');
                $('#esz-term-image-wrapper').html('<img class="custom-media-image" src="" style="margin:0;padding:0;max-height:100px;float:none;" />');
            });
            $(document).ajaxComplete(function (event, xhr, settings) {
                var queryStringArr = settings.data.split('&');
                if ($.inArray('action=add-tag', queryStringArr) !== -1) {
                    var xml = xhr.responseXML;
                    $response = $(xml).find('term_id').text();
                    if ($response != "") {
                        // Clear the thumb image
                        $('#esz-term-image-wrapper').html('');
                    }
                }
            });
        }
    });

})(jQuery);