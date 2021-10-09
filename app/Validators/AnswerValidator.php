<?php

namespace App\Validators;

use \Prettus\Validator\Contracts\ValidatorInterface;
use \Prettus\Validator\LaravelValidator;

/**
 * Class AnswerValidator.
 *
 * @package namespace App\Validators;
 */
class AnswerValidator extends LaravelValidator
{
    CONST RULE_DELETE = 'RULE_DELETE';
    /**
     * Validation Rules
     *
     * @var array
     */
    protected $rules = [
        AnswerValidator::RULE_CREATE => [
            'title' => 'required',
            'filemsgans' => 'required'
        ],
        AnswerValidator::RULE_UPDATE => [
            'title' => 'required',
            'urlAns' => 'required_without_all:fileeditmsgans'
        ],
        AnswerValidator::RULE_DELETE => [
            'id' => 'required|exists:answer,id'
        ],
    ];
}
