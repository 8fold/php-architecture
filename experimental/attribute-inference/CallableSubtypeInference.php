<?php

declare(strict_types=1);

namespace Eightfold\Experimental\AttributeInference;

use Eightfold\Attributes\CallableBaseType;
use Eightfold\Attributes\CallableSubtype;
use Eightfold\Attributes\Returns;

/**
 * Experimental draft; not autoloaded or supported.
 */
final class CallableSubtypeInference
{
    /**
     * Infers the probable CallableSubtype based on base type, return type, and AST flags.
     */
    public static function fromFlags(
        CallableBaseType $baseType,
        Returns $returns = Returns::Other,
        bool $hasNoDiscard = false,
        bool $isPure = false
    ): CallableSubtype {
        // 1. Abstract method signatures are always contract definitions
        if ($baseType === CallableBaseType::AbstractMethod) {
            return CallableSubtype::ContractDefinition;
        }

        // 2. Structural return type dispatch
        return match ($returns) {
            Returns::NeverType   => CallableSubtype::Assertion,
            Returns::VoidType    => CallableSubtype::Command,
            Returns::BooleanType => $hasNoDiscard ? CallableSubtype::Check : CallableSubtype::Predicate,
            Returns::SelfType    => match ($baseType) {
                CallableBaseType::StaticMethod   => CallableSubtype::NamedConstructor,
                CallableBaseType::InstanceMethod => $isPure
                    ? CallableSubtype::StateTransformer // Immutability (returns clone)
                    : CallableSubtype::StateTransformerThis, // Fluent in-place mutation
                default => CallableSubtype::Factory,
            },
            Returns::Other => match (true) {
                $hasNoDiscard => CallableSubtype::CommandWithStatus,
                $isPure       => CallableSubtype::Pure,
                default       => match ($baseType) {
                    CallableBaseType::InstanceMethod,
                    CallableBaseType::MagicMethod => CallableSubtype::Query,
                    default => CallableSubtype::NotFound,
                },
            },
        };
    }
}
