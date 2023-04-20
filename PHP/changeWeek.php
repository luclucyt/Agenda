<?php
        //get the week start and end dates if not already set
        if(!isset($_SESSION['week_start']) || !isset($_SESSION['week_end'])) {
            $date = date('Y-m-d');
            $current_week_day = date('N', strtotime($date));
            $week_start = date('Y-m-d', strtotime('-' . ($current_week_day - 1) . ' days', strtotime($date)));
            $week_end = date('Y-m-d', strtotime('+' . (7 - $current_week_day) . ' days', strtotime($date)));

            $_SESSION['week_start'] = $week_start;
            $_SESSION['week_end'] = $week_end;
        } else {
            $week_start = $_SESSION['week_start'];
            $week_end = $_SESSION['week_end'];
        }

        if(isset($_POST['prev_week'])) {
            //move the week start and end dates back one week
            $week_start = $_SESSION['week_start'] = date('Y-m-d', strtotime('-1 week', strtotime($_SESSION['week_start'])));
            $week_end = $_SESSION['week_end'] = date('Y-m-d', strtotime('-1 week', strtotime($_SESSION['week_end'])));
        }

        if(isset($_POST['this_week'])) {
            $date = date('Y-m-d');
            $current_week_day = date('N', strtotime($date));
            $week_start = date('Y-m-d', strtotime('-' . ($current_week_day - 1) . ' days', strtotime($date)));
            $week_end = date('Y-m-d', strtotime('+' . (7 - $current_week_day) . ' days', strtotime($date)));

            $_SESSION['week_start'] = $week_start;
            $_SESSION['week_end'] = $week_end;
        }

        if(isset($_POST['next_week'])) {
            //move the week start and end dates forward one week
            $week_start = $_SESSION['week_start'] = date('Y-m-d', strtotime('+1 week', strtotime($_SESSION['week_start'])));
            $week_end = $_SESSION['week_end'] = date('Y-m-d', strtotime('+1 week', strtotime($_SESSION['week_end'])));
        }

        echo "<input type='hidden' id='week_start' value='$week_start'>";