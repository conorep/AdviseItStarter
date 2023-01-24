/**
 * This file contains javascript functions for the page.
 * @version 1.0
 * @author Conor O'Brien
 */

/**
 * This block of jQuery functions contains several functions defining page view activity.
 *      It sets the page load view to 'home' and adds onclick event handlers to load
 *          different PHP views in the document's content body.
 *      When the 'new' view is loaded, an onclick event handler is attached to the submit
 *          button. This prevents page reload and runs a function to submit the new
 *          schedule to the database.
 */
$(document).ready(function()
{
    /*TODO: remove Home button disabled prop on view load if found a schedule using URI input*/
    /**
     * On document 'ready,' the home view is loaded in the index page.
     */
    $("#mainContent").load('views/home.php');

    /**
     * This onclick function loads home.php when the home header button is clicked.
     */
    $("#home-view-button").click(function()
    {
        $("#mainContent").load('views/home.php');
    });

    /**
     * This onclick function loads new.php when the new schedule header button is clicked.
     */
    $("#new-view-button").click(function ()
    {
        $("#mainContent").load('views/new.php', function()
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
     * This function adds an onclick event to the admin login button.
     *      It posts the login form submit data to login_ajax_calls, which handles success or failure to log in.
     *      NOTE: The post uses the whole URI for the file because this button is loaded on page load.
     */
    $('#admin-login-button').click(function()
    {
        console.log("YOU CLICKED IT");
        $.post('https://cobrien2.greenriverdev.com/adviseit//controller/login_ajax_call.php', $('#adminLoginSubmit').serialize(), function()
        {
            location.reload(true);
        });
    });

    /**
     * This function adds an onclick event to the logout button.
     *      It calls the login_ajax_call page with a get request to call the logout function in the controller.
     *      NOTE: The get uses the whole URI for the file because this button is loaded on page load.
     */
    $('#logout-button').click(function()
    {
        $.get('https://cobrien2.greenriverdev.com/adviseit//controller/login_ajax_call.php', {"LogGet": "yes"}, function()
        {
            location.reload(true);
        })
    })

    /**
     * This function adds an onclick event to each button with the retrievalBtnclass.
     * The event will trigger an ajax get call to schedule_ajax_calls.php, triggering a page reload and
     *      a view of an existing schedule
     */
    $(document).on('click', '.retrievalBtn', function(e)
    {
        e.preventDefault();
        let buttonID = $(this).attr('id');
        $.get('controller/schedule_ajax_calls.php', {"ScheduleIDGet": buttonID}, function()
        {
            /*move view to "retrieve", call disableUpdateBtn function, remove retrieve schedule button 'disabled'*/
            $("#mainContent").load('views/retrieve.php', function()
            {
                $.fn.disableUpdateBtn();
            });
            $('#admin-view-button').prop('disabled', false);
        })
    });

    /**
     * This function responds to the search box input. When there's a change, it searches schedule div IDs
     *      to see if they contain the input character(s). If not, they are hidden. If yes, they are shown.
     */
    $(document).on('input', '#searchSchedules', function()
    {
        let thisVal = $(this).val().toUpperCase();

        $('.retrievalDiv').each(function()
        {
            var thisId = this.id.split('-')[0];
            this.id.search(thisVal) < 0 ? $(this).hide() : $(this).show();
        });
    });

    /**
     * This function responds to input in the home page search box. STILL IN WORK!
     */
    $(document).on('input', '#getSchedule', function()
    {
        let searchVal = $(this).val().toUpperCase();
        if(searchVal.length === 6)
        {
            console.log("length six input! run that get.");
            $.get('controller/schedule_ajax_calls.php', {"ScheduleIDGet": searchVal}, function ()
            {
                /*move view to "retrieve", call disableUpdateBtn function, remove retrieve schedule button 'disabled'*/
                $("#mainContent").load('views/retrieve.php', function ()
                {
                    $.fn.disableUpdateBtn();
                });
                $('#admin-view-button').prop('disabled', false);
                $('#home-view-button').prop('disabled', false);
            });
        }
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
            $.post('controller/schedule_ajax_calls.php',
                "UniqueID=" + $('.submit_id').attr('id') + "&" + $('#scheduleSubmit').serialize(), function()
                {
                    /*move view to "retrieve" and set the view button disabled state properly*/
                    $("#mainContent").load('views/retrieve.php', function()
                    {
                        $.fn.disableUpdateBtn();
                    });
                    alert("NEW RECORD CREATED");
                    $('#new-view-button').prop('disabled', false);
                });
        });
    }

    /**
     * On schedule update submit, ajax handles posting of the data to session
     *      and then navigates back to the 'retrieve' view to display the info
     *      before calling the disableUpdateBtn function to reset element functional
     *      properties.
     * @param postDataBuilder string with UniqueID= and the schedule ID.
     * @param inputDataBuilder string with the field(s) to update and update value(s).
     */
    $.fn.updateSchedule = function(postDataBuilder, inputDataBuilder)
    {
        $.post('controller/schedule_ajax_calls.php', postDataBuilder + inputDataBuilder, function()
        {
            /*move view to "retrieve" and set the view button disabled state properly*/
            $("#mainContent").load('views/retrieve.php', function()
            {
                $.fn.disableUpdateBtn();
            });
            alert("RECORD UPDATED");
            $('#new-view-button').prop('disabled', false);
        });
    }

    /**
     * This function disables the update button. It is called when the retrieve view has been loaded.
     *      The button is re-enabled when there is a change registered in the schedule forms.
     *      This function also re-attaches a submit eventlistener to the submit button that has been
     *      loaded via ajax in consideration of this element not being a part of the DOM.
     */
    $.fn.disableUpdateBtn = function()
    {
        let submitBtn = $('#submit-schedule-button');
        let scheduleForms = $('#scheduleSubmit');
        if(submitBtn.prop('name') === 'scheduleUpdate')
        {
            submitBtn.prop("disabled", true);
        }
        scheduleForms.on('input', function()
        {
            submitBtn.prop("disabled", false);
        })

        /*grab all initial input elements and save their values to array for comparison in submit*/
        let scheduleInitEles = $('.quarterInput');
        let scheduleInitVals = [];
        scheduleInitEles.each(function()
        {
            scheduleInitVals.push(this.value);
        });

        /*create initial ID string and an empty input data builder string variable for POST*/
        let postDataBuilder = "UniqueID=" + $('.submit_id').attr('id');
        let inputDataBuilder = '';
        scheduleForms.submit(function(e)
        {
            e.preventDefault();
            /*grab values on submit and compare to initial input values*/
            let scheduleUpdateVals = $('.quarterInput');
            for(let x = 0; x < scheduleUpdateVals.length; x++)
            {
                if(scheduleInitVals[x] !== scheduleUpdateVals[x].value)
                {
                    inputDataBuilder += "&" + scheduleUpdateVals[x].id + "=" + scheduleUpdateVals[x].value;
                }
            }
            /*if there are updated values, run the update POST*/
            if(inputDataBuilder !== '')
            {
                $.fn.updateSchedule(postDataBuilder, inputDataBuilder);
            } else
            {
                alert("NO CHANGE TO VALUES. SUBMIT CANCELED.");
            }
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
 * @param clicked the ID of the button that was clicked (home-view-button, new-view-button, or admin-view-button)
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
    if(mainElement.firstChild)
    {
        let mainChild = mainElement.firstChild;
        while (mainChild)
        {
            mainElement.removeChild(mainChild);
        }
    }
}