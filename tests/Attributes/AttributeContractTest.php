<?php

declare(strict_types=1);

namespace Eightfold\Tests\Attributes;

use Attribute;
use Composer\Autoload\ClassLoader;
use Eightfold\Attributes\CallableSubtype;
use Eightfold\Attributes\Callables\NamedConstructor;
use Eightfold\Attributes\Callables\Predicate;
use Eightfold\Attributes\Callables\StateTransformerThis as CallableStateTransformerThis;
use Eightfold\Attributes\Classes\BaseClass;
use Eightfold\Attributes\Classes\Subclass;
use Eightfold\Attributes\Functions\Accessor;
use Eightfold\Attributes\Functions\Command;
use Eightfold\Attributes\Functions\CommandWithStatus;
use Eightfold\Attributes\Functions\Factory;
use Eightfold\Attributes\Functions\Pure;
use Eightfold\Attributes\Functions\StateTransformer;
use Eightfold\Attributes\Functions\StateTransformerThis as FunctionStateTransformerThis;
use Eightfold\Tests\PHPStan\Fixtures\{
    ClassesShouldBeFinal\Compliant as ClassesShouldBeFinalCompliant,
    ClassesShouldNotBeSubclass\Compliant as ClassesShouldNotBeSubclassCompliant,
};
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

#[Subclass(rationale: 'PHPUnit test classes must extend TestCase.')]
final class AttributeContractTest extends TestCase
{
    /**
     * @return array<string, array{class-string}>
     */
    public static function methodAttributes(): array
    {
        return [
            'accessor' => [Accessor::class],
            'command' => [Command::class],
            'command with status' => [CommandWithStatus::class],
            'factory' => [Factory::class],
            'pure' => [Pure::class],
            'state transformer' => [StateTransformer::class],
            'function state transformer this' => [FunctionStateTransformerThis::class],
            'named constructor' => [NamedConstructor::class],
            'predicate' => [Predicate::class],
            'callable state transformer this' => [CallableStateTransformerThis::class],
        ];
    }

    /**
     * @param class-string $attributeClass
     */
    #[Test]
    #[Group('attributes')]
    #[DataProvider('methodAttributes')]
    public function method_attribute_autoloads_and_exposes_metadata(string $attributeClass): void
    {
        parent::assertTrue(class_exists($attributeClass));
        $reflection = new ReflectionClass($attributeClass);
        $declarations = $reflection->getAttributes(Attribute::class);

        parent::assertCount(1, $declarations);
        parent::assertSame(Attribute::TARGET_METHOD, $declarations[0]->newInstance()->flags);
        parent::assertInstanceOf($attributeClass, $reflection->newInstance());

        $methods = ['behavior', 'definingInvariants', 'recommendedAlternatives', 'example'];
        foreach ($methods as $method) {
            parent::assertIsString($reflection->getMethod($method)->invoke(null));
        }
    }

    /**
     * @return array<string, array{class-string, class-string<BaseClass|Subclass>, string}>
     */
    public static function classAttributes(): array
    {
        return [
            'base class' => [
                ClassesShouldBeFinalCompliant::class,
                BaseClass::class,
                'This fixture permits extension.',
            ],
            'subclass' => [
                ClassesShouldNotBeSubclassCompliant::class,
                Subclass::class,
                'This fixture requires a subclass.',
            ],
        ];
    }

    /**
     * @param class-string $fixture
     * @param class-string<BaseClass|Subclass> $attributeClass
     */
    #[Test]
    #[Group('attributes')]
    #[DataProvider('classAttributes')]
    public function fixture_attribute_can_be_instantiated(
        string $fixture,
        string $attributeClass,
        string $rationale
    ): void {
        $attributes = (new ReflectionClass($fixture))->getAttributes($attributeClass);

        parent::assertCount(1, $attributes);
        $attribute = $attributes[0]->newInstance();
        parent::assertInstanceOf($attributeClass, $attribute);
        parent::assertSame($rationale, $attribute->rationale);

        $declarations = (new ReflectionClass($attributeClass))->getAttributes(Attribute::class);
        parent::assertCount(1, $declarations);
        parent::assertSame(Attribute::TARGET_CLASS, $declarations[0]->newInstance()->flags);
    }

    /**
     * @param class-string $fixture
     * @param class-string<BaseClass|Subclass> $attributeClass
     */
    #[Test]
    #[Group('attributes')]
    #[DataProvider('classAttributes')]
    public function class_attribute_rejects_a_blank_rationale(
        string $fixture,
        string $attributeClass,
        string $rationale
    ): void {
        parent::expectException(InvalidArgumentException::class);

        new $attributeClass(" \t\n");
    }

    #[Test]
    #[Group('attributes')]
    public function examples_preserve_literal_php_variables_and_in_place_semantics(): void
    {
        parent::assertStringContainsString('$this->balance', Accessor::example());
        parent::assertStringContainsString('$record', Command::example());
        parent::assertStringContainsString('$record', CommandWithStatus::example());
        parent::assertStringContainsString('$cents', Factory::example());
        parent::assertStringContainsString(
            '#[\\' . NamedConstructor::class . ']',
            Factory::example()
        );
        parent::assertStringContainsString(
            '#[\\' . NamedConstructor::class . ']',
            NamedConstructor::example()
        );
        parent::assertStringContainsString('$rawCode', Pure::example());
        parent::assertStringContainsString('clone($this', StateTransformer::example());
        parent::assertStringContainsString(
            '#[\\' . StateTransformer::class . ']',
            StateTransformer::example()
        );
        parent::assertStringContainsString(
            'return $this;',
            FunctionStateTransformerThis::example()
        );
        parent::assertStringContainsString(
            'return $this;',
            CallableStateTransformerThis::example()
        );
        parent::assertStringContainsString(
            '#[\\' . FunctionStateTransformerThis::class . ']',
            FunctionStateTransformerThis::example()
        );
        parent::assertStringContainsString(
            '#[\\' . CallableStateTransformerThis::class . ']',
            CallableStateTransformerThis::example()
        );
        parent::assertStringContainsString(
            'not a clone',
            CallableStateTransformerThis::definingInvariants()
        );
    }

    #[Test]
    #[Group('attributes')]
    public function callable_subtype_lookup_recognizes_case_names_values_and_qualified_names(): void
    {
        foreach (CallableSubtype::cases() as $case) {
            if ($case === CallableSubtype::NotFound) {
                continue;
            }

            parent::assertSame($case, CallableSubtype::fromAttributeName($case->name));
            parent::assertSame($case, CallableSubtype::fromAttributeName($case->value));
            parent::assertTrue(CallableSubtype::isValidAttribute($case->name));
        }

        parent::assertSame(
            CallableSubtype::Predicate,
            CallableSubtype::fromAttributeName(Predicate::class)
        );
        parent::assertSame(
            CallableSubtype::Command,
            CallableSubtype::fromAttributeName(Command::class)
        );
        parent::assertSame(
            CallableSubtype::Query,
            CallableSubtype::fromAttributeName(Accessor::class)
        );
        parent::assertSame(
            CallableSubtype::StrictPure,
            CallableSubtype::fromAttributeName(Pure::class)
        );
        parent::assertTrue(CallableSubtype::isValidAttribute(Accessor::class));
        parent::assertTrue(CallableSubtype::isValidAttribute(Pure::class));
        parent::assertSame(
            CallableSubtype::NotFound,
            CallableSubtype::fromAttributeName('Unknown')
        );
        parent::assertFalse(CallableSubtype::isValidAttribute('Unknown'));
    }

    #[Test]
    #[Group('attributes')]
    public function unfinished_inference_is_not_exposed_as_a_supported_api(): void
    {
        parent::assertFalse((new ReflectionClass(CallableSubtype::class))->hasMethod('fromFlags'));
        parent::assertFalse(class_exists('Eightfold\\Attributes\\AttributeInferer'));
        parent::assertFalse(class_exists('Eightfold\\PHPStan\\NodeInspector'));
        parent::assertFalse(class_exists(
            'Eightfold\\Experimental\\AttributeInference\\CallableSubtypeInference'
        ));
        $loader = require dirname(__DIR__, 2) . '/vendor/autoload.php';
        parent::assertInstanceOf(ClassLoader::class, $loader);
        $drafts = [
            'AttributeInferer',
            'NodeInspector',
            'CallableEvaluators',
            'CallableSubtypeInference',
        ];
        foreach ($drafts as $name) {
            parent::assertFalse($loader->findFile(
                'Eightfold\\Experimental\\Analysis\\CallablesMustBeTyped\\' . $name
            ));
        }
    }
}
