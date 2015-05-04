<?php
##################################################
#
# Copyright (c) 2004-2015 OIC Group, Inc.
#
# This file is part of Exponent
#
# Exponent is free software; you can redistribute
# it and/or modify it under the terms of the GNU
# General Public License as published by the Free
# Software Foundation; either version 2 of the
# License, or (at your option) any later version.
#
# GPL: http://www.gnu.org/licenses/gpl.txt
#
##################################################

/**
 * This is the class expValidator
 *
 * @package    Subsystems
 * @subpackage Subsystems
 */
/** @define "BASE" "../../.." */

class expValidator {
	/**
     * Checks if object field contains a value (not empty)
     *
	 * @param $field
	 * @param $object
	 * @param $opts
	 * @return mixed
	 */
	public static function presence_of($field, $object, $opts) {
        $value = trim($object->$field);
        if (empty($value)) {
            return array_key_exists('message', $opts) ? $opts['message'] : ucwords($field)." is a required field";
        } else {
            return true;
        }
    }

	/**
     * Checks if object field contains an numeric value
     *
	 * @param $field
	 * @param $object
	 * @param $opts
	 * @return mixed
	 */
	public static function numericality_of($field, $object, $opts) {
        if (is_numeric($object->$field)) {
            return true;
        } else { 
            return ucwords($field).' must be a valid number.';
        }
    }

	/**
     * Checks if object field contains an alphanumeric value
     *
	 * @param $field
	 * @param $object
	 * @param $opts
	 * @return mixed
	 */
	public static function alphanumericality_of($field, $object, $opts) {
        $reg = "#[^a-z0-9\s-_)(/]#i";
        $count = preg_match($reg, $object->$field, $matches);
        
        if ($count > 0){
            return ucwords($field).' contains invalid characters.';
        } else { 
            return true;
        }
    }

	/**
     * Checks if object field value is between two limits
     *
	 * @param $field
	 * @param $object
	 * @param $opts
	 * @return mixed
	 */
	public static function value_between($field, $object, $opts) {
        // make sure the value is numeric
        if (!is_numeric($object->$field)) return ucwords($field).' must be a valid number.';
        if ($object->$field < $opts['range']['from'] || $object->$field > $opts['range']['to']) {
            return array_key_exists('message', $opts) ? $opts['message'] : ucwords($field)." must be a value between ".$opts['range']['to']." and ".$opts['range']['from'] ;
        } else {
            return true;
        }
    }

	/**
     * Checks if object field is set (boolean true)
     *
	 * @param $field
	 * @param $object
	 * @param $opts
	 * @return mixed
	 */
	public static function acceptance_of($field, $object, $opts) {
        if (empty($object->$field)) {
            return array_key_exists('message', $opts) ? $opts['message'] : ucwords($field)." must be accepted.";
        } else {
            return true;
        }
    }

	/**
	 * @param $field
	 * @param $object
	 * @param $opts
	 */
	public static function confirmation_of($field, $object, $opts) {
        //STUB::TODO        
    }

	/**
	 * @param $field
	 * @param $object
	 * @param $opts
	 */
	public static function format_of($field, $object, $opts) {
        //STUB::TODO    
    }

	/**
     * Checks if object field is minimum length
     *
	 * @param $field
	 * @param $object
	 * @param $opts
	 * @return mixed
	 */
	public static function length_of($field, $object, $opts) {
        if (strlen($object->$field) < $opts['length']) {
            return array_key_exists('message', $opts) ? $opts['message'] : ucwords($field)." must be longer than ".$opts['length']." characters long.";
        } else {
            return true;
        } 
    }

	/**
     * Check for uniqueness of object field amongst all items in table
     *
	 * @param $field
	 * @param $object
	 * @param $opts
	 * @return mixed
	 */
	public static function uniqueness_of($field, $object, $opts) {
        global $db;

        $sql = "`".$field."`='".$object->$field."'";
        if (!empty($object->id)) $sql .= ' AND id != '.$object->id;
        if (array_key_exists('grouping_sql', $opts)) $sql .= $opts['grouping_sql'];  // allow grouping sql parameter to be passed
        $ret = $db->countObjects($object->tablename, $sql);
        if ($ret > 0) {
            return array_key_exists('message', $opts) ? $opts['message'] : ucwords($field).' "'.$object->$field.'" is already in use.';
        } else {
            return true;
        }
    }

	/**
     * Checks if object files contains a valid zipcode
     *
	 * @param $field
	 * @param $object
	 * @param $opts
	 * @return mixed
	 */
	public static function is_valid_zipcode($field, $object, $opts) {
        $match = array();
        if($object->country != 223) return true; //if non-us, than we won't validate the zip
        
		$pattern = "/^\d{5}([\-]\d{4})?$/";
		if (!preg_match($pattern, $object->$field, $match, PREG_OFFSET_CAPTURE)) {
			return array_key_exists('message', $opts) ? $opts['message'] : ucwords($field)." is not a valid US zip code.";
		} else {
			return true;
		}
    }

	/**
     * Checks if object field contains a valid phone number
     *
	 * @param $field
	 * @param $object
	 * @param $opts
	 * @return mixed
	 */
	public static function is_valid_phonenumber($field, $object, $opts) {
        $match = array();
		$pattern = "/^((\+\d{1,3}(-| )?\(?\d\)?(-| )?\d{1,5})|(\(?\d{2,6}\)?))(-| )?(\d{3,4})(-| )?(\d{4})(( x| ext)\d{1,5}){0,1}$/";
		if (!preg_match($pattern, $object->$field, $match, PREG_OFFSET_CAPTURE)) {
			return array_key_exists('message', $opts) ? $opts['message'] : ucwords($field)." is not a valid US zip code.";
		} else {
			return true;
		}
    }

    /**
     * Checks if object field contains a valid state abbreviation
     *
     * @param $field
     * @param $object
     * @param $opts
     *
     * @return bool|string
     */
    public static function is_valid_state($field, $object, $opts) {        
        if(($object->state == -2 && !empty($object->non_us_state)) || $object->country != 223 || $object->state > 0) {
            return true; //supplied a non-us state/province, so we're OK
        } else {
            return array_key_exists('message', $opts) ? $opts['message'] : ucwords($field)." is not a valid US state abbreviation.";
        }
    }
    
	/**
     * Checks if object field contains a valude email address
     *
	 * @param $field
	 * @param $object
	 * @param $opts
	 * @return mixed
	 */
	public static function is_valid_email($field, $object, $opts) {
        $email = $object->$field;
        // First, we check that there's one @ symbol, and that the lengths are right
//        if (!ereg("^[^@]{1,64}@[^@]{1,255}$", $email)) {

        //
        if(self::isValidEmail($email))
        {
            return true; 
        }
        else
        {
            return array_key_exists('message', $opts) ? $opts['message'] : ucwords($field)." does not appear to be a valid email address";
        }
        //  old code below
//        if (!preg_match("^[^@]{1,64}@[^@]{1,255}$", $email)) {
//            // Email invalid because wrong number of characters in one section, or wrong number of @ symbols.
//            return array_key_exists('message', $opts) ? $opts['message'] : ucwords($field)." does not appear to be a valid email address";
//        }
//        // Split it into sections to make life easier
//        $email_array = explode("@", $email);
//        $local_array = explode(".", $email_array[0]);
//        for ($i = 0; $i < sizeof($local_array); $i++) {
//            if (!preg_match("^(([A-Za-z0-9!#$%&'*+/=?^_`{|}~-][A-Za-z0-9!#$%&'*+/=?^_`{|}~\.-]{0,63})|(\"[^(\\|\")]{0,62}\"))$", $local_array[$i])) {
//                return array_key_exists('message', $opts) ? $opts['message'] : ucwords($field)." does not appear to be a valid email address";
//            }
//        }
//        if (!preg_match("^\[?[0-9\.]+\]?$", $email_array[1])) { // Check if domain is IP. If not, it should be valid domain name
//            $domain_array = explode(".", $email_array[1]);
//            if (sizeof($domain_array) < 2) {
//                return array_key_exists('message', $opts) ? $opts['message'] : ucwords($field)." does not appear to be a valid email address";
//            }
//            for ($i = 0; $i < sizeof($domain_array); $i++) {
//                if (!preg_match("^(([A-Za-z0-9][A-Za-z0-9-]{0,61}[A-Za-z0-9])|([A-Za-z0-9]+))$", $domain_array[$i])) {
//                    return array_key_exists('message', $opts) ? $opts['message'] : ucwords($field)." does not appear to be a valid email address";
//                }
//            }
//        }
//        return true;
    }

	/**
     * Validate an email address.
     * Provide email address (raw input)
     * Returns true if the email address has the email
     * address format and the domain exists.
     *
	 * @param $email
	 * @return bool
	 */
    public static function isValidEmail($email)
    {
       $isValid = true;
       $atIndex = strrpos($email, "@");
       if (is_bool($atIndex) && !$atIndex)
       {
          $isValid = false;
       }
       else
       {
          $domain = substr($email, $atIndex+1);
          $local = substr($email, 0, $atIndex);
          $localLen = strlen($local);
          $domainLen = strlen($domain);
          if ($localLen < 1 || $localLen > 64)
          {
             // local part length exceeded
             $isValid = false;
          }
          else if ($domainLen < 1 || $domainLen > 255)
          {
             // domain part length exceeded
             $isValid = false;
          }
          else if ($local[0] == '.' || $local[$localLen-1] == '.')
          {
             // local part starts or ends with '.'
             $isValid = false;
          }
          else if (preg_match('/\\.\\./', $local))
          {
             // local part has two consecutive dots
             $isValid = false;
          }
          else if (!preg_match('/^[A-Za-z0-9\\-\\.]+$/', $domain))
          {
             // character not valid in domain part
             $isValid = false;
          }
          else if (preg_match('/\\.\\./', $domain))
          {
             // domain part has two consecutive dots
             $isValid = false;
          }
          else if (!preg_match('/^(\\\\.|[A-Za-z0-9!#%&`_=\\/$\'*+?^{}|~.-])+$/', str_replace("\\\\","",$local)))
          {
             // character not valid in local part unless 
             // local part is quoted
             if (!preg_match('/^"(\\\\"|[^"])+"$/', str_replace("\\\\","",$local)))
             {
                $isValid = false;
             }
          }
          /*if ($isValid && !(checkdnsrr($domain,"MX") ||  checkdnsrr($domain,"A")))
          {
             // domain not found in DNS
             $isValid = false;
          }*/
       }
       return $isValid;
    }

	/**
     * Checks if object field contains a valid sef name
     *
	 * @param $field
	 * @param $object
	 * @param $opts
	 * @return mixed
	 */
	public static function is_valid_sef_name($field, $object, $opts) {
		$match = array();
		$pattern = "/([^0-9a-z-_\+\.])/i";
		if (empty($object->$field) || preg_match($pattern, $object->$field, $match, PREG_OFFSET_CAPTURE)) {
			return array_key_exists('message', $opts) ? $opts['message'] : ucwords($field)." is not a valid sef url.";   
		} else {
			return true;
		}
    }

	/**
     * Checks validity of recaptcha antispam entry
     *
	 * @param $params
	 * @param string $msg
	 * @return bool
	 */
	public static function check_antispam($params, $msg="") {
		global $user;

		if (SITE_USE_ANTI_SPAM == 0 || ($user->isLoggedIn() && ANTI_SPAM_USERS_SKIP == 1)) {
			return true;
		}
        $msg = empty($msg) ? gt('Anti-spam verification failed.  Please try again.') : $msg;
        switch (ANTI_SPAM_CONTROL) {
            case 'recaptcha':
                if (empty($params["recaptcha_response_field"])) {
                    self::failAndReturnToForm($msg, $params);
                } 
                
                if (!defined('RECAPTCHA_PRIVATE_KEY')) {
                    self::failAndReturnToForm(gt('reCAPTCHA is not properly configured. Please contact an administrator.'), $params);
                }
                
//                require_once(BASE.'external/recaptchalib.php');
                require_once(BASE . 'external/ReCaptcha/ReCaptcha.php');
                $reCaptcha = new ReCaptcha(RECAPTCHA_PRIVATE_KEY);

//                $resp = recaptcha_check_answer (RECAPTCHA_PRIVATE_KEY,$_SERVER["REMOTE_ADDR"],$params["recaptcha_challenge_field"],$params["recaptcha_response_field"]);
//                if ($params["g-recaptcha-response"]) {  //FIXME swap params for new v1.1.1 ->verify(resp,ip)
//                    $resp = $reCaptcha->verifyResponse(
//                        $_SERVER["REMOTE_ADDR"],
//                        $params["g-recaptcha-response"]
//                    );
//                }
                if ($params["g-recaptcha-response"]) {
                    $resp = $reCaptcha->verify(
                        $params["g-recaptcha-response"],
                        $_SERVER["REMOTE_ADDR"]
                    );
                }

//                if ($resp->is_valid) {
                if (!empty($resp->success)) {
                    return true;
                } else {
                    //Compatibility with old school form module - prb
                    if (!isset($params['manual_redirect'])) {
                        self::failAndReturnToForm($msg, $params);
                    } else {
                        return false;
                    };
                }
            break;
            case 0:
                return true;
            break;
        }
        return false;
    }

	/**
     * All purpose validation method
     *
	 * @param $vars
	 * @param $post
	 * @return bool
	 */
	public static function validate($vars, $post) {
        if (!is_array($vars)) return false;

        $post['_formError'] = array();
        foreach($vars as $validate_type=>$param) {
            switch($validate_type) {
//                case 'captcha':
//                case 'capcha':
//                    $captcha_real = expSession::get('captcha_string');
//                    if (SITE_USE_ANTI_SPAM && strtoupper($post[$param]) != $captcha_real) {
//                            unset($post[$param]);
//                            $post['_formError'][] = gt('Captcha Verification Failed');
//                    }
//                break;
                case 'presence_of':
                    if (empty($post[$param])) $post['_formError'][] = $param.' is a required field.';
                break;
                case 'valid_email':
                    if (empty($post[$param])) {
                        $post['_formError'][] = $param.' is a required field.';
                    } elseif (!self::validate_email_address($post[$param])) {
                        $post['_formError'][] = $post[$param].' does not appear to be a valid email address.';
                    }
                break;
            }
        }
        
        if (count($post['_formError']) > 0) {
            self::failAndReturnToForm($post['_formError'], $post);
        }
        return true;
    }

	/**
     * Routine to note error and return to the filled out form with messages
     *
	 * @param string $msg
	 * @param null $post
	 */
	public static function failAndReturnToForm($msg='', $post=null) {
        if (!is_array($msg)) $msg = array($msg);
        flash('error', $msg);
        if (!empty($post)) expSession::set('last_POST',$post);
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit();
    }

	/**
     * Routine to establish error fields
     *
	 * @param $field
	 */
	public static function setErrorField($field) {
        $errors = expSession::get('last_post_errors');
        if (!in_array($field, $errors)) $errors[] = $field;
        expSession::set('last_post_errors', $errors);
    }

	/**
     * Routine to  error and return to the filled out form with flash message
     *
	 * @param string $queue
	 * @param $msg
	 * @param null $post
	 */
	public static function flashAndReturnToForm($queue='message', $msg, $post=null) {
        if (!is_array($msg)) $msg = array($msg);
        flash($queue, $msg);
        if (!empty($post)) expSession::set('last_POST',$post);
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit();
    }

	/**
     * Wrapper function to validate email address
     *
	 * @param $email
	 * @return bool
	 */
	public static function validate_email_address($email) {
		return self::isValidEmail($email);

		// old code
//        // First, we check that there's one @ symbol, and that the lengths are right
//        if (!preg_match("^[^@]{1,64}@[^@]{1,255}$", $email)) {
//            // Email invalid because wrong number of characters in one section, or wrong number of @ symbols.
//            return false;
//        }
//        // Split it into sections to make life easier
//        $email_array = explode("@", $email);
//        $local_array = explode(".", $email_array[0]);
//        for ($i = 0; $i < sizeof($local_array); $i++) {
//            if (!preg_match("^(([A-Za-z0-9!#$%&'*+/=?^_`{|}~-][A-Za-z0-9!#$%&'*+/=?^_`{|}~\.-]{0,63})|(\"[^(\\|\")]{0,62}\"))$", $local_array[$i])) {
//                return false;
//            }
//        }
//        if (!preg_match("^\[?[0-9\.]+\]?$", $email_array[1])) { // Check if domain is IP. If not, it should be valid domain name
//            $domain_array = explode(".", $email_array[1]);
//            if (sizeof($domain_array) < 2) {
//                return false; // Not enough parts to domain
//            }
//            for ($i = 0; $i < sizeof($domain_array); $i++) {
//                if (!preg_match("^(([A-Za-z0-9][A-Za-z0-9-]{0,61}[A-Za-z0-9])|([A-Za-z0-9]+))$", $domain_array[$i])) {
//                    return false;
//                }
//            }
//        }
//        return true;
    }

	/**
     * Determines if upload was successfully inserted into 'file' table
     *
	 * @param $file
	 * @return mixed
     * @deprecated
	 */
	public static function uploadSuccessful($file) {
        global $db;

        if (is_object($file)) {
            return $db->insertObject($file,'file');
        } else {
            $post = expString::sanitize($_POST);
            $post['_formError'] = $file;
            flash('error',$file);
            expSession::set('last_POST',$post);
            header('Location: ' . $_SERVER['HTTP_REFERER']);
            exit();
        }
    }

    /**
     * Method to determine password strength
     *   e.g., does not match username and is greater than 8 characters
     * @param $username
     * @param $password
     *
     * @return string
     */
    public static function checkPasswordStrength($username,$password) {
		// Return blank string on success, error message on failure.
		// The error message should let the user know why their password is wrong.
		if (strcasecmp($username,$password) == 0) {
			return gt('Password cannot be equal to the username.');
		}
		# For example purposes, the next line forces passwords to be over 8 characters long.
		if (strlen($password) < 8) {
			return gt('Passwords must be at least 8 characters long.');
		}

		return ""; // by default, accept any passwords
	}

    /**
     * Routine to check that username is longer than 3 characters
     *
     * @param $username
     *
     * @return string
     */
    public static function checkUsername($username) {
		// Return blank string on success, error message on failure.
		// The error message should let the user know why their username is wrong.
		if (strlen($username) < 3) {
			return gt('Your username must be at least 3 characters.');
		}
		//echo "<xmp>";
		//print_r(preg_match("/^[a-zA-Z0-9]/",$username));
		//echo "</xmp>";
		//exit;

		//if (!preg_match("/[a-zA-Z0-9]/",$username)){
		//	return gt('Your username contains illegal characters.');
		//}
		return ""; // by default, accept any passwords
	}
}

?>