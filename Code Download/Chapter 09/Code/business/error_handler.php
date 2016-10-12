<?php
class ErrorHandler
{
  // Private constructor to prevent direct creation of object
  private function __construct()
  {
  }

  /* Set user error-handler method to ErrorHandler::Handler method */
  public static function SetHandler($errTypes = ERROR_TYPES)
  {
    return set_error_handler(array ('ErrorHandler', 'Handler'), $errTypes);
  }

  // Error handler method
  public static function Handler($errNo, $errStr, $errFile, $errLine)
  {
    /* The first two elements of the backtrace array are irrelevant:
        - ErrorHandler.GetBacktrace
        - ErrorHandler.Handler */
    $backtrace = ErrorHandler::GetBacktrace(2);

    // Error message to be displayed, logged, or mailed
    $error_message = "\nERRNO: $errNo\nTEXT: $errStr" .
                     "\nLOCATION: $errFile, line " .
                     "$errLine, at " . date('F j, Y, g:i a') .
                     "\nShowing backtrace:\n$backtrace\n\n";

    // Email the error details, in case SEND_ERROR_MAIL is true
    if (SEND_ERROR_MAIL == true)
      error_log($error_message, 1, ADMIN_ERROR_MAIL, "From: " .
                SENDMAIL_FROM . "\r\nTo: " . ADMIN_ERROR_MAIL);

    // Log the error, in case LOG_ERRORS is true
    if (LOG_ERRORS == true)
      error_log($error_message, 3, LOG_ERRORS_FILE);

    /* Warnings don't abort execution if IS_WARNING_FATAL is false
       E_NOTICE and E_USER_NOTICE errors don't abort execution */
    if (($errNo == E_WARNING && IS_WARNING_FATAL == false) ||
        ($errNo == E_NOTICE || $errNo == E_USER_NOTICE))
    // If the error is nonfatal ...
    {
      // Show message only if DEBUGGING is true
      if (DEBUGGING == true)
        echo '<div class="error_box"><pre>' . $error_message . '</pre></div>';
    }
    else
    // If error is fatal ...
    {
      // Show error message
      if (DEBUGGING == true)
        echo '<div class="error_box"><pre>'. $error_message . '</pre></div>';
      else
      {
        // Clean output buffer
        ob_clean();

        // Load the 500 page
        include '500.php';

        // Clear the output buffer and stop execution
        flush(); 
        ob_flush(); 
        ob_end_clean(); 
        exit();
      }

      // Stop processing the request
      exit();
    }
  }

  // Builds backtrace message
  public static function GetBacktrace($irrelevantFirstEntries)
  {
    $s = '';
    $MAXSTRLEN = 64;
    $trace_array = debug_backtrace();

    for ($i = 0; $i < $irrelevantFirstEntries; $i++)
      array_shift($trace_array);
    $tabs = sizeof($trace_array) - 1;

    foreach ($trace_array as $arr)
    {
      $tabs -= 1;
      if (isset ($arr['class']))
        $s .= $arr['class'] . '.';
      $args = array ();

      if (!empty ($arr['args']))
        foreach ($arr['args']as $v)
        {
          if (is_null($v))
            $args[] = 'null';
          elseif (is_array($v))
            $args[] = 'Array[' . sizeof($v) . ']';
          elseif (is_object($v))
            $args[] = 'Object: ' . get_class($v);
          elseif (is_bool($v))
            $args[] = $v ? 'true' : 'false';
          else
          {
            $v = (string)@$v;
            $str = htmlspecialchars(substr($v, 0, $MAXSTRLEN));
            if (strlen($v) > $MAXSTRLEN)
              $str .= '...';
            $args[] = '"' . $str . '"';
          }
        }

      $s .= $arr['function'] . '(' . implode(', ', $args) . ')';
      $line = (isset ($arr['line']) ? $arr['line']: 'unknown');
      $file = (isset ($arr['file']) ? $arr['file']: 'unknown');
      $s .= sprintf(' # line %4d, file: %s', $line, $file);
      $s .= "\n";
    }

    return $s;
  }
}
?>
