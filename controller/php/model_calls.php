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
                    <h3 class='text-center'>Schedule Token: " . $thisUniqueId . "</h3>
                </div>
            ";
        }

    }









