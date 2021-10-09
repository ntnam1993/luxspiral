<?php

namespace App\Validators;

use \Prettus\Validator\Contracts\ValidatorInterface;
use \Prettus\Validator\LaravelValidator;

/**
 * Class DeliveryValidator.
 *
 * @package namespace App\Validators;
 */
class DeliveryValidator extends LaravelValidator
{
    CONST RULE_DELETE = 'RULE_DELETE';
    CONST RULE_EDIT   = 'RULE_EDIT';
    /**
     * Validation Rules
     *
     * @var array
     */
    protected $rules = [
        DeliveryValidator::RULE_CREATE => [
            'title' => 'required',
            'filemsgsend' => 'required',
            'filemsgnoans' => 'required',
            'schedule' => 'required',
        ],
        DeliveryValidator::RULE_UPDATE => [
            'title' => 'required',
            'filemsgsend' => '',
            'filemsgnoans' => '',
            'schedule' => 'required',
        ],
        DeliveryValidator::RULE_DELETE => [
            'id' => 'required|exists:delivery,id'
        ],
        DeliveryValidator::RULE_EDIT => [
            'id' => 'required|exists:delivery,id'
        ],
    ];
}
