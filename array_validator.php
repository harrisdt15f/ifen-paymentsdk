<?php
/**
 * Created by PhpStorm.
 * author: harris
 * Date: 17-8-17
 * Time: 下午2:17
 */
trait ArrayValidator
{

    /** @var array */
    private $arr_valid_error = [];

    /** @var array */
    private $cleanFields = [];

    /** @var array */
    private $_definedRules = [
        'min' => 'min_field_validate',
        'max' => 'max_field_validate',
        'required' => 'required_field_validate',
    ];

    /** @var array */
    private $arr_validate_fields = [];

    //#########[MIN]####################
    private $para_min = 0;

    /** @var int */
    private $arr_validate_min = 0;
    //#########[MAX]####################
    private $para_max = 0;

    /** @var int */
    private $arr_validate_max;
    //#############################
    /**
     * InputValidator constructor.
     * @param array $input
     */

    /**
     * @param $input
     * @param array $fieldRules
     * @param array $fields
     * @return bool
     */
    public function validate(array $input, array $fieldRules, array $fields = [])
    {
        $this->arr_validate_fields = $input;
        $this->arr_validate_fields = empty($fields) ? $this->arr_validate_fields : $fields;

        foreach ($fieldRules as $fieldName => $rules) {
            $this->validateField($fieldName, explode('|', $rules));
        }

        return empty($this->arr_valid_error);
    }

    /**
     *
     * @param string $fieldName
     * @param array $rules
     */
    public function validateField($fieldName, array $rules)
    {
        foreach ($rules as $rule) {
            $parserule = $this->parseRule($rule, $prefunc);
            list($ruleMethod, $parameters) = $parserule;
            $this->{$prefunc . "_setParameters"}($parameters);
            $field = empty($this->arr_validate_fields[$fieldName]) ? null : $this->arr_validate_fields[$fieldName];

            if (false === $this->{$ruleMethod}($field)) {
                $this->arr_valid_error = [
                    'error_msg' => $fieldName . $this->get_arr_error_msg($ruleMethod)
                ];
            } else if ($field !== null) {
                $this->cleanFields[$fieldName] = $field;
            }
        }
    }

    /**
     *
     * @param string $rule
     * @param $prefunc
     * @return array
     */
    private function parseRule($rule, &$prefunc)
    {
        $data = explode(':', $rule);
        if (empty($data[0])) {
            throw new RuntimeException('Missing rule');
        } else if (empty($this->_definedRules[$data[0]])) {
            throw new RuntimeException("Rule {$data[0]} is not defined");
        }
        $prefunc = $data[0];
        $rule_class = $this->_definedRules[$data[0]]; //RuleRequired
        $parameter = empty($data[1]) ? [] : explode(',', $data[1]);
        return [$rule_class, $parameter];
    }

    /**
     *
     * @return array
     */
    public function get_arr_Errors()
    {
        return $this->arr_valid_error;
    }

    /**
     * @param $value
     * @return bool
     */
    public function required_field_validate($value)
    {
        return false === empty($value);
    }

    /**
     * @param array $parameters
     * @return null
     */
    public function required_setParameters(array $parameters)
    {
        unset($parameters);
    }

    public function min_field_validate($value)
    {
        return strlen($value) >= $this->arr_validate_min;
    }

    /**
     * @param array $parameters
     * @return null
     */
    public function min_setParameters(array $parameters)
    {
        if (isset($parameters[$this->para_min])) {
            $this->arr_validate_min = $parameters[$this->para_min];
        }
    }

    /**
     * @param mixed $value
     * @return bool
     */
    public function max_field_validate($value)
    {
        return strlen($value) <= $this->arr_validate_max;
    }

    /**
     * @param array $parameters
     * @return null
     */
    public function max_setParameters(array $parameters)
    {
        $this->arr_validate_max = PHP_INT_MAX;

        if (isset($parameters[$this->para_max])) {
            $this->arr_validate_max = $parameters[$this->para_max];
        }
    }

    /**
     * @param $message
     * @return string
     */
    public function get_arr_error_msg($message)
    {
        $error = [
            'require_field_validate' => ' 字段没有接收到',
            'min_field_validate' => '字段必须是 ' . $this->arr_validate_min . ' 位',
            'max_field_validate' => '字段有包括 ' . $this->arr_validate_max . ' 位数以上',
        ];
        return $error[$message];
    }


}