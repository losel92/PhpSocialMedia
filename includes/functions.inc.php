<?php

function CalculateAge($birthday){
	$age = floor((time() - strtotime($birthday)) / 31556926);
	return $age;
}