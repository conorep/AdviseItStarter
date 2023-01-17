<?php
    session_start();

    /**
     * This is an un-finished class for handling routes. It will be possible to use six-digit URL routes
     *      to fetch an existing schedule record from the database and display it once everything is functional.
     */
    class Route
    {
        /**
         * This function sends a user to a view of a retrieved schedule when they input the token code in the URL.
         * @param $route String route address
         * @param $file String location of the file to show if route address matched
         * @return void
         */
        public function addRoute(string $route, string $file)
        {
            /*remove start/end slashes from the route request and check against request URI*/
            if(!empty($_REQUEST['uri'])){
                $route = preg_replace("/(^\/)|(\/$)/","",$route);
                $requestURI =  preg_replace("/(^\/)|(\/$)/","",$_REQUEST['uri']);
            }else{
                $requestURI = "";
            }

            /*if route and requestURI are the same after regex*/
            if($requestURI == $route && strlen($requestURI) === 6)
            {
                print($requestURI);
                //on match load the view
                $_SESSION['getSchedule'] = $requestURI;
                include($file);

                //exit because route address matched.
                exit();
            }
        }



    }