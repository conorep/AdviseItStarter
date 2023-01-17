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

    /*on document ready, load home view in index page*/
    $("#mainContent").load('views/home.php');

    /*load home.php*/
    $("#home-view-button").click(function ()
    {
        $("#mainContent").load('views/home.php');
    });

    /*load new.php and attach event handler to submit button*/
    $("#new-view-button").click(function ()
    {
        $("#mainContent").load('views/new.php', function ()
        {
            /*
              on schedule submit, ajax handles posting of the data to session
              and then navigates to the 'retrieve' view and displays the info.
              before setting button disabled states properly.
              */
            $('#scheduleSubmit').submit(function (e)
            {
                e.preventDefault();
                $.ajax({
                    url: 'controller/schedule_ajax_calls.php',
                    type: 'post',
                    data: $('#scheduleSubmit').serialize(),
                    success: function(){
                        /*move view to "retrieve" and set the view button disabled state properly*/
                        $("#mainContent").load('views/retrieve.php');
                        alert("NEW RECORD CREATED");

                        /*THIS CYCLES THE DISABLED BUTTON ATTRIBUTE TO DISABLE RETRIEVE SCHEDULE ON VIEW CHANGE.*/
                        /*disableToggle('retrieve-view-button',
                            document.getElementsByClassName('viewButton'));*/
                        $('#new-view-button').prop('disabled', false);

                    }
                });

            });

        });
    });

    /*load retrieve.php*/
    $("#retrieve-view-button").click(function ()
    {
        $("#mainContent").load('views/retrieve.php');
    });

    /*this adds an on click event to each button with the retrievalBtnclass*/
    /*the event will trigger an ajax get call to schedule_ajax_calls.php, triggering a page reload and
            a view of an existing schedule*/
    $(document).on('click', '.retrievalBtn', function(e)
    {
        e.preventDefault();
        var buttonID = $(this).attr('id');
        $.ajax({
            url: 'controller/schedule_ajax_calls.php',
            type: 'get',
            data: {"ScheduleIDGet": buttonID},
            success: function(){
                /*move view to "retrieve" and set the view button disabled state properly*/
                $("#mainContent").load('views/retrieve.php');

                $('#retrieve-view-button').prop('disabled', false);
            }
        });
    });

    /*TODO: finish this function*/
    /*this function will take the search input's current value and display click elements that have its value
            (i.e. show the record we want to retrieve)*/
    $(document).on('change', '#searchSchedules', function()
    {
        console.log($(this).attr('currentValue'));
    });

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