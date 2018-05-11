<?php

//use Illuminate\Http\Request;
use Illuminate\Support\Facades\Request;

function setActive($uri)
{
	return Request::is($uri) ? 'active text-dark' : '';
}