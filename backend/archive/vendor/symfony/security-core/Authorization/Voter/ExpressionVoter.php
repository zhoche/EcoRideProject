<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\Security\Core\Authorization\Voter;

use Symfony\Component\ExpressionLanguage\Expression;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\AuthenticationTrustResolverInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Authorization\ExpressionLanguage;
use Symfony\Component\Security\Core\Role\RoleHierarchyInterface;

/**
 * ExpressionVoter votes based on the evaluation of an expression.
 *
 * @author Fabien Potencier <fabien@symfony.com>
 */
class ExpressionVoter implements CacheableVoterInterface
{
    public function __construct(
        private ExpressionLanguage $expressionLanguage,
        private ?AuthenticationTrustResolverInterface $trustResolver,
        private AuthorizationCheckerInterface $authChecker,
        private ?RoleHierarchyInterface $roleHierarchy = null,
    ) {
    }

    public function supportsAttribute(string $attribute): bool
    {
        return false;
    }

    public function supportsType(string $subjectType): bool
    {
        return true;
    }

    /**
     * @param Vote|null $vote Should be used to explain the vote
     */
    public function vote(TokenInterface $token, mixed $subject, array $attributes/* , ?Vote $vote = null */): int
    {
        $vote = 3 < \func_num_args() ? func_get_arg(3) : null;
        $result = VoterInterface::ACCESS_ABSTAIN;
        $variables = null;
        $failingExpressions = [];
        foreach ($attributes as $attribute) {
            if (!$attribute instanceof Expression) {
                continue;
            }

            $variables ??= $this->getVariables($token, $subject);

            $result = VoterInterface::ACCESS_DENIED;

            if ($this->expressionLanguage->evaluate($attribute, $variables)) {
                $vote?->addReason(\sprintf('Expression (%s) is true.', $attribute));

                return VoterInterface::ACCESS_GRANTED;
            }

            $failingExpressions[] = $attribute;
        }

        if ($failingExpressions) {
            $vote?->addReason(\sprintf('Expression (%s) is false.', implode(') || (', $failingExpressions)));
        }

        return $result;
    }

    private function getVariables(TokenInterface $token, mixed $subject): array
    {
        $roleNames = $token->getRoleNames();

        if (null !== $this->roleHierarchy) {
            $roleNames = $this->roleHierarchy->getReachableRoleNames($roleNames);
        }

        $variables = [
            'token' => $token,
            'user' => $token->getUser(),
            'object' => $subject,
            'subject' => $subject,
            'role_names' => $roleNames,
            'auth_checker' => $this->authChecker,
        ];

        if ($this->trustResolver) {
            $variables['trust_resolver'] = $this->trustResolver;
        }

        // this is mainly to propose a better experience when the expression is used
        // in an access control rule, as the developer does not know that it's going
        // to be handled by this voter
        if ($subject instanceof Request) {
            $variables['request'] = $subject;
        }

        return $variables;
    }
}
