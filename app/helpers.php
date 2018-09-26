<?php

//use Illuminate\Http\Request;
use Illuminate\Support\Facades\Request;

//function to 
/**
 * Set tabs of navigation bar active.
 *
 * @return string
 */
function setActive($uri)
{
	return Request::is($uri) ? 'active' : '';
}