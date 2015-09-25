<?php 

namespace App;

use Illuminate\Support\MessageBag;

trait ErrorTrait {

	protected $errors;

	//
	function setErrors(MessageBag $errors)
	{
		$this->errors = $errors;
	}

	function setError(MessageBag $errors)
	{
		$this->errors = $errors;
	}

	function getError()
	{
		return $this->errors;
	}

	function getErrors()
	{
		return $this->errors;
	}
}
