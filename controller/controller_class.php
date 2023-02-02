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
                    <h3 class='text-center'><strong>Schedule Token: </strong>" . $thisUniqueId . "</h3>";
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
                        <input id='AdvisorName' class='form-control shadow' name='AdvisorName'
                        placeholder='Enter advisor name...'/>
                    </div>";
            $this->anotherYearButton(0);
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
                $_SESSION['planRetrieved'] = true;
                if($planArray['modified_date'])
                {
                    $modDate = $planArray['modified_date'];
                } else
                {
                    $modDate = "NO UPDATES YET";
                }
                echo "
                    <div id='" . $planArray['schedule_id'] . "-" . $planArray['plan_year'] . "' 
                    class='container-fluid submit_id'>
                        <h3 class='text-center'><strong>Schedule Token: </strong>" .$planArray['schedule_id']. "</h3>
                        <h4 class='text-center'><strong>Schedule Created: </strong>" .$planArray['created_date']. "</h4>
                        <h4 class='text-center'><strong>Last Updated: </strong>" .$modDate. "</h4>
                        <h4 class='hide-for-print print text-center'><strong>Advisor Name: </strong>" .$planArray['advisor_name']. "</h4>
                        <div class='no-print w-50 mx-auto'>
                            <label for='AdvisorName' class='form-label fw-bold d-inline'>Advisor Name </label>
                            <input id='AdvisorName' class='quarterInput form-control shadow' name='AdvisorName'
                                                    value='" . $planArray['advisor_name'] . "' />
                        </div>
                    </div>";
                $this->anotherYearButton(0);
                $_SESSION['planData'] = $planArray;
            } else
            {
                $_SESSION['planRetrieved'] = false;
                echo "
                <div class='container-fluid'>
                    <h3 class='text-center text-danger'>ERROR. Plan data not found!</h3>
                </div>";
                include('../include/get_schedule_form.php');
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
            $fallInfo = explode('-', $fall)[0];
            $winterInfo = explode('-', $winter)[0];
            $springInfo = explode('-', $spring)[0];
            $summerInfo = explode('-', $summer)[0];
            /*if there's no existing ID, create new schedule. if there IS an existing ID, run the update function.*/
            if($this->databaseFuncs->checkTokens($id))
            {
                if($this->databaseFuncs->createNewSchedule($id, $advisor, $fallInfo, $winterInfo, $springInfo, $summerInfo))
                {
                    return "The schedule was successfully created!";
                } else
                {
                    return "Error - the new schedule was not created.";
                }
            } else
            {
                return $this->updateExistingPlan($id, [$advisor, $fallInfo, $winterInfo, $springInfo, $summerInfo]);
            }
        }

        /**
         * This function gets an array of row arrays from the model and displays them as buttons in
         *      the retrieve schedule view.
         * @return void echo data out
         */
        public function getAllIds()
        {
            $plansArr = $this->databaseFuncs->getAllSchedules();

            echo "
                  <div id='shrinkContainer' class='container-fluid d-flex flex-wrap flex-column'>
                    <div class='retrievalDiv row mx-5'>
                        <div class='retrievalBtn scheduleButton py-0 fs-5 d-flex justify-content-between align-items-center'>
                            <div class='ps-2 col-4 text-center fw-bold'>SCHEDULE ID</div>
                            <div class='col-4 text-center fw-bold'>ADVISOR NAME</div>
                            <div class='pe-2 col-4 text-center fw-bold'>CREATED DATE</div>
                        </div>
                    </div>
                  ";
            foreach ($plansArr as $row)
            {
                if($row['advisorName'] == '')
                {
                    $row['advisorName'] = 'No advisor entered!';
                }
                echo   "
                        <div class='retrievalDiv row mx-5' id='" .$row['scheduleId']. "-div'>
                            <button type='button' class='retrievalBtn scheduleButton py-0 fs-5 d-flex justify-content-between align-items-center'
                            id='" .$row['scheduleId']. "' name='" .$row['schedule_id']. "'>
                                <div class='ps-2 col-4'>".$row['scheduleId']."</div>
                                <div class='col-4'>".$row['advisorName']."</div>
                                <div class='pe-2 col-4'>".$row['createdDate']."</div>
                            </button>
                        </div>";
            }
            echo "
                   </div>";
        }

        /**
         * @param string $uniqueToken schedule_id token for row reference
         * @param array $valsArray values to update. also used to ref $columnArr to see which fields need updating.
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

        /*TODO: make the error return more comprehensive. True or false don't tell whether there was an error
            with the email or password*/
        /**
         * This function is used when an admin is attempting to log into the site to view all schedules.
         * @param string $email admin email
         * @param string $password admin password
         * @return bool true if login successful, false if anything goes wrong
         */
        public function adminLogin(string $email, string $password): bool
        {
            $adminArray = $this->databaseFuncs->getAdminInfo($email);

            if($adminArray)
            {
                if($adminArray['password'])
                {
                    $checkProvidedPass = $this->hashPass($password);
                    if($checkProvidedPass === $adminArray['password'])
                    {
                        $_SESSION['adminLogged'] = true;
                        return true;
                    }
                }
            } else
            {
                return false;
            }
            return false;
        }

        /**
         * This function destroys the session array and refreshes the page (essentially logging a user out).
         * @return void
         */
        public function logout()
        {
            session_destroy();
        }

        /**
         * This method takes an input user password and returns a hashed version. It appends a salt to the password
         *  before hashing using sha256. Used for new user creation, as well as for validation for login.
         * @param string $userPass user password
         * @return string hashed password with salt
         */
        public function hashPass(string $userPass): string
        {
            $userPass = $userPass . "adviseIT";
            return hash("sha256", $userPass);
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

        /**
         * This function creates a button that will add another schedule year before or after a plan year based on
         *      the passed in parameter.
         * @param int $upOrDown 0 is one up (year before lowest year displayed),
         *      1 is down (year after highest year displayed)
         * @return void
         */
        public function anotherYearButton(int $upOrDown)
        {
            $upOrDown === 0 ? $prevOrNext = 'Previous Year' : $prevOrNext = 'Next Year';
            $upOrDown === 0 ? $chevronDirection = 'up' : $chevronDirection = 'down';
            echo "
                <div id='year-" .$chevronDirection. "' class='text-center my-2 no-print'>
                    <button id='year-" .$chevronDirection. "-button' type='button' class='yearButton'>
                        <i class='bi bi-chevron-bar-" .$chevronDirection. "'></i> " .$prevOrNext. "
                    </button>
                </div>";
        }
    }









