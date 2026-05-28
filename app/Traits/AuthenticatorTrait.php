<?php

namespace App\Traits;

use Illuminate\Http\Request;
use Dingo\Api\Http\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\PropertyAccess\Exception\AccessException;
use Symfony\Component\PropertyAccess\PropertyAccess;
use stdClass;
use Symfony\Component\PropertyAccess\PropertyAccessor;

trait AuthenticatorTrait
{
    private ?stdClass $payload;
    private array $credentials;
    private PropertyAccessor $propertyAccessor;

    /**
     * Get a JWT via given credentials.
     *
     * @param  Request  $request
     * @return Response
     */
    public function authenticate(Request $request)
    {   
        $this
            ->checkRequirement($request);

        if (!$token = auth()->attempt(['email' => $this->credentials['username'], 'password' => $this->credentials['password']])) {
            throw new UnauthorizedHttpException('Bearer', 'Unauthorized login');

        }

        return $this->respondWithToken($token);
    }

   
    private function checkRequirement(Request $request): self
    {
        $this
            ->checkPayload($request);

        return $this;
    }

    private function checkPayload(Request $request): self
    {
        try {
            $this->payload = json_decode($request->getContent());
            if (!$this->payload instanceof stdClass) {
                throw new BadRequestHttpException('Invalid JSON.');
            }

            $this->credentials = $this->getCredentials($this->payload);
        } catch (BadRequestHttpException $e) {
            $request->setRequestFormat('json');

            throw $e;
        }

        return $this;
    }   

    private function getCredentials(stdClass $data): array
    {
        $credentials = [];
        try {
            $this->propertyAccessor = PropertyAccess::createPropertyAccessor();
            $usernamePath = config('api_authenticator.username_path');
            $passwordPath = config('api_authenticator.password_path');


            $usernameValue =  $this->propertyAccessor->getValue($data, $usernamePath);
            $credentials['username']  = strtolower($usernameValue);

            if (!\is_string($credentials['username'])) {
                throw new BadRequestHttpException(
                    sprintf('The key "%s" must be a string.', $usernamePath)
                );
            }
        } catch (AccessException $e) {
            throw new BadRequestHttpException(
                sprintf('The key "%s" must be provided.', $usernamePath),
                $e
            );
        }

        try {
            $credentials['password'] = $this->propertyAccessor->getValue($data, $passwordPath);
            $this->propertyAccessor->setValue($data, $passwordPath, null);

            if (!\is_string($credentials['password'])) {
                throw new BadRequestHttpException(
                    sprintf('The key "%s" must be a string.', $passwordPath)
                );
            }
        } catch (AccessException $e) {
            throw new BadRequestHttpException(
                sprintf('The key "%s" must be provided.', $passwordPath),
                $e
            );
        }

        if ('' === $credentials['username'] || '' === $credentials['password']) {
            trigger_deprecation('symfony/security', '6.2', 'Passing an empty string as username or password parameter is deprecated.');
        }

        return $credentials;
    }

    //TODO: Adjust response
    protected function respondWithToken($token)
    {
        $tokenReponse = new \stdClass;

        $tokenReponse->jwt = $token;
        $tokenReponse->token_type = 'bearer';
        $tokenReponse->expires_in = auth()->factory()->getTTL();

        return $this->response->item($tokenReponse, $this->getTransformer())->setStatusCode(200);
    }
}
