<?php
    session_start();

    /**
     * This file contains HTML for a search box in the 'admin schedules' view and a function call to show
     *  all existing schedules.
     * @version 1.0
     * @author Conor O'Brien
     */

    echo "
            <div class='container-fluid px-0'>
                <div class='container-fluid d-flex row justify-content-center mx-0'>
                    <div class=' col-6 mx-5 mb-3 text-center'>
                        <label for='searchSchedules' class='form-label fw-bold'>
                            Start typing a schedule token to narrow choices:
                        </label>
                        <input id='searchSchedules' type='search' class='form-control shadow' 
                                    placeholder='Search...' aria-label='Search through schedule IDs.'/>
                    </div>
                </div>
            </div>";



    $controllerObject->getAllIds();