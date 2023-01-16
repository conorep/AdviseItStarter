<?php

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
         * @return void
         */
        function displayUniqueToken()
        {
            $thisUniqueId = $this->databaseFuncs->generateUniqueID();
            echo "
                <div id='" . $thisUniqueId . "' class='container-fluid'>
                    <h3 class='text-center' >Schedule Token: " . $thisUniqueId . "</h3>
                    <input name='UniqueID' class='d-none' value=" . $thisUniqueId . " />
                </div>
            ";
        }

        /**
         * This function retrieves a plan and displays it on the page.
         * @return void
         */
        function displayCreatedPlan()
        {

        }

        /**
         * This function creates a new plan.
         * @return bool|mysqli_result
         */
        function createNewPlan($id, $fall, $winter, $spring, $summer)
        {
            //TODO: validation here! PRE db access.
            return $this->databaseFuncs->createNewSchedule($id, $fall, $winter, $spring, $summer);
        }

        /*THIS IS FOR TESTING THAT DIFFERENT VIEWS CAN ACCESS A CREATED OBJECT*/
        function testingStuff()
        {
            return "hi, you've reached the object";
        }

    }









