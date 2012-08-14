<?php

////////////////////////////////////////////////////////////////////////////////

require( 'include.validator.'.language.'.php' );

////////////////////////////////////////////////////////////////////////////////

/**
 *  Validator superclass for form validation
 */
class Validator {
    /**
    * Private
    * $errorMsg stores error messages if not valid
    */
    var $errorMsg;
 
    //! A constructor.
    /**
    * Constucts a new Validator object
    */
    function Validator () {
        $this->errorMsg=array();
        $this->validate();
    }
 
    //! A manipulator
    /**
    * @return void
    */
    function validate() {
        // Superclass method does nothing
    }
 
    //! A manipulator
    /**
    * Adds an error message to the array
    * @return void
    */
    function setError ($msg) {
        $this->errorMsg[]=$msg;
    }
 
    //! An accessor
    /**
    * Returns true is string valid, false if not
    * @return boolean
    */
    function isValid () {
        if ( isset ($this->errorMsg) ) {
            return false;
        } else {
            return true;
        }
    }
 
    //! An accessor
    /**
    * Pops the last error message off the array
    * @return string
    */
    function getError () {
        return array_pop($this->errorMsg);
    }
}
 
/**
 *  ValidatorUser subclass of Validator
 *  Validates a username
 */
class ValidateUser extends Validator {
    /**
    * Private
    * $user the username to validate
    */
    var $user;
 
    //! A constructor.
    /**
    * Constucts a new ValidateUser object
    * @param $user the string to validate
    */
    function ValidateUser ($user) {
        $this->user=$user;
        Validator::Validator();
    }
 
    //! A manipulator
    /**
    * Validates a username
    * @return void
    */
    function validate() {
        if (!preg_match('/^[a-zA-Z0-9_]+$/',$this->user )) {
            $this->setError(errorValidatorUsernameContainsInvalidCharacters);
        }
        if (strlen($this->user) < 4 ) {
            $this->setError(errorValidatorUsernameTooShort);
        }
        if (strlen($this->user) > 32 ) {
            $this->setError(errorValidatorUsernameTooLong);
        }
        if (is_numeric($this->user) ) {
        	$this->setError(errorValidatorUsernameNumeric);
        }
    }
}
 
/**
 *  ValidatorPassword subclass of Validator
 *  Validates a password
 */
class ValidatePassword extends Validator {
    /**
    * Private
    * $pass the password to validate
    */
    var $pass;
    /**
    * Private
    * $conf to confirm the passwords match
    */
    var $conf;
 
    //! A constructor.
    /**
    * Constucts a new ValidatePassword object subclass or Validator
    * @param $pass the string to validate
    * @param $conf to compare with $pass for confirmation
    */
    function ValidatePassword ($pass,$conf) {
        $this->pass=$pass;
        $this->conf=$conf;
        Validator::Validator();
    }
 
    //! A manipulator
    /**
    * Validates a password
    * @return void
    */
    function validate() {
        if ($this->pass!=$this->conf) {
            $this->setError(errorValidatorPasswordsDoNotMatch);
        }
        if (!preg_match('/^[a-zA-Z0-9_]+$/',$this->pass )) {
            $this->setError(errorValidatorPasswordContainsInvalidCharacters);
        }
        if (strlen($this->pass) < 6 ) {
            $this->setError(errorValidatorPasswordTooShort);
        }
        if (strlen($this->pass) > 32 ) {
            $this->setError(errorValidatorPasswordTooLong);
        }
    }
}
 
/**
 *  ValidatorEmail subclass of Validator
 *  Validates an email address
 */
class ValidateEmail extends Validator {
    /**
    * Private
    * $email the email address to validate
    */
    var $email;
 
    //! A constructor.
    /**
    * Constucts a new ValidateEmail object subclass or Validator
    * @param $email the string to validate
    */
    function ValidateEmail ($email){
        $this->email=$email;
        Validator::Validator();
    }
 
    //! A manipulator
    /**
    * Validates an email address
    * @return void
    */
    function validate() {
        $pattern=
    "/^([a-zA-Z0-9])+([.a-zA-Z0-9_-])*@([a-zA-Z0-9_-])+(.[a-zA-Z0-9_-]+)+/";
        if(!preg_match($pattern,$this->email)){
            $this->setError(errorValidatorEmailAddressInvalid);
        }
        if (strlen($this->email)>100){
            $this->setError(errorValidatorEmailAddressTooLong);
        }
    }
}
?>