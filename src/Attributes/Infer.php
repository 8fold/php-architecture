<?php

declare(strict_types=1);

namespace Eightfold\Aatributes;

/**
 * Infers appropriate behavioral subtype attributes for unannotated callables.
 */
final readonly class AttributeInferer
{
    /**
     * Infer the best matching subtype attribute for a given AST node.
     */
    public function infer(NodeInspector $inspector): ?InferenceResult
    {
        // TODO: Replace with CallableSubtypes::classes()
        $candidates = [
            Command::class,
            CommandWithStatus::class,
            Query::class,
            StateTransformer::class,
            StateTransformerThis::class,
            Factory::class,
            NamedConstructor::class,
            Handler::class,
            Assertion::class,
            Predicate::class,
            Check::class,
            StrictPure::class,
            Middleware::class,
            Voter::class,
        ];

        $eligible = [];

        foreach ($candidates as $attributeClass) {
            if (self::isDisqualified($attributeClass, $inspector)) {
                continue;
            }

            $score = self::calculateScore($attributeClass, $inspector);
            if ($score > 0) {
                $eligible[$attributeClass] = $score;
            }
        }

        if (count($eligible) === 0) {
            return null;
        }

        arsort($eligible);
        $bestMatch = (string) key($eligible);
        $confidence = $eligible[$bestMatch];

        return new InferenceResult(
            attributeClass: $bestMatch,
            score: $confidence,
            explanation: self::buildExplanation($bestMatch, $inspector)
        );
    }

    /**
     * Check if a subtype is disqualified due to hard MUST_NOT rules.
     */
    private function isDisqualified(
        string $attributeClass,
        NodeInspector $inspector
    ): bool {
        $checks = self::getRuleCheckMap($inspector);

        foreach ($checks as $methodName => $actualCondition) {
            /** @var Rfc2119 $rfc */
            $rfc = $attributeClass::$methodName();

            if ($rfc->isViolation($actualCondition) && $rfc->isSpl()) {
                return true;
            }

            if ($rfc === Rfc2119::MUST_NOT && $actualCondition) {
                return true;
            }
        }

        return false;
    }

    /**
     * Calculate a matching score based on fulfilled RFC 2119 rules.
     */
    private function calculateScore(
        string $attributeClass,
        NodeInspector $inspector
    ): int {
        $score = 0;
        $checks = self::getRuleCheckMap($inspector);

        foreach ($checks as $methodName => $actualCondition) {
            /** @var Rfc2119 $rfc */
            $rfc = $attributeClass::$methodName();

            if ($actualCondition && $rfc->value > 0 && !$rfc->isSpl()) {
                $score += $rfc->value;
            } elseif ($actualCondition && $rfc === Rfc2119::SHOULD_NOT) {
                $score += $rfc->value;
            }
        }

        return $score;
    }

    /**
     * Map AST inspector properties to attribute rule methods.
     *
     * @return array<string, bool>
     */
    private function getRuleCheckMap(NodeInspector $inspector): array
    {
        return [
            'standalone' => $inspector->isStandalone(),
            'beStatic' => $inspector->isStatic(),
            'beFinal' => $inspector->isFinal(),
            'accessToThis' => $inspector->accessesThis(),
            'returnSelfLike' => $inspector->returnsSelfLike(),
            'returnThis' => $inspector->returnsThis(),
            'cloneThis' => $inspector->clonesThis(),
            'hasNoDiscardAttribute' => $inspector->hasNoDiscardAttribute(),
            'hasPureAttribute' => $inspector->hasPureAttribute(),
            'returnGeneratorOrYields' => $inspector->yieldsOrReturnsGenerator(),
            'returnNeverOrThrowsException' => $inspector->throwsOrReturnsNever(),
            'returnBool' => $inspector->returnsBool(),
            'returnVoid' => $inspector->returnsVoid(),
            'returnNull' => $inspector->returnsNull(),
            'returnException' => $inspector->returnsException(),
            'returnEmptyVariant' => $inspector->returnsEmptyVariant(),
            'returnOther' => $inspector->returnsOther(),
            'examineInstanceState' => $inspector->examinesInstanceState(),
            'mutateInstanceState' => $inspector->mutatesInstanceState(),
            'examineExternalState' => $inspector->examinesExternalState(),
            'mutateExternalState' => $inspector->mutatesExternalState(),
            'useInterface' => $inspector->usesInterface(),
            'usePropertyHook' => $inspector->usesPropertyHook(),
        ];
    }

    /**
     * Build human-readable explanation for the recommendation.
     */
    private function buildExplanation(
        string $attributeClass,
        NodeInspector $inspector
    ): string {
        $parts = explode('\\', $attributeClass);
        $shortName = end($parts);

        return sprintf(
            'Method matches #[%s] specification based on AST traits.',
            $shortName
        );
    }
}
