<?php

if (!function_exists('generatePnr')) {
    function generatePnr()
    {
        return 'PNR' . strtoupper(uniqid());
    }
}
