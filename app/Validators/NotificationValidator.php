<?php

namespace App\Validators;

use \Prettus\Validator\Contracts\ValidatorInterface;
use \Prettus\Validator\LaravelValidator;

/**
 * Class NotificationValidator.
 *
 * @package namespace App\Validators;
 */
class NotificationValidator extends LaravelValidator
{
    CONST RULE_DELETE = 'RULE_DELETE';
    CONST RULE_EDIT   = 'RULE_EDIT';
    /**
     * Validation Rules
     *
     * @var array
     */
    protected $rules = [
        NotificationValidator::RULE_CREATE => [
            'title' => 'required',
            'date' => 'required',
            'time' => 'required',
            'description' => 'required',
        ],
        NotificationValidator::RULE_UPDATE => [],
        NotificationValidator::RULE_DELETE => [
            'id' => 'required|exists:notify,id'
        ],
        NotificationValidator::RULE_EDIT => [
            'id' => 'required|exists:notify,id'
        ],
    ];
}
