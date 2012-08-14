<?php

define( 'textValidatorEmailAddress', 'Email address' );
define( 'textValidatorPassword', 'Password' );
define( 'textValidatorUsername', 'Username' );
define( 'textValidatorInvalid', ' is invalid.' );
define( 'textValidatorInvalidCharacters', ' contains invalid characters.' );
define( 'textValidatorTooLong', ' is too long.' );
define( 'textValidatorTooShort', ' is too short.' );

define( 'errorValidatorEmailAddressInvalid', textValidatorEmailAddress.textValidatorInvalid );
define( 'errorValidatorEmailAddressTooLong', textValidatorEmailAddress.textValidatorTooLong );
define( 'errorValidatorUsernameContainsInvalidCharacters', textValidatorUsername.textValidatorInvalidCharacters );
define( 'errorValidatorUsernameNumeric', textValidatorUsername.' is numeric.' );
define( 'errorValidatorUsernameTooLong', textValidatorUsername.textValidatorTooLong );
define( 'errorValidatorUsernameTooShort', textValidatorUsername.textValidatorTooShort );
define( 'errorValidatorPasswordContainsInvalidCharacters', textValidatorPassword.textValidatorInvalidCharacters );
define( 'errorValidatorPasswordTooLong', textValidatorPassword.textValidatorTooLong );
define( 'errorValidatorPasswordTooShort', textValidatorPassword.textValidatorTooShort );
define( 'errorValidatorPasswordsDoNotMatch', textValidatorPassword.'s do not match.' );

?>