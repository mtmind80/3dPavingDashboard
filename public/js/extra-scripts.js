$(document).ready(function () {

    checkSize();

    var waitForFinalEvent=function(){var b={};return function(c,d,a){a||(a="I am a banana!");b[a]&&clearTimeout(b[a]);b[a]=setTimeout(c,d)}}();
    var fullDateString = new Date();

    $(window).resize(function () {
        waitForFinalEvent(function(){
            checkSize();
        }, 100, fullDateString.getTime())
    });

    /** Spinner  (needs spinner.css) **/
    $(function(){
        var $window = $(window),
            width = $window.width(),
            height = $window.height();

        setInterval(function() {
            if ((width != $window.width()) || (height != $window.height())) {
                width = $window.width();
                height = $window.height();

                updateSpinnerDivSize();
            }
        }, 200);
    });
    /* END Spinner */

    $('body').on('click', '.actions .action[data-action="route"]', function(){
        window.location = $(this).data('route');
    });

    /// from backed

    $('#pageItems').change(function(){
        window.location.href = $(this).val();
    });

    $('#needle').blur(function(){
        $(this).parents('label').removeClass('state-error').next('em').remove();
    });

    $('#searchForm').validate({
        rules: {
            needle: {
                required: true,
                minlength: 3,
                text     : true
            }
        }
    });

    $('#searchForm input').focusout(function(ev){
        ev.stopPropagation();
        ev.preventDefault();

        let form = $(this).closest('form');

        if (!$(ev.relatedTarget).hasClass('btn')) {
            $(this).val('');
        }

        form.find('em.state-error').remove();
        form.find('.field.state-error').removeClass('state-error');
    });

    $('.search-container').on('click', '.reset-button', function(){
        window.location.href = $(this).data('route');
    });

    // field.select

    $('.field.select select').change(function(){
        let el = $(this);

        if (el.val() >= 1) {
            let container = el.closest('.field.select');
            container.closest('.field.select').removeClass('state-error');
            container.next('em.state-error').remove();
        }
    });

    $('body').on('click', '.actions .action[data-action="delete"]', function(){
        if ($(this).attr('data-text')) {
            uiConfirm({callback: 'confirmDelete', text: $(this).attr('data-text'), params: [$(this).attr('data-id')]});
        } else {
            uiConfirm({callback: 'confirmDelete', params: [$(this).attr('data-id')]});
        }
    });

    $('body').on('click', '.actions .action[data-action="eliminar"]', function(){
        if ($(this).attr('data-text')) {
            uiConfirm({callback: 'confirmDelete', text: $(this).attr('data-text'), params: [$(this).attr('data-id')], spanish: true});
        } else {
            uiConfirm({callback: 'confirmDelete', params: [$(this).attr('data-id')], spanish: true});
        }
    });

    $('body').on('click', '.actions .action[data-action="delete_ban"]', function(){
        if ($(this).attr('data-text')) {
            uiConfirm({callback: 'confirmDeleteAndBanEmail', text: $(this).attr('data-text'), params: [$(this).attr('data-id')]});
        } else {
            uiConfirm({callback: 'confirmDeleteAndBanEmail', params: [$(this).attr('data-id')]});
        }
    });

    $('body').on('click', '.actions .action[data-action="eliminar_prohibir"]', function(){
        if ($(this).attr('data-text')) {
            uiConfirm({callback: 'confirmDeleteAndBanEmail', text: $(this).attr('data-text'), params: [$(this).attr('data-id')], spanish: true});
        } else {
            uiConfirm({callback: 'confirmDeleteAndBanEmail', params: [$(this).attr('data-id')], spanish: true});
        }
    });

    $('body').on('click', '.actions .action[data-action="confirm"]', function(){
        uiConfirm({callback: $(this).attr('data-callback'), text: $(this).attr('data-text'), params: [$(this).attr('data-id')]});
    });

    $('body').on('click', '.actions .action[data-action="confirmar"]', function(){
        uiConfirm({callback: $(this).attr('data-callback'), text: $(this).attr('data-text'), params: [$(this).attr('data-id')], spanish: true});
    });

    $('.admin-form').on('click', '.btn-bulk-action', function(){
        var bulkButton = $(this);
        var container = bulkButton.closest('.admin-form');
        var form = bulkButton.closest('form');
        var formId = form.attr('id');
        var table = container.find('table');
        var rowCheckboxesChecked = table.find('tbody .row-checkbox:checked');
        var lang = typeof bulkButton.data('lang') !== 'undefined' ? bulkButton.data('lang') : 'en';

        if (rowCheckboxesChecked.size() == 0) {
            notify({message: lang == 'en' ? 'There is no element selected.' : 'No hay elemento seleccionado.', title: 'Error', type: 'error', delay: 5000, lang: lang});
        } else {
            var ids = [];
            rowCheckboxesChecked.each(function(){
                ids.push($(this).val());
            });

            form.find('input:hidden[name="str_cid"]').val(ids.join('|'));

            if (bulkButton.data('confirm') == "1") {
                confirmation({title: 'Warning', type: 'warning', lang: lang,
                    confirm_function: function(){
                        $('#'+formId).submit();
                    },
                    cancel_function: function(){
                        rowCheckboxesChecked.each(function(){
                            $(this).removeAttr('checked');
                        });
                    }
                });
            } else {
                form.submit();
            }
        }
    });

    $('.list-table').on('click', '.toggle-checkbox', function(){
        var toggleCheckbox = $(this);
        var table = toggleCheckbox.closest('table');
        var rowCheckboxes = table.find('tbody .row-checkbox');

        if (toggleCheckbox.is(':checked')) {
            rowCheckboxes.each(function(){
                $(this).prop('checked', true);
            });
        } else {
            rowCheckboxes.each(function(){
                $(this).removeAttr('checked');
            });
        }
    });

    // table list with column having action dropdown menu:

    /** popover for i.help */
    $('.i-help').popover({
        placement: $(this).data('placement'),
        html: 'true',
        title: '<span class="text-info"><strong>Hint</strong></span> <button type="button" class="close">&times;</button>',
        content : $(this).data('content')
    });
    $('body').on('click', '.popover .close', function(){
        $(this).parents('.popover').popover('hide');
    });

    // remove previous validation error when select an option
    $('.select2-widget').change(function(ev){
        if ($(this).val() != 0) {
            var container = $(this).parents('.field').removeClass('state-error');
            if ($('#' + $(this).attr('id') + '-error')) {
                $('#' + $(this).attr('id') + '-error').remove();
            }
        }
    });

    var guiTextAreas = $('.gui-textarea');

    if (guiTextAreas) {
        guiTextAreas.focus(function(){
            $(this).addClass('expanded');
        });

        $('body').click(function(ev){
            if (!guiTextAreas.is(':focus') && !$(ev.target).is('.action-btn')) {
                guiTextAreas.removeClass('expanded');
            }
        });
    }

    $('body .list-table').on('shown.bs.dropdown', function (e) {
        var $table = $(this),
            delta = 30,
            $menu = $(e.target).find('.dropdown-menu'),
            tableOffsetHeight = $table.offset().top + $table.height(),
            menuOffsetHeight = $menu.offset().top + $menu.outerHeight(true) - delta;

        if (menuOffsetHeight > tableOffsetHeight) {
            $table.css('margin-bottom', menuOffsetHeight - tableOffsetHeight);
        }
    });

    $('body .list-table').on('hide.bs.dropdown', function () {
        $(this).css('margin-bottom', 0);
    });

    var uploadFileWidgets = $('.upload-file-widget');

    if (uploadFileWidgets.length > 0) {
        uploadFileWidgets
            .on('change', '.gui-file', function() {
                var guiFile = $(this);
                var container = guiFile.closest('.upload-file-widget');
                var guiInput = container.find('.gui-input');
                var removeFileLink = container.find('.remove-file-link');
                var guiFileVal = guiFile.val().replace(/(\\|\/)/g, '||');
                var tokens = guiFileVal.split('||');
                var hiddenFileName = container.find('.hidden-file-name');
                var newFilName = tokens[tokens.length-1];
                guiInput.val(newFilName);
                hiddenFileName.val(newFilName);
                removeFileLink.removeClass('hidden');
                container.removeClass('validation-error');
                container.find('span.error-message').remove();
            })
            .on('click', '.remove-file-link', function() {
                var removeFileLink = $(this);
                var container = removeFileLink.closest('.upload-file-widget');
                var lang = container.data('lang');
                var guiInput = container.find('.gui-input');
                var guiFile = container.find('.gui-file');
                var hiddenFileName = container.find('.hidden-file-name');
                hiddenFileName.val('');
                guiFile.val('');
                guiInput.val('').attr('placeholder', (lang === 'en' ? 'Upload file' : 'Subir fichero'));
                removeFileLink.addClass('hidden');
            });

        uploadFileWidgets.each(function(){
            var container = $(this);
            var removeFileLink = container.find('.remove-file-link');
            var hiddenFileName = container.find('.hidden-file-name');
            if (hiddenFileName.val() !== '') {
                removeFileLink.removeClass('hidden');
            }
        });

        // validation
        uploadFileWidgets.on('change', 'input:file', function(){
            var fileField = $(this);
            var container = fileField.closest('.upload-file-widget');

            container.removeClass('validation-error');
            container.find('span.error-message').remove();

            var fakeField = container.find('.fake-file-input-field');
            if (fakeField.length > 0) {
                fakeField.val(fileField.val());
            }
        })
    }

    $('#sortable_body').sortable({
        containment: 'parent',
        start: function(event, ui){
            $(ui.item.context).addClass('drag');
        },
        stop: function(event, ui){
            $(ui.item.context).removeClass('drag');
        },
        update: function(event, ui){
            $('#button_update_order').removeClass('hidden');
        }
    });

    $('#button_update_order').click(function(ev){
        ev.preventDefault();

        var strCid = [];
        $('#sortable_body tr').each(function(){
            strCid.push($(this).attr('data-id'));
        });

        $('#reorder_str_cid').val(strCid.join(','));
        $('#reorderForm').submit();
    });
});

function checkSize()
{
    $('body').removeClass('xs sm md lg not-xs');

    var windowWidth = $(window).width();

    if (windowWidth < 576) {
        $('body').addClass('xs');
    } else if (windowWidth < 768) {
        $('body').addClass('sm  not-xs');
    } else if (windowWidth < 992) {
        $('body').addClass('md not-xs');
    } else {
        $('body').addClass('lg not-xs');
    }
}

Number.prototype.format = function(n, x) {
    var re = '\\d(?=(\\d{' + (x || 3) + '})+' + (n > 0 ? '\\.' : '$') + ')';
    return this.toFixed(Math.max(0, ~~n)).replace(new RegExp(re, 'g'), '$&,');
};

function urlQueryString(url)
{
    if (typeof url == 'undefined') {
        return false;
    }

    var varsArr = url.split('?');

    if (varsArr.length != 2) {
        return false;
    }

    return varsArr[1];
}

function get_center_pos(width, top){
    // top is empty when creating a new notification and is set when recentering
    if (!top){
        top = 30;
        // this part is needed to avoid notification stacking on top of each other
        $('.ui-pnotify').each(function() { top += $(this).outerHeight() + 20; });
    }

    return {"top": top, "left": ($(window).width() / 2) - (width / 2)}
}

function notify(params)
{
    // params: message, title, type, top, class. delay = 0 => always visible
    new PNotify({
        title: (typeof params.title != 'undefined') ? params.title : (typeof params.lang != 'undefined' && params.lang == 'es' ? 'Notificación' : 'Notification'),
        text: (typeof params.message != 'undefined') ? params.message : (typeof params.lang != 'undefined' && params.lang == 'es' ? '¡Hola!' : 'Hello!'),
        opacity: 0.90,
        type: (typeof params.type != 'undefined') ? params.type : 'info',
        delay: (typeof params.delay != 'undefined') ? params.delay : 5000,
        hide: (typeof params.delay == 'undefined' || params.delay != 0),
        addClass: (typeof params.class != 'undefined') ? params.class : '',     // Hola!'mt50'
        before_open: function (PNotify) {
            //  PNotify.get().css(get_center_pos(PNotify.get().width(), (typeof params.top != 'undefined' ? params.top : null)));
        },
        after_open: function (PNotify) {
            PNotify.get().css(get_center_pos(PNotify.get().width(), PNotify.get().css('top')));
        },
        before_close: (typeof params.before_close != 'undefined') ? params.before_close : null,
        after_close: (typeof params.after_close != 'undefined') ? params.after_close : null
    });
}

function confirmation(params)
{
    // params: message, title, type, top, class. delay = 0 => always visible
    new PNotify({
        title: (typeof params.title != 'undefined') ? params.title : (typeof params.lang != 'undefined' && params.lang == 'es' ? '&iexcl;Cuidado!' : 'Warning'),
        text: (typeof params.message != 'undefined') ? params.message : (typeof params.lang != 'undefined' && params.lang == 'es' ? '&iquest;Est&aacute; seguro?' : 'Are you sure?'),
        opacity: 0.90,
        type: (typeof params.type != 'undefined') ? params.type : 'warning',
        addClass: (typeof params.class != 'undefined') ? params.class : 'admin-panel-note',
        hide: false,
        animate_speed: 'fast',
        modal: true,
        buttons: {
            closer: false,
            sticker: false
        },
        confirm: {
            confirm: true,
            buttons: [{
                text: typeof params.button_confirm_caption != 'undefined' ? params.button_confirm_caption : (typeof params.lang != 'undefined' && params.lang == 'es' ? 'Confirmar' : 'Confirm'),
                addClass: 'btn-sm btn-primary mt10'
            }, {
                text: typeof params.button_cancel_caption != 'undefined' ? params.button_cancel_caption : (typeof params.lang != 'undefined' && params.lang == 'es' ? 'Cancelar' : 'Cancel'),
                addClass: 'btn-sm btn-default mt10'
            }],
            history: {
                history: false
            }
        },
        before_open: function (PNotify) {
            //  PNotify.get().css(get_center_pos(PNotify.get().width(), (typeof params.top != 'undefined' ? params.top : null)));
        },
        after_open: function (PNotify) {
            PNotify.get().css(get_center_pos(PNotify.get().width(), PNotify.get().css('top')));
        }
    })
    .get()
    .on('pnotify.confirm', typeof params.confirm_function == 'function' ? params.confirm_function : function(){
        if (typeof params.confirm_function != 'undefined') {
            executeFunctionByName(params.confirm_function, window, typeof params.confirm_args != 'undefined' ? params.confirm_args : null);
        } else {
            console.log('confirmed');
        }
        return;
    })
    .on('pnotify.cancel', typeof params.cancel_function == 'function' ? params.cancel_function : function(){
        if (typeof params.cancel_function != 'undefined') {
            executeFunctionByName(params.cancel_function, window, typeof params.cancel_args != 'undefined' ? params.cancel_args : null);
        } else {
            console.log('canceled');
        }
        return;
    });
}

function executeFunctionByName(functionName, context /*, args */) {
    var args = Array.prototype.slice.call(arguments, 2);
    var namespaces = functionName.split(".");
    var func = namespaces.pop();
    for(var i = 0; i < namespaces.length; i++) {
        context = context[namespaces[i]];
    }
    return context[func].apply(context, args);
}

$.fn.serializeObject = function(){
    var o = {};
    var a = this.serializeArray();
    $.each(a, function() {
        if (o[this.name]) {
            if (!o[this.name].push) {
                o[this.name] = [o[this.name]];
            }
            o[this.name].push(this.value || '');
        } else {
            o[this.name] = this.value || '';
        }
    });
    return o;
};

$.fn.hasAttr = function(name)
{
    var attr = $(this).attr(name);

    return typeof attr !== 'undefined' && attr !== false;
}

function scrollTo(element_id, speed)
{
    if (typeof speed === 'undefined') {
        speed = 2000;
    }

    let el = $('#'+element_id);

    if (el.length) {
        // add -+ number to adjust up or down
        $([document.documentElement, document.body]).animate({
            scrollTop: el.offset().top
        }, speed);
    }
}

// backend

function isObject(obj)
{
    return obj === Object(obj);
}

function disableAdminFormButtons(form)
{
    form.find('.panel-footer .button').prop('disabled', 'disabled');
}

function confirmDelete(item_id)
{
    $('#form_delete_item_id').val(item_id);
    $('#deleteForm').submit();
}

function confirmDeleteAndBanEmail(item_id)
{
    $('#form_delete_and_ban_item_id').val(item_id);
    $('#deleteAndBanForm').submit();
}

function resetError(el)
{
    el.next('em.state-error').remove();
    el.removeClass('state-error');
}

function setError(el, image_id)
{
    if (!el.hasClass('state-error')) {
        el.addClass('state-error');
    }
    $('#' + image_id).val('');
    $('#file_id_' + image_id).val('');
    if ($('#' + image_id + '-error').size() > 0) {
        $('#' + image_id + '-error').html('This field is required.').show();
    } else if ($('#file_id_' + image_id + '-error').size() > 0){
        $('#file_id_' + image_id + '-error').html('This field is required.').show();
    } else {
        el.after('<em class="state-error image-error" id="' + image_id + '-error">This field is required.</em>');
    }
}
function showSpinner()
{
    $('body').append('<div id="spinner"><i class="fas fa-spinner fa-spin"></i></div>');
    updateSpinnerDivSize();
}
function hideSpinner()
{
    $('#spinner').remove();
}
function updateSpinnerDivSize()
{
    if ($('#spinner')) {
        $('#spinner').css({
            width : $(window).width(),
            height: $(window).height()
        });
    }
}
function showSpinnerWithText(text)
{
    $('body').append('<div id="spinner_with_text">' +
        '<div class="spinner-container">' +
            '<div class="spinner">' +
                '<i class="fas fa-sync-alt fa-spin"></i>' +
                '<div class="spinner-text">' +
                    text +
                '</div>' +
            '</div>' +
        '</div>' +
    '</div>');
}
function hideSpinnerWithText()
{
    $('#spinner_with_text').remove();
}
function currencyFormat(value)
{
    return '$' + Number(value).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
}
