<?php

namespace app\core\forms;

use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
final class LinkLogForm extends Model
{
    public string $linkId = '';
    public string $ipAddress = '';

    /**
     * @return array the validation rules.
     */
    public function rules(): array
    {
        return [
            [['linkId', 'ipAddress'], 'required'],
            ['linkId', 'integer'],
            ['ipAddress', 'string'],
        ];
    }

    public function formName(): string
    {
        return '';
    }
}
