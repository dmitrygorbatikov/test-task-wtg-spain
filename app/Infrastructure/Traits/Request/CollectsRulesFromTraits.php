<?php

declare(strict_types=1);

namespace App\Infrastructure\Traits\Request;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

/**
 * @mixin FormRequest
 */
trait CollectsRulesFromTraits
{
    use HasJsonParameter;
    use ReplaceUserTagAndHashtagTrait;

    public string $extensionMethodsNameSuffix = 'ExtensionRules';

    public function collectRules(): array
    {
        $methods = collect(get_class_methods($this))->filter(
            fn (string $method) => Str::endsWith($method, $this->extensionMethodsNameSuffix)
        );

        $rules = [];
        foreach ($methods as $method) {
            $rules = [...$rules, ...$this->{$method}()];
        }

        return $rules;
    }

    public function attributes(): array
    {
        return [
            ...parent::attributes(),
            'pagination.0' => 'page',
            'pagination.1' => 'perPage',
            'sort.0' => 'column',
            'sort.1' => 'order',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->collectsRulesPrepareForValidation();
    }

    protected function collectsRulesPrepareForValidation(): void
    {
        if ($json = $this->get('pagination')) {
            $this->merge([
                'pagination' => $this->tryParseJsonData($json),
            ]);
        }
        if ($json = $this->get('filter')) {
            $this->merge([
                'filter' => $this->tryParseJsonData($json),
            ]);
        }
        if ($json = $this->get('sort')) {
            $this->merge([
                'sort' => $this->tryParseJsonData($json),
            ]);
        }
    }
}
