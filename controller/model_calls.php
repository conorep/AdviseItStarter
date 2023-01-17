<?php
    session_start();
    
    /**
     * This class contains calls to the model and handling of data returned from it.
     * @version 1.0
     * @author Conor O'Brien
     */
    class ModelCalls
    {
        private $databaseFuncs;

        /**
         * This constructor initializes its $databaseFuncs dependency using a DBFunctions class parameter
         * @param DBFunctions $databaseFuncs
         */
        public function __construct(DBFunctions $databaseFuncs)
        {
            $this->databaseFuncs = $databaseFuncs;
        }

        /*TODO: fix this up a bit. hidden input with the token is not ideal - can be edited.*/
        /**
         * This function utilizes the DB model class to retrieve  a token and display it on the page.
         *      The div is generated with the token as its ID for usage in the form submit function.
         * @return void this function doesn't return anything, but instead echos the generated token.
         */
        function displayUniqueToken()
        {
            $thisUniqueId = $this->databaseFuncs->generateUniqueID();
            echo "
                <div id='" . $thisUniqueId . "' class='container-fluid'>
                    <h3 class='text-center'>Schedule Token: " . $thisUniqueId . "</h3>
                    <input name='UniqueID' class='d-none' value=" . $thisUniqueId . " />
                </div>";
        }

        /**
         * This function retrieves a plan, displays it token/created date/modified date on page, and returns an array
         *      of schedule info if all goes well.
         * @return void no return data. sets $_SESSION planData to array of info if successfully retrieved and echos
         *      HTML to display ID + created/modified date, otherwise echos error text.
         */
        function displayCreatedPlan(string $uniqueToken)
        {
            $_SESSION['planData'] = '';
            $planArray = $this->databaseFuncs->retrieveSchedule($uniqueToken);
            if($planArray)
            {
                $modDate = "JUST CREATED";
                if($planArray['modified_date'] != '')
                {
                    $modDate = $planArray['modified_date'];
                }
                echo "
                    <div id='" . $planArray['scheduleID'] . "' class='container-fluid'>
                        <h3 class='text-center'>Schedule Token: " . $planArray['scheduleID'] . "</h3>
                        <h4 class='text-center'>Schedule Created: " . $planArray['created_date'] . "</h4>
                        <h4 class='text-center'>Last Updated: " . $modDate . "</h4>
                    </div>";
                $_SESSION['planData'] = $planArray;
            } else
            {
                echo "
                <div class='container-fluid'>
                    <h3 class='text-center'>ERROR. Plan data not found!</h3>
                </div>";
            }
        }
    
        /**
         * This function creates a new plan.
         *      The returned 'alert' scripts are found by checking the 'Network' tab in the browser inspection tool.
         *      Click the
         * @param $id String
         * @param $fall String
         * @param $winter String
         * @param $spring String
         * @param $summer String
         * @return string if true, a success message. if false, an error message.
         */
        function createNewPlan(string $id, string $fall, string $winter, string $spring, string $summer): string
        {
            if($this->databaseFuncs->createNewSchedule($id, $fall, $winter, $spring, $summer))
            {
                return "The schedule was successfully created!";
            } else
            {
                return "Error - the new schedule was not created.";
            }
        }

    }









