<?php

class FormValidator
{
    private $fieldValidations;
    private $data;

    public function __construct(array $fieldValidations, array $data)
    {
        $this->fieldValidations = $fieldValidations;
        $this->data = $data;
    }

    public function validate()
    {
        foreach ($this->fieldValidations as $field => $props) {
            $title = $props['title'] ?? ucfirst($field);
            if ($props['required'] && !isset($this->data[$field])) {
                return $title . " is required";
            }
            if (isset($props['type'])) {
                if ($props['type'] === 'email' && !filter_var($this->data[$field], FILTER_VALIDATE_EMAIL)) {
                    return $title . " is not a valid email";
                }
                if ($props['type'] === 'number' && !is_numeric($this->data[$field])) {
                    return $title . " is not a valid number";
                }
            }
            if (isset($props['min']) && strlen($this->data[$field]) < $props['min']) {
                return $title . " must have at least " . $props['min'] . " characters";
            }
        }
        return null;
    }

    private function parse_input(string $data): string
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    public function parseFeilds()
    {
        $parsedData = [];
        foreach ($this->data as $key => $value) {
            $parsedData[$key] = $this->parse_input($value);
        }
        return $parsedData;
    }
}
