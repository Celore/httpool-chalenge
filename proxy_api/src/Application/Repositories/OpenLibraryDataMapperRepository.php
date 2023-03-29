<?php

namespace App\Application\Repositories;

use App\Application\Domain\OpenLibraryValueObjectInterface;
use App\Application\Dto\Repositories\DataMapperFieldDto;
use ReflectionUnionType;

class OpenLibraryDataMapperRepository
{
    protected static array $cache = [];

    /**
     * @throws \ReflectionException
     */
    public function fillObject(
        OpenLibraryValueObjectInterface $object, array $openLibraryData
    ): OpenLibraryValueObjectInterface
    {
        foreach ($this->prepareMap($object) as $property => $jsonField) {
            try {
                $object->{$property} = $this->findValue($openLibraryData, $jsonField);
            } catch (\Exception $e) {
            }
        }

        return $object;
    }

    /**
     * @param $object
     * @return array<string, DataMapperFieldDto>
     * @throws \ReflectionException
     */
    private function prepareMap($object): array
    {
        $reflectionClass = new \ReflectionClass($object);
        $className = $reflectionClass->getName();

        if (!isset(self::$cache[$className])) {
            $properties = $reflectionClass->getProperties();
            $dataMapping = [];
            foreach ($properties as $property) {
                if (!$property->isPublic()) {
                    continue;
                }

                if ($dto = $this->getFieldDto($property)) {
                    $dataMapping[$property->getName()] = $dto;
                }
            }

            self::$cache[$className] = $dataMapping;
        }

        return self::$cache[$className];
    }

    /**
     * @throws \ReflectionException
     * @throws \Exception
     */
    private function findValue(array $openLibraryData, DataMapperFieldDto $field)
    {
        $iterator = new \RecursiveArrayIterator($openLibraryData);
        $recursive = new \RecursiveIteratorIterator(
            $iterator,
            \RecursiveIteratorIterator::SELF_FIRST
        );

        foreach ($recursive as $key => $openLibraryValue) {
            if ($key === $field->name) {
                if ($field->class) {
                    if ($field->isArray) {
                        $valuesArray = [];
                        foreach ($openLibraryValue as $value) {
                            $valuesArray[] = $this->createAndFillObject($field->class, $value);
                        }
                        return $valuesArray;
                    } else {
                        return $this->createAndFillObject($field->class, $openLibraryValue);
                    }

                }
                return $openLibraryValue;
            }
        }

        throw new \Exception('Value is not found');
    }

    /**
     * @throws \ReflectionException
     */
    private function createAndFillObject(string $class, $value) {
        $object = new $class();
        $this->fillObject($object, $value);
        return $object;
    }

    private function isPropertyArray(\ReflectionProperty $property): bool
    {
        $type = $property->getType();
        if ($type instanceof ReflectionUnionType) {
            return in_array(
                'array',
                array_map(
                    fn($typeFromUnion) => $typeFromUnion->getName(),
                    $type->getTypes())
            );
        }

        return $type->getName() == 'array';
    }

    private function getFieldDto(\ReflectionProperty $property): ?DataMapperFieldDto
    {
        $docBlock = $property->getDocComment();
        $matches = [];
        if (
            preg_match(
                '/(@open-library-json-field-name (?P<json_field_name>([0-9a-zA-Z_-]+)))+([\S\n\t\v ]*@open-library-json-field-class (?P<json_field_class>([\\\\a-zA-Z_-]+)))?/m',
                $docBlock,
                $matches)
        ) {

            return new DataMapperFieldDto(
                $matches['json_field_name'],
                (
                    isset($matches['json_field_class'])
                    && is_subclass_of(
                        $matches['json_field_class'], OpenLibraryValueObjectInterface::class
                    )
                )
                    ? $matches['json_field_class']
                    : null,
                $this->isPropertyArray($property)
            );
        }

        return null;
    }
}