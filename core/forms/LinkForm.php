<?php

namespace app\core\forms;

use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
final class LinkForm extends Model
{
    public string $link = '';

    /**
     * @return array the validation rules.
     */
    public function rules(): array
    {
        return [
            ['link', 'trim'],
            ['link', 'required'],
            ['link', 'url'],
            ['link', 'validateUrlAvailability'],
        ];
    }

    /**
     * @return array customized attribute labels
     */
    public function attributeLabels(): array
    {
        return [
            'link' => 'Ссылка',
        ];
    }

    public function getInputId(string $name): string
    {
        return strtolower($this->formName()) . '-' . $name;
    }

    public function validateUrlAvailability($attribute, $params): void
    {
        $curlInit = curl_init($this->$attribute);

        curl_setopt($curlInit, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($curlInit, CURLOPT_HEADER, true);
        curl_setopt($curlInit, CURLOPT_NOBODY, true);
        curl_setopt($curlInit, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($curlInit);
        curl_close($curlInit);

        if (!$response) {
            $this->addError($attribute, 'Данный URL не доступен');
        }
    }
}
