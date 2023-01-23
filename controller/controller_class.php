<?php
    session_start();
    
    /**
     * This class contains calls to the model and handling of data returned from it.
     * @version 1.0
     * @author Conor O'Brien
     */
    class ControllerClass
    {
        private $databaseFuncs;

        /**
         * This constructor initializes its $databaseFuncs dependency using a DBFunctions class parameter
         * @param ModelDB $databaseFuncs
         */
        public function __construct(ModelDB $databaseFuncs)
        {
            $this->databaseFuncs = $databaseFuncs;
        }
        
        /**
         * This function utilizes the DB model class to retrieve  a token and display it on the page.
         *      The div is generated with the token as its ID for usage in the form submit function.
         * @return void this function doesn't return anything, but instead echos the generated token.
         */
        public function displayUniqueToken()
        {
            $thisUniqueId = $this->databaseFuncs->generateUniqueID();
            echo "
                <div id='" . $thisUniqueId . "' class='container-fluid submit_id'>
                    <h3 class='text-center'>Schedule Token: " . $thisUniqueId . "</h3>";
            $this->displayAdvisorInput();
            echo"
                </div>";
        }

        /**
         * This private function returns HTML for an advisor name input field.
         * @return void
         */
        private function displayAdvisorInput()
        {
            echo "
                    <div class='w-50 mx-auto'>
                        <label for='AdvisorName' class='form-label fw-bold d-inline'>Advisor Name </label>
                        <input id='AdvisorName' class='form-control shadow-sm' name='AdvisorName' 
                        placeholder='Enter advisor name...'/>
                    </div>";
        }

        /**
         * This function retrieves a plan, displays it token/created date/modified date on page, and returns an array
         *      of schedule info if all goes well.
         * @return void no return data. sets $_SESSION planData to array of info if successfully retrieved and echos
         *      HTML to display ID + created/modified date, otherwise echos error text.
         */
        public function displayCreatedPlan(string $uniqueToken)
        {
            $_SESSION['planData'] = '';
            $planArray = $this->databaseFuncs->retrieveSchedule($uniqueToken);
            if($planArray)
            {
                if($planArray['modified_date'])
                {
                    $modDate = $planArray['modified_date'];
                } else
                {
                    $modDate = "NO UPDATES YET";
                }
                echo "
                    <div id='" . $planArray['schedule_id'] . "' class='container-fluid submit_id'>
                        <h3 class='text-center'>Schedule Token: " . $planArray['schedule_id'] . "</h3>
                        <h4 class='text-center'>Schedule Created: " . $planArray['created_date'] . "</h4>
                        <h4 class='text-center'>Last Updated: " . $modDate . "</h4>
                        <div class='w-50 mx-auto'>
                            <label for='AdvisorName' class='form-label fw-bold d-inline'>Advisor Name </label>
                            <input id='AdvisorName' class='quarterInput form-control shadow-sm' name='AdvisorName' 
                                                    value='" . $planArray['advisor_name'] . "' />
                        </div>
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
         * @param $id String plan ID
         * @param $advisor String advisor name
         * @param $fall String fall quarter info
         * @param $winter String winter quarter info
         * @param $spring String spring quarter info
         * @param $summer String summer quarter info
         * @return string if true, a success message. if false, an error message.
         */
        public function createNewPlan(string $id, string $advisor, string $fall, string $winter, string $spring,
                                      string $summer): string
        {
            /*if there's no existing ID, create new schedule. if there IS an existing ID, run the update function.*/
            if($this->databaseFuncs->checkTokens($id))
            {
                if($this->databaseFuncs->createNewSchedule($id, $advisor, $fall, $winter, $spring, $summer))
                {
                    return "The schedule was successfully created!";
                } else
                {
                    return "Error - the new schedule was not created.";
                }
            } else
            {
                return $this->updateExistingPlan($id, [$advisor, $fall, $winter, $spring, $summer]);
            }
        }

        /**
         * This function gets an array of row arrays from the model and displays them as buttons in
         *      the retrieve schedule view.
         * @return void echo data out
         */
        public function getAllIds()
        {
            $plansArr = $this->databaseFuncs->getAllScheduleIDs();

            echo "
                  <div id='shrinkContainer' class='container-fluid d-flex flex-wrap flex-column'>";
            foreach ($plansArr as $row)
            {
                echo   "<div class='retrievalDiv row mx-5' id='" . $row . "-div'>
                            <button class='retrievalBtn scheduleButton py-0 fs-5' id='" . $row . "' name='" . $row . "'>
                                        " . $row . " </button>
                        </div>";
            }
            echo "</div>";
        }

        /**
         * @param string $uniqueToken schedule_id token for row reference
         * @param array $valsArray values to update. also used to reference $columnArr to see which fields need updating.
         * @return string return string regarding successful or unsuccessful record creation
         */
        public function updateExistingPlan(string $uniqueToken, array $valsArray): string
        {
            $currentValsArr = [];
            $columnArr = ['advisor_name', 'fall_qrtr', 'winter_qrtr', 'spring_qrtr', 'summer_qrtr'];
            $updateStatement = "UPDATE schedules SET ";
            for($x = 0; $x < count($columnArr); $x++)
            {
                if($valsArray[$x] != '')
                {
                     $updateStatement .= $columnArr[$x] . "=?, ";
                     $currentValsArr[] = $valsArray[$x];
                }
            }
            /*trim last ', ' and append 'WHERE schedule_id = ?'*/
            $updateStatement = substr($updateStatement, 0, -2) . " WHERE schedule_id =?;";

            if($this->databaseFuncs->updateSchedule($uniqueToken, $updateStatement, $currentValsArr))
            {
                return "This record was updated properly.";
            } else
            {
                return "Error - record not updated.";
            }
            
        }
    
        /**
         * A simple function to unset the variables used in the $_POST or $_SESSION array
         * @param array $globalInfoArr array the array of values that are used in the 'new schedule' code
         * @return void
         */
        public function unsetVars(array $globalInfoArr)
        {
            foreach($globalInfoArr as $globalInfo)
            {
                if($globalInfo !== 'UniqueID')
                {
                    if(isset($_POST[$globalInfo]))
                    {
                        unset($_POST[$globalInfo]);
                    } else if(isset($_SESSION[$globalInfo]))
                    {
                        unset($_SESSION[$globalInfo]);
                    }
                }
            }
            if(isset($_SESSION['planData']))
            {
                unset($_SESSION['planData']);
            }
        }

    }









