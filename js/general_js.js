/**
 * This file contains javascript functions for the page.
 * @version 1.0
 * @author Conor O'Brien
 */

/**
 * This jQuery function contains several nested functions defining page view activity.
 *      It sets the page load view to 'home' and adds onclick event handlers to load
 *          different PHP views in the document's content body.
 *      When the 'new' view is loaded, an onclick event handler is attached to the submit
 *          button. This prevents page reload and runs a function to submit the new
 *          schedule to the database.
 */
$(document).ready(function ()
{
    /*TODO: create ajax code that will catch URI route and load the 'retrieve' view*/
/*    var session;
    $.ajaxSetup({cache: false})
    $.get('index.php', function (data) {
        session = data;
    });*/

    /**
     * On document 'ready,' the home view is loaded in the index page.
     */
    $("#mainContent").load('views/home.php');

    /**
     * This onclick function loads home.php when the home header button is clicked.
     */
    $("#home-view-button").click(function ()
    {
        $("#mainContent").load('views/home.php');
    });

    /**
     * This onclick function loads new.php when the new schedule header button is clicked.
     */
    $("#new-view-button").click(function ()
    {
        $("#mainContent").load('views/new.php', function ()
        {
            $.fn.postSchedule();
        });
    });

    /**
     * This onclick function loads retrieve.php when the retrieve schedule header button is clicked.
     */
    $("#retrieve-view-button").click(function()
    {
        $("#mainContent").load('views/retrieve.php');
    });

    /**
     * This function adds an onclick event to each button with the retrievalBtnclass.
     * The event will trigger an ajax get call to schedule_ajax_calls.php, triggering a page reload and
     *             a view of an existing schedule
     */
    $(document).on('click', '.retrievalBtn', function(e)
    {
        e.preventDefault();
        var buttonID = $(this).attr('id');
        $.ajax({
            url: 'controller/schedule_ajax_calls.php',
            type: 'GET',
            data: {"ScheduleIDGet": buttonID},
            success: function()
            {
                /*move view to "retrieve" and set the view button disabled state properly*/
                $("#mainContent").load('views/retrieve.php', function()
                {
                    $.fn.disableUpdateBtn();
                });
                $('#retrieve-view-button').prop('disabled', false);
            }
        });
    });

    /**
     * This function responds to the search box input. When there's a change, it searches schedule div IDs
     *      to see if they contain the input character(s). If not, they are hidden. If yes, they are shown.
     */
    $(document).on('input', '#searchSchedules', function()
    {
        var thisVal = $(this).val().toUpperCase();

        $('.retrievalDiv').each(function()
        {
            var thisId = this.id.split('-')[0];

            if(thisId.search(thisVal) < 0)
            {
                $(this).hide();
            } else
            {
                $(this).show();
            }
        });
    });

    /**
     * On schedule submit, ajax handles posting of the data to session
     *      and then navigates to the 'retrieve' view and displays the info.
     *      before setting button disabled states properly.
     */
    $.fn.postSchedule = function()
    {
        $('#scheduleSubmit').submit(function(e)
        {
            e.preventDefault();
            $.ajax({
                url: 'controller/schedule_ajax_calls.php',
                type: 'POST',
                data: $('#scheduleSubmit').serialize(),
                success: function()
                {
                    /*move view to "retrieve" and set the view button disabled state properly*/
                    $("#mainContent").load('views/retrieve.php', function()
                    {
                        $.fn.disableUpdateBtn();
                    });
                    alert("NEW RECORD CREATED");
                    $('#new-view-button').prop('disabled', false);
                }
            });
        });
    }

    /**
     * This function disables the update button. It is called when the retrieve view has been loaded.
     *      The button is re-enabled when there is a change registered in the schedule forms.
     */
    $.fn.disableUpdateBtn = function()
    {
        var submitBtn = $('#submit-schedule-button');
        var scheduleForms = $('#scheduleSubmit');
        if(submitBtn.prop('name') === 'scheduleUpdate')
        {
            submitBtn.prop("disabled", true);
        }
        scheduleForms.on('input', function()
        {
            submitBtn.prop("disabled", false);
        })
        /*TODO: simplify this. redundant code.*/
        scheduleForms.submit(function(e)
        {
            e.preventDefault();
            $.ajax({
                url: 'controller/schedule_ajax_calls.php',
                type: 'POST',
                data: $('#scheduleSubmit').serialize(),
                success: function()
                {
                    /*move view to "retrieve" and set the view button disabled state properly*/
                    $("#mainContent").load('views/retrieve.php', function()
                    {
                        $.fn.disableUpdateBtn();
                    });
                    alert("RECORD UPDATED");
                    $('#new-view-button').prop('disabled', false);
                }
            });
        })
    }
});


/**
 * Call the viewButtonHandler method on window load.
 */
window.onload = viewButtonHandler;

/**
 * This function does two things:
 *  1: Sets the home button disabled property to 'true' on window load.
 *  2: Attaches a disable toggle function to each of the header 'view' buttons.
 */
function viewButtonHandler()
{
    let viewButtons = document.getElementsByClassName("viewButton");

    for (let x = 0; x < viewButtons.length; x++)
    {
        if (viewButtons[x].id === "home-view-button")
        {
            viewButtons[x].disabled = true;
        }
        viewButtons[x].addEventListener("click", () => disableToggle(viewButtons[x].id, viewButtons));
    }
}

/**
 * This function sets the clicked button to 'disabled' and re-enables any other button that has been disabled.
 * @param clicked the ID of the button that was clicked (home-view-button, new-view-button, or retrieve-view-button)
 * @param viewButtons button elements with 'viewButton' class
 */
function disableToggle(clicked, viewButtons)
{
    for (let x = 0; x < viewButtons.length; x++)
    {
        viewButtons[x].disabled = viewButtons[x].id === clicked;
    }
}

/*TODO: see if this is needed or not*/
/**
 * This function removes all children from the mainContent element.
 */
function removeElements()
{
    const mainElement = document.getElementById("mainContent");
    let mainChild = mainElement.firstChild;
    while (mainChild)
    {
        mainElement.removeChild(mainChild);
    }
}