<?php
namespace Cars;

class CarsException extends \Exception
{
    // Redefine the exception so message isn't optional
    public function __construct($message, $app, \Exception $previous = null) {
        // make sure everything is assigned properly
        parent::__construct($message, 0, $previous);

        //Log this exception
        $app->logger->error($this);
        if($previous) {
            $app->logger->error("Rethrows: " . $previous);
        }
    }

    // custom string representation of object
    public function __toString() {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\nFile: {$this->file}\nLine: {$this->line}";
    }
}
