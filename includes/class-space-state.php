<?php

/**
 * Get the Space Open/Close state from external sources
 *
 * For now, only get state from the Wordpress Opening Hours plugin
 *
 * @since 0.3
 */
class Space_State
{

    public function is_open()
    {
        //TODO add cases based on external sources
        //TODO add enabled/available Opening Hours plugin check
        return $this->get_opening_hours_state();
    }

    /**
     * Get the open/close status from the Opening Hours plugin
     *
     * @return bool $is_open
     */
    private function get_opening_hours_state()
    {
        if (function_exists('is_open')){
            return is_open();
        }
        else {
            return null;
        }
    }
}
