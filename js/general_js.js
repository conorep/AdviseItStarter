/*General JS File*/
$(document).ready(function()
{
    $("#mainContent").load('views/home.php');

    $("#home-view-button").click(function()
    {
        $("#mainContent").load('views/home.php');
    });
    $("#new-view-button").click(function()
    {
        $("#mainContent").load('views/new.php');
    });
    $("#retrieve-view-button").click(function()
    {
        $("#mainContent").load('views/retrieve.php');
    });
});

/*TODO: see if this is needed or not*/
window.onload = function()
{
    let viewButtons = document.getElementsByClassName("headerButton");

    for(let x = 0; x < viewButtons.length; x++)
    {
        viewButtons[x].addEventListener("click", selectView);
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
    while(mainChild)
    {
        mainElement.removeChild(mainChild);
    }
}

/*TODO: see if this is needed or not*/
/**
 * This function changes the displayed page view based on navbar button clicks.
 * @param thisButton the
 */
function selectView(thisButton)
{
    let idName = thisButton.target.id.split("-");
    if(idName[0] === "home")
    {
        console.log("home");
    } else if(idName[0] === "new")
    {
        console.log("new");
    } else if(idName[0] === "retrieve")
    {
        console.log("retrieve");
    }
    console.log(thisButton.target.id);
}

/*TODO: see if this is needed or not*/
function makeNewElement(info)
{
    let newScript = document.createElement('script');
    newScript.id = info + "-view";
    newScript.innerText = " <?php  include_once 'views/" + info + ".php'; ?> "

    return newScript;
}