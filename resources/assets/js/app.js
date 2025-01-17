/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */
require('./bootstrap');

import select2 from './components/select2.js';
import deleteConfirm from './components/delete-confirm.js';

/**
 * Notifications: List and Count selector, and seconds for the timeout to refresh the list.
 * Refresh every 5 minutes because every minute gets quite intensive with people opening many tabs.
 * Todo: workaround to only run timeout if the window is active
 */
var notificationList, notificationCount, notificationRefreshTimeout = 60 * 5000;

$(document).ready(function() {

    // Inject the isMobile variable into the window. We don't want ALL of the javascript
    // for mobiles, namely the tooltip tool.
    window.kankaIsMobile = window.matchMedia("only screen and (max-width: 760px)");
    if (!window.kankaIsMobile.matches) {
        initTooltips();
    }

    $('[data-toggle="popover"]').popover({
        sanitize: false,
    });

    initSelect2();
    initCheckboxSwitch();

    // Open select2 dropdowns on focus. Don't add this in initSelect2 since we only need this
    // binded once.
    $(document).on('focus', '.select2.select2-container', function (e) {
        // only open on original attempt - close focus event should not fire open
        if (e.originalEvent && $(this).find(".select2-selection--single").length > 0) {
            $(this).siblings('select').select2('open');
        }
    });

    if ($('.date-picker').length > 0) {
        $.each($('.date-picker'), function (index) {
            // instance, using default configuration.
            $(this).datepicker({
                autoclose: true,
                format: 'yyyy-mm-dd',
                todayHighlight: true
            });
        });
    }

    $.each($('.img-delete'), function (index) {
        $(this).click(function (e) {
            e.preventDefault();
            $('input[name=' + $(this).data('target') + ']')[0].value = 1;
            $(this).parent().parent().hide();
        });
    });


    if ($('#delete-confirm-form').length > 0) {
        $('#delete-confirm-form').on('keyup keypress', function (e) {
            var keyCode = e.keyCode || e.which;
            if (keyCode === 13) {
                e.preventDefault();
                return false;
            }
        });
    }

    $.each($('.new-entity-selector'), function (index) {
        $(this).on('click', function (e) {
            $('#new-entity-type').val($(this).data('entity'));
            $('#new-entity-form').data('parent', $(this).data('parent'));
        });
    });

    if ($('#new-entity-form').length > 0) {
        $('#new-entity-form').on('submit', function (e) {
            $('#new-entity-errors').hide();
            var target = $(this).data('parent');
            $.ajax({
                type: "POST",
                url: $(this).attr('action'),
                data: $(this).serialize()
            }).done(function (result, textStatus, xhr) {
                if (textStatus === 'success' && result.id) {
                    $('#' + target).children().remove().end().append(new Option(result.name, result.id)).val(result.id).trigger('change');

                    // Close modal
                    $('#new-entity-modal').modal('toggle');
                    $('#new-entity-errors').hide();
                    $('#new-entity-name').val('');

                    // Reset submit button
                    resetSubmitButton('#new-entity-save');
                } else {
                    $('#new-entity-errors').show();

                    // Reset submit button
                    resetSubmitButton('#new-entity-save');
                }
            }).fail(function (result, textStatus, xhr) {
                $('#new-entity-errors').show();

                // Re-enable the submit button
                resetSubmitButton('#new-entity-save');
            });

            e.preventDefault();
            return false;
        });
    }


    // Treeview for locations
    var treeViewLoader = $('#locations-treeview');
    if (treeViewLoader.length > 0) {
        treeViewInit('locations');
    }

    // Treeview for tags
    if ($('#tags-treeview').length > 0) {
        treeViewInit('tags');
    }
    if ($('#quests-treeview').length > 0) {
        treeViewInit('quests');
    }
    if ($('#organisations-treeview').length > 0) {
        treeViewInit('organisations');
    }
    if ($('#families-treeview').length > 0) {
        treeViewInit('families');
    }
    if ($('#races').length > 0) {
        treeViewInit('races');
    }

    manageTabs();
    manageDashboardNotifications();


    // Live search on forms
    /*$.each($('.datagrid-search'), function(index) {
        $(this).submit(function(event) {
            event.preventDefault();

            window.location.href =
        });
    });*/

    deleteConfirm();
    initTogglePasswordFields();
    initAjaxPagination();
    initNotifications();

    /**
     * Whenever a modal or popover is shown, we'll need to re-bind various helpers we have.
     */
    $(document).on('shown.bs.modal shown.bs.popover', function() {
        $('[data-toggle="tooltip"]').tooltip();

        // Also re-bind select2 elements on modal show
        initSelect2();
        initCheckboxSwitch();
        initAjaxPagination();
        initTooltips();

        // Handle when opening the entity-creator ui
        entityCreatorUI();

    });
});

/**
 * Init the toggle elements
 */
function initCheckboxSwitch() {
    //$('[data-toggle="switch"]').bootstrapSwitch();
}

/**
 * Select2 is used for all the fancy dropdowns
 */
function initSelect2() {
    select2();
}


/**
 * Go through table trs to add on click support
 */
function treeViewInit(element) {
    var treeViewLoader = $('#' + element + '-treeview');
    var link = treeViewLoader.data('url');
    $.each($('#' + element + ' > tbody > tr'), function(index) {
        var children = $(this).data('children');
        if (parseInt(children) > 0) {
            $(this).addClass('tr-hover');
            $(this).on('click', function (e) {
                // Don't trigger the click on the checkbox (used for bulk actions)
                if (e.target.type !== 'checkbox') {
                    window.location = link + '?parent_id=' + $(this).data('id');
                }
            });
        }
    });
}

/**
 * Get the entity's tooltip via ajax
 */
var cachedTooltips = Array();

function entityTooltip() {
    var element = $(this);

    var id = element.data('id');

    if (id in cachedTooltips){
        return cachedTooltips[id];
    }

    var title = '<div class="center"><i class="fa fa-spinner fa-spin"></i></div>';

    $.ajax({
        url: $(this).data('url'),
        method: "GET",
        async: false,
        success:function(data)
        {
            title = data;
        }
    });

    cachedTooltips[id] = title;

    return title;

}

/**
 * Save and manage tabs for when refreshing
 */
function manageTabs() {
    var tabLink = $('.nav-tabs li a');
    tabLink.click(function(e) {
        e.preventDefault();

        // If tab isn't ajax request
        if (!$(this).data('url')) {
            $(this).tab('show');
        }
    });

    // store the currently selected tab in the hash value
    tabLink.on("shown.bs.tab", function(e) {
        e.preventDefault();
        var tabId = $(e.target).attr("href").substr(1);
        var dataToggle = $(e.target).attr('ajax-modal');

        if (dataToggle && dataToggle == 'ajax-modal') {
            // Modal? Don't do more.
            return true;
        }
        // We fake a tab_ to avoid page jumps from the browser
        window.location.hash = 'tab_' + tabId;
    });

    // on load of the page: switch to the currently selected tab
    var tabHash = window.location.hash.replace('tab_', '');
    $('ul.nav-tabs > li > a[href="' + tabHash + '"]').tab('show');
}

/**
 *
 */
function manageDashboardNotifications() {
    $.each($('.click-notification'), function(index) {
        $(this).modal();
    });

    $.each($('.notification-delete'), function(index) {
        $(this).on('click', function() {
            $.ajax({
                url: $(this).data('url'),
                dataType: 'json'
            });

            // Had this in the done, but it never fired?
            var parent = $(this).data('parent');
            $('#' + parent).modal('toggle');
        });

    });
}


/**
 * Show/Hide password field helpers
 */
function initTogglePasswordFields() {
    var passwordField = $('#password');
    var passwordToggleIcon = $('.toggle-password-icon');
    $('.toggle-password').on('click', function(e) {
        e.preventDefault();
        if (passwordField.prop('type') === 'text') {
            passwordField.prop('type', 'password');
            passwordToggleIcon.removeClass('fa-eye-slash').addClass('fa-eye')
        } else {
            passwordField.prop('type', 'text');
            passwordToggleIcon.removeClass('fa-eye').addClass('fa-eye-slash');
        }
        return false;
    });
}

function resetSubmitButton(id) {
    var newEntitySaveButton = $(id);
    newEntitySaveButton.text(newEntitySaveButton.data('text')).prop('disabled', false);
}

/**
 * Quick Entity Creator UI
 */
function entityCreatorUI() {
    $('[data-toggle="entity-creator"]').on('click', function(e) {
        e.preventDefault();

        var selection = $('#entity-creator-selection');
        var loader = $('.entity-creator-loader');

        selection.addClass('hidden');
        loader.removeClass('hidden');

        $.ajax($(this).data('url')).done(function (data) {
            loader.addClass('hidden');
            selection.html(data).removeClass('hidden');
            initSelect2();
            initEntityCreatorDuplicateName();
            window.initCategories();

            $('#entity-creator-form').on('submit', function(e) {
                e.preventDefault();

                // Allow ajax requests to use the X_CSRF_TOKEN for deletes
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.post({
                    url: $(this).attr('action'),
                    data: $(this).serialize(),
                    context: this
                }).done(function (result, textStatus, xhr) {
                    // New entity was created, let's follow that redirect
                    console.log(result);

                    $('#entity-creator-form').hide();

                    $('.entity-creator-success').html(result.message).show();

                }).fail(function (data) {
                    $('.entity-creator-error').show();
                });
            });
        });

        return false;
    });
}

function initEntityCreatorDuplicateName() {
    $('#entity-creator-selection input[name="name"]').focusout(function(e) {
        var entityCreatorDuplicateWarning = $('#entity-creator-selection .duplicate-entity-warning');
        entityCreatorDuplicateWarning.hide();
        // Check if an entity of the same type already exists, and warn when it does.
        $.ajax(
            $(this).data('live') + '?q=' + $(this).val() + '&type=' + $(this).data('type')
        ).done(function (res) {
            if (res.length > 0) {
                entityCreatorDuplicateWarning.fadeIn();
            } else {
                entityCreatorDuplicateWarning.hide();
            }
        });
    });
}

/**
 * Replace pagination for ajax links
 */
function initAjaxPagination() {
    $('.pagination-ajax-links a').on('click', function(e) {
        e.preventDefault();
        var paginationAjaxBody = $('.pagination-ajax-body');
        paginationAjaxBody.find('.loading').show();
        paginationAjaxBody.find('.pagination-ajax-content').hide();

        $.ajax(
            $(this).attr('href')
        ).done(function (res) {
            paginationAjaxBody.parent().html(res);
            initAjaxPagination();
        });
        return false;
    })
}

/**
 * Check if there are new notifiations for the user
 */
function initNotifications() {
    notificationList = $('#header-notification-list');
    notificationCount = $('#header-notification-count');
    if (notificationList.length === 1) {
        setTimeout(refreshNotificationList, notificationRefreshTimeout);
    }
}

function refreshNotificationList() {
    // console.log('refresh notification list');
    $.ajax(notificationList.data('url'))
        .done(function(result) {
            if (result.count > 0) {
                notificationList.html(result.body);
                notificationCount.html(result.count).show();
            } else {
                notificationCount.hide();
            }
            setTimeout(refreshNotificationList, notificationRefreshTimeout);
        }
    );
}

/**
 * Register the tooltip and tooltip-ajax helper
 */
function initTooltips()
{
    $('[data-toggle="tooltip"]').tooltip();

    // New tooltips with ajax call
    // $('[data-toggle="tooltip-ajax"]').on('mouseenter', function(e) {
    //     var self = $(this);
    //     console.log('mention-loaded?', self.data('mention-loaded'));
    //     if (self.data('mention-loaded')) {
    //         return;
    //     }
    //
    //     e.preventDefault();
    //     setTimeout(function() {
    //         $.ajax({
    //             url: self.data('mention-url'),
    //             async: true
    //         })
    //         .done(function(data) {
    //             console.log('data from request', data[0]);
    //             self.data('original-title', data[0]);
    //             //self.prop('title', data[0]);
    //             self.data('mention-loaded', true);
    //             console.log('finished setting data', self.data('mention-loaded'));
    //         });
    //     }, 5);
    // });
    $('[data-toggle="tooltip-ajax"]').tooltip({
        title: entityTooltip,
        delay: 250,
        trigger: 'hover',
        placement: 'auto',
        template: '<div class="tooltip" role="tooltip">' +
            '<div class="tooltip-arrow"></div>' +
            '<div class="tooltip-inner tooltip-ajax"></div>' +
            '</div>',
        html: true
    });

    $('[data-toggle="tooltip-ajax"]').click(function (e) {
        $(this).tooltip('hide');
    });
}

// Helpers are injected directly in the window functions.
require('./helpers.js');

require('./keyboard.js');
require('./crud.js');
require('./calendar.js');
require('./search.js');
require('./tags.js');