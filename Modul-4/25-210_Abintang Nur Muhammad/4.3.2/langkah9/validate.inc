<?php
function validateName(&$errors, $field_list, $field_name)
{
    $pattern = "/^[a-zA-Z'-]+$/"; // format surname (alfabet)
    if (!isset($field_list[$field_name]) || empty($field_list[$field_name]))
        $errors[$field_name] = 'required';
    else if (!preg_match($pattern, $field_list[$field_name]))
        $errors[$field_name] = 'invalid (only letters, apostrophe and hyphen are allowed)';
}

function validateEmail(&$errors, $field_list, $field_name)
{
    $pattern = "/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,})$/i"; // format email
    if (!isset($field_list[$field_name]) || empty($field_list[$field_name]))
        $errors[$field_name] = 'required';
    else if (!preg_match($pattern, $field_list[$field_name]))
        $errors[$field_name] = 'invalid (format must be like name@example.com)';
}

function validatePassword(&$errors, $field_list, $field_name)
{
    if (!isset($field_list[$field_name]) || empty($field_list[$field_name]))
        $errors[$field_name] = 'required';
    else if (strlen($field_list[$field_name]) < 6)
        $errors[$field_name] = 'invalid (minimum 6 characters)';
}

function validateAddress(&$errors, $field_list, $field_name)
{
    if (!isset($field_list[$field_name]) || trim($field_list[$field_name]) == '')
        $errors[$field_name] = 'required';
}

function validateState(&$errors, $field_list, $field_name)
{
    if (!isset($field_list[$field_name]) || $field_list[$field_name] == '-1')
        $errors[$field_name] = 'required (please choose your state)';
    else if (!preg_match("/^[1-8]$/", $field_list[$field_name]))
        $errors[$field_name] = 'invalid';
}

function validateGender(&$errors, $field_list, $field_name)
{
    if (!isset($field_list[$field_name]) || empty($field_list[$field_name]))
        $errors[$field_name] = 'required';
    else if ($field_list[$field_name] != 'male' && $field_list[$field_name] != 'female')
        $errors[$field_name] = 'invalid';
}
?>
