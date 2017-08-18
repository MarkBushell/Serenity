<?php

namespace FileSystem;

/*
|--------------------------------------------------------------------------
| - Error & Exception Handler - Serenity -
|--------------------------------------------------------------------------
|
| PHP File: PHP Error & Exception Handler Class.
| Powered by PHP 7
|
*/
class Serenity {

    /**
     * Serenity Error handler. Convert all Errors to Exceptions by throwing an ErrorException.
     * @param int $level  Error level
     * @param string $message  Error message
     * @param string $file  Filename the error was raised in
     * @param int $line  Line number in the file
     *
     * @return void
     */
    public static function XosError($level, $message, $file, $line) {
        if (error_reporting() !== 0) {  // to keep the @ operator working
            throw new \ErrorException($message, 0, $level, $file, $line);
        }
    }

    /**
     * Serenity Exception handler.
     * @param Exception $XenEx  The exception
     *
     * @return void
     */
     public static function XosException($XenEx) {
         // HTTP Status Code Process
         $StatCode = $XenEx->getCode();
         if ($StatCode != 404) {
             $StatCode = 500;
         }
         // Sending HTTP Status Code Back to user Browser
         http_response_code($StatCode);

         // Check Error Display. If ERROR_MODE CONSTANT VAR is TRUE
         // Display Errors / Exceptions to Developers on Screen
         // Else - Output exceptions to > SerenityLog - Display User friendly
         // Message to End-Users
        if (\App\Mode::ERROR_MODE) {
            echo "<h2>System Fatal Error. Serenity Activated. </h2>";
            echo "<p>Uncaught Exception: '" . get_class($XenEx) . "'</p>";
            echo "<p>Communication Message: '" . $XenEx->getMessage() . "'</p>";
            echo "<p>Xos Stack Trace:<pre>" . $XenEx->getTraceAsString() . "</pre></p>";
            echo "<p>Thrown in '" . $XenEx->getFile() . "' on File Line " . $XenEx->getLine() . "</p>";
        } else {
            $XosLog = dirname(__DIR__) . '/SerenityLog/' . date('Y-m-d') . '-Serenity' . '.txt';
            ini_set('error_log', $XosLog);

            $Serenity = "Uncaught Exception: '" . get_class($XenEx) . "'";
            $Serenity .= " >>>> With Communication Message - '" . $XenEx->getMessage() . "'";
            $Serenity .= " >>>> Xos Stack Trace - " . $XenEx->getTraceAsString();
            $Serenity .= " >>>> Thrown in - '" . $XenEx->getFile() . "' on File Line - " . $XenEx->getLine();
            $Serenity .= " >>>> END EXCEPTION - START NEXT EXCEPTION: <<<< ";

            error_log($Serenity);

            if ($StatCode == 404) {
                echo "<h1>System Page/File Not Found</h1>";
            } else {
                echo "<h1>System Error Occurred</h1>";
            }
        }
    }
}
