<?php

declare(strict_types=1);

namespace App\Infrastructure\Traits\Enum;

use Arr;
use App\Infrastructure\Interfaces\Enum\EnumInterface;
use ReflectionClass;
use ReflectionClassConstant;
use ReflectionEnum;

trait EnumAttributeTrait
{
    public function getAttribute(string $attributeClassName): ?object
    {
        $reflection = new ReflectionClassConstant(class: self::class, constant: $this->name);

        $attributes = $reflection->getAttributes(name: $attributeClassName);

        if (!count($attributes)) {
            return null;
        }

        return $attributes[0]->newInstance();
    }

    public function getAttributes(array $attributeClassNames): ?array
    {
        $attributes = [];

        foreach ($attributeClassNames as $attributeClassName) {
            $attribute = $this->getAttribute($attributeClassName);

            if ($attribute === null) {
                $attribute = str($this->name)->headline()->lower()->ucfirst();
            }

            $attributes[str(class_basename($attributeClassName))->before(
                'Attribute'
            )->camel()->lower()->toString()] = $attribute->name;
        }

        return $attributes;
    }

    public function getArrayOfAttributes(): array
    {
        return [];
    }

    public static function fromUniqueAttribute(string $attributeClassName, string|int $value): ?EnumInterface
    {
        $reflectionEnum = new ReflectionEnum(self::class);
        $reflectionAttribute = new ReflectionClass($attributeClassName);

        $attributeKey = Arr::get($reflectionAttribute->getConstructor()->getParameters(), 0)->getName();

        foreach ($reflectionEnum->getCases() as $case) {
            $attribute = Arr::get($case->getAttributes(name: $attributeClassName), 0);

            if ($attribute->newInstance()->{$attributeKey} === $value) {
                /** @var EnumInterface $value */
                $value = $case->getValue();

                return $value;
            }
        }

        return null;
    }
}
