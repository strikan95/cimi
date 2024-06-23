<?php

namespace App\Shared\Security;

use App\Core\User\Entity\Embeddable\UserIdentity;
use App\Core\User\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Lcobucci\JWT\Encoding\JoseEncoder;
use Lcobucci\JWT\Token;
use Lcobucci\JWT\Token\Parser;
use Lcobucci\JWT\UnencryptedToken;
use Lcobucci\JWT\Validation\Constraint\IssuedBy;
use Lcobucci\JWT\Validation\Constraint\PermittedFor;
use Lcobucci\JWT\Validation\Validator;
use Symfony\Component\Security\Http\AccessToken\AccessTokenHandlerInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use GetStream\StreamChat\Client as GetStreamClient;
use GetStream\StreamChat\StreamResponse;

class AccessTokenHandler implements AccessTokenHandlerInterface
{
    private Parser $tokenParser;

    private Validator $tokenValidator;

    private GetStreamClient $getStreamClient;

    public function __construct(private EntityManagerInterface $em)
    {
        $this->tokenParser = new Parser(new JoseEncoder());
        $this->tokenValidator = new Validator();

        $this->getStreamClient = new GetStreamClient(
            $_SERVER['GET_STREAM_KEY'],
            $_SERVER['GET_STREAM_SECRET'],
        );
    }

    /**
     * @throws \Exception
     */
    public function getUserBadgeFrom(
        #[\SensitiveParameter] string $accessToken,
    ): UserBadge {
        $token = $this->parse($accessToken);
        if (!$this->validate($token)) {
            throw new \Exception('Bad token.', 400);
        }

        $claims = $token->claims();
        $sub = $claims->get('sub');
        $email = $claims->get('email');

        if (!($sub || $email)) {
            throw new \Exception('Token claim missing.', 400);
        }

        $user = $this->em
            ->getRepository(User::class)
            ->findOneBy(['userIdentity.sub' => $sub]);

        if (!$user) {
            $userIdentity = UserIdentity::build($sub, $email);
            $user = User::register($userIdentity);

            $this->em->persist($user);
            $this->em->flush();

            $this->getStreamClient->upsertUser([
                'id' => str_replace(
                    '|',
                    '_',
                    $user->getUserIdentity()->getSub(),
                ),
                'role' => 'user',
                'name' => 'Djuro' . ' ' . 'Peric',
            ]);
        }

        return new UserBadge($user->getUserIdentifier());
    }

    /**
     * @throws \Exception
     */
    private function parse(string $accessToken): UnencryptedToken
    {
        $token = $this->tokenParser->parse($accessToken);
        assert($token instanceof UnencryptedToken);

        return $token;
    }

    private function validate(Token $token): bool
    {
        return $this->tokenValidator->validate(
            $token,
            new IssuedBy('https://cimi-api.eu.auth0.com/'),
            new PermittedFor('https://cimi.core.api'),
            //new LooseValidAt()
        );
    }
}
