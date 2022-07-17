<?php

    session_start();

    if(isset($_SESSION['success']))
    {
        echo '<a class="toast-msg toast-close flex items-center justify-between p-4 text-sm font-semibold text-green-600 bg-green-300 rounded-lg shadow-md focus:outline-none focus:shadow-outline-green" href="javascript::">
              <div class="flex items-center">
                <span>Success '.$_SESSION['success'].'</span>
              </div>
              <span><i class="fas fa-times"></i></span>
            </a>';
        unset($_SESSION['success']);
    }

    if(isset($_SESSION['failure']))
    {
         echo '<a class="toast-msg toast-close flex items-center justify-between p-4 text-sm font-semibold text-red-600 bg-red-300 rounded-lg shadow-md focus:outline-none focus:shadow-outline-red" href="javascript::">
              <div class="flex items-center">
                <span>Error '.$_SESSION['failure'].'</span>
              </div>
              <span><i class="fas fa-times"></i></span>
            </a>';
        unset($_SESSION['failure']);
    }

    if(isset($_SESSION['info']))
    {
         echo '<a class="toast-msg toast-close flex items-center justify-between p-4 text-sm font-semibold text-yellow-600 bg-yellow-300 rounded-lg shadow-md focus:outline-none focus:shadow-outline-yellow" href="javascript::">
              <div class="flex items-center">
                <span>Warning '.$_SESSION['info'].'</span>
              </div>
              <span><i class="fas fa-times"></i></span>
            </a>';
        unset($_SESSION['info']);
    }

?>
