/**
 * This file contains javascript functions for the page.
 * @version 1.0
 * @author Conor O'Brien
 */

/**
 * This jQuery function contains several nested functions defining page view activity.
 *      It sets the page load view to 'home' and adds onclick event handlers to load
 *      different PHP views in the document's content body.
 */
$(document).ready(function ()
{
    $("#mainContent").load('views/home.php');

    $("#home-view-button").click(function ()
    {
        $("#mainContent").load('views/home.php');
    });
    $("#new-view-button").click(function ()
    {
        $("#mainContent").load('views/new.php');
    });
    $("#retrieve-view-button").click(function ()
    {
        $("#mainContent").load('views/retrieve.php');
    });
});

/**
 * This function does two things:
 *  1: Sets the home button disabled property to 'true' on window load.
 *  2: Attaches a disable toggle function to each of the header 'view' buttons.
 */
window.onload = function ()
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
 * @param clicked the ID of the button that was clicked
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