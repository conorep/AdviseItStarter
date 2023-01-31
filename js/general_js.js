/**
 * This file contains javascript functions for the page.
 * @version 1.0
 * @author Conor O'Brien
 */
/*
TODO: NOTE TO SELF! I'm currently setting this page up with no back button functions. This was OK'd by Tyler.
   As such, I've used history.replaceState instead of proper window reloads. This has advantages and disadvantages.
   ADVANTAGE: seamless function of page with no reloads (outside of admin login currently)
   DISADVANTAGE: some people are used to a back button to return to previous data
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
    var homeLoc = "https://cobrien2.greenriverdev.com/adviseit/";
    var schedPath = "schedule=";
    var currPath = window.location.href;

    /**
     * On document 'ready,' the home view is loaded in the index page and the home button is disabled.
     *      If that load sequence leads to retrieval of a schedule due to a token in the URI, the home
     *      view button is NOT disabled, otherwise it is disabled.
     */
    $("#mainContent").load('views/home.php', function()
    {
        /*using this function to track if there was a URI token used to retrieve a schedule*/
        /*if $scheduleSubmit.length (i.e. this element exists because retrieve view was loaded), disable home button*/
        if(!$('#scheduleSubmit').length)
        {
            $('#home-view-button').prop('disabled', true);
        } else
        {
            $.fn.updateViewEvents();
            history.replaceState({}, null, currPath);
        }
    });

    /**
     * This onclick function loads home.php when the home header button is clicked.
     *      If window.location.href is different from the defined home location string,
     *      the page is reloaded at homeLoc (i.e. the schedule token used for retrieval
     *      is removed from the URI and the page gets reloaded).
     *      Otherwise, after the 'home' HTML is rendered, the home view button is disabled
     *      and other header buttons are enabled.
     */
    $("#home-view-button").click(function()
    {
        if(window.location.href !== homeLoc)
        {
            history.replaceState({}, null, homeLoc);
        }
        $("#mainContent").load('views/home.php', function()
        {
            $('#home-view-button').prop('disabled', true);
            disableToggle("home-view-button");
        });
    });

    /**
     * This onclick function loads new.php when the new schedule header button is clicked.
     *      After the 'new' HTML is rendered, the new view button is disabled and other
     *      header buttons are enabled.
     */
    $("#new-view-button").click(function()
    {
        $("#mainContent").load('views/new.php', function()
        {
            let submitID = $('.submit_id').attr('id');
            $.fn.postSchedule();
            if(window.location.href !== homeLoc)
            {
                history.replaceState({}, null, homeLoc);
            }
            if(window.location.href === homeLoc && submitID !== null)
            {
                history.replaceState({}, null, homeLoc + schedPath + submitID);
            }
            disableToggle("new-view-button");
        });
    });

    /**
     * This onclick function loads retrieve.php when the retrieve schedule header button is clicked.
     *      After the 'retrieve' HTML is rendered, the retrieve view button is disabled and other
     *      header buttons are enabled.
     */
    $("#retrieve-view-button").click(function()
    {
        $("#mainContent").load('views/retrieve.php', function()
        {
            if(window.location.href !== homeLoc)
            {
                history.replaceState({}, null, homeLoc);
            }
            disableToggle("retrieve-view-button");
        });
    });

    /*TODO: implement more comprehensive login error handling.
       Set up a return value from login submit and act accordingly...?*/
    /**
     * This function adds an onclick event to the admin login button.
     *      It posts the login form submit data to login_ajax_calls, which handles success or failure to log in.
     *      NOTE: The ajax 'post' uses the whole URI for the file because this button is rendered on page load.
     */
    $('#admin-login-button').click(function(e)
    {
        e.preventDefault();
        $.post('https://cobrien2.greenriverdev.com/adviseit//controller/login_ajax_call.php',
            $('#adminLoginSubmit').serialize(), function(response)
        {
            console.log(response);
            if(response.includes('success'))
            {
                alert(response);
                location.reload();
            } else
            {
                alert(response);
            }
        });
    });

    /**
     * This function adds an onclick event to the logout button.
     *      It calls the login_ajax_call page with a get request to call the logout function in the controller.
     *      NOTE: The ajax 'get' uses the whole URI for the file because this button is rendered on page load.
     */
    $('#logout-button').click(function(e)
    {
        e.preventDefault();
        $.get('https://cobrien2.greenriverdev.com/adviseit//controller/login_ajax_call.php', {"LogGet": "yes"},
            function()
        {
            alert("Logout successful.");
            location.reload();
        })
    })

    /**
     * This function adds an onclick event to each button with the retrievalBtnclass.
     * The event will trigger an ajax get call to schedule_ajax_calls.php, triggering a page
     *      reload and a view of an existing schedule
     */
    $(document).on('click', '.retrievalBtn', function(e)
    {
        e.preventDefault();
        let buttonID = $(this).attr('id');
        let setURLLoc = homeLoc + schedPath + buttonID;
        $.get('controller/schedule_ajax_calls.php', {"ScheduleIDGet": buttonID}, function()
        {
            /*move view to "retrieve", call updateViewEvents function, remove retrieve schedule button 'disabled'*/
            $("#mainContent").load('views/retrieve.php', function()
            {
                $.fn.updateViewEvents();
                history.replaceState({}, null, setURLLoc);
            });
            $('#retrieve-view-button').prop('disabled', false);
        })
    });

    /*TODO: need to make the new divs functional. the container div needs a proper ID reflecting its year and
       the textarea divs need to have proper IDs reflecting that plan year*/
    /**
     * This function adds an onclick event to the up and down buttons on the new or update schedule views.
     */
    $(document).on('click', '.yearButton', function()
    {
        let getScheduleDivs = document.getElementsByClassName('getYear');
        let thisId = $(this).attr('id');

        if(thisId.includes('up'))
        {
            let prevDiv = getScheduleDivs[0].cloneNode(true);
            let prevID = prevDiv.id.split('-');
            prevID[2]--;
            prevDiv.id = prevID.join('-');
            prevDiv.appendBefore(getScheduleDivs[0]);
        } else
        {
            let nextDiv = getScheduleDivs[getScheduleDivs.length-1].cloneNode(true);
            let nextID = nextDiv.id.split('-');
            nextID[2]++;
            nextDiv.id = nextID.join('-');
            nextDiv.appendAfter(getScheduleDivs[getScheduleDivs.length-1]);
        }
    });

    /**
     * This function responds to the search box input on the admin 'view schedules' page. When there's a change,
     *      it searches schedule div IDs to see if they contain the input character(s).
     *      If not, they are hidden. If yes, they are shown.
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
     * This function responds to input in the home page search box. Once a 6-digit string is entered into the search box,
     *      this function is run to retrieve a schedule.
     */
    $(document).on('input', '#getSchedule', function()
    {
        let searchVal = $(this).val().toUpperCase();
        if(searchVal.length === 6)
        {
            $.get('controller/schedule_ajax_calls.php', {"ScheduleIDGet": searchVal}, function()
            {
                /*move view to "retrieve", call updateViewEvents function, remove retrieve schedule button 'disabled'*/
                $("#mainContent").load('views/retrieve.php', function()
                {
                    $.fn.updateViewEvents();
                    history.replaceState({}, null, homeLoc + schedPath + searchVal);
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
                        $.fn.updateViewEvents();
                        history.replaceState({}, null, homeLoc + schedPath + $('.submit_id').attr('id'));
                    });
                    alert("NEW RECORD CREATED");
                    $('#new-view-button').prop('disabled', false);
                });
        });
    }

    /**
     * On schedule update submit, ajax handles posting of the data to session
     *      and then navigates back to the 'retrieve' view to display the info
     *      before calling the updateViewEvents function to reset element functional
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
                $.fn.updateViewEvents();
            });
            alert("RECORD UPDATED");
            $('#new-view-button').prop('disabled', false);
        });
    }

    /*TODO: split this function into smaller chunks. LENGTHY, NOT READABLE BY OTHER DEVS.*/
    /**
     * This function disables the update button. It is called when the retrieve view has been loaded.
     *      The button is re-enabled when there is a change registered in the schedule forms.
     *      This function also re-attaches a submit eventlistener to the submit button that has been
     *      loaded via ajax in consideration of this element not being a part of the DOM.
     */
    $.fn.updateViewEvents = function()
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
 * This function sets the clicked button to 'disabled' and re-enables any other button that has been disabled.
 * @param clicked the ID of the button that was clicked (home-view-button, new-view-button, or admin-view-button)
 */
function disableToggle(clicked)
{
    let viewButtons = document.getElementsByClassName("viewButton");
    for (let x = 0; x < viewButtons.length; x++)
    {
        viewButtons[x].disabled = viewButtons[x].id === clicked;
    }
}

/**
 * This function allows insertion of a node into the beginning of a list of nodes.
 * @param newNode the node to insert
 * @param existingNode the existing node to prepend to
 */
Element.prototype.appendBefore = function (element) {
    element.parentNode.insertBefore(this, element);
},false;

/**
 * This function allows insertion of a node into the end of a list of nodes.
 * @param newNode the node to insert
 * @param existingNode the existing node to append to
 */
Element.prototype.appendAfter = function (element) {
    element.parentNode.insertBefore(this, element.nextSibling);
},false;