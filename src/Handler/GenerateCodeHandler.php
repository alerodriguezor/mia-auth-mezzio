<?php

namespace Mia\Auth\Handler;

use Mia\Auth\Model\MiaUserCode;

/**
 * Description of MiaRecoveryHanlder
 * 
 * @OA\Post(
 *     path="/mia-auth/generate-code",
 *     summary="Generate Code",
 *     tags={"Authentication"},
 *     @OA\RequestBody(
 *         description="Info of User",
 *         required=true,
 *         @OA\MediaType(
 *             mediaType="application/json",                 
 *             @OA\Schema(
 *                  @OA\Property(
 *                      property="email",
 *                      type="string",
 *                      description="Email of user",
 *                      example="matias@agencycoda.com"
 *                  )
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *          response=200,
 *          description="successful operation",
 *          @OA\JsonContent(ref="#/components/schemas/MIAUser")
 *     )
 * )
 *
 * @author matiascamiletti
 */
class GenerateCodeHandler extends \Mia\Core\Request\MiaRequestHandler
{
    public function handle(\Psr\Http\Message\ServerRequestInterface $request): \Psr\Http\Message\ResponseInterface
    {
        // Obtener parametros obligatorios
        $email = $this->getParam($request, 'email', '');
        // Verificar si ya existe la cuenta
        $account = \Mia\Auth\Model\MIAUser::where('email', $email)->first();
        if($account === null){
            return new \Mia\Core\Diactoros\MiaJsonErrorResponse(-1, 'This account not exist');
        }
        if($account->deleted == 1){
            return new \Mia\Core\Diactoros\MiaJsonErrorResponse(-1, 'This account not exist.');
        }
        // Generate new Code
        $code = new MiaUserCode();
        $code->user_id = $account->id;
        $code->code = random_int(0, 999999);
        $code->status = MiaUserCode::STATUS_PENDING;
        $code->save();

        /* @var $sendgrid \Mia\Mail\Service\Sendgrid */
        $sendgrid = $request->getAttribute('Sendgrid');
        $result = $sendgrid->send($account->email, 'send-code', [
            'firstname' => $account->firstname,
            'email' => $account->email,
            'code' => $code->code
        ]);

        if($result === false){
            return new \Mia\Core\Diactoros\MiaJsonResponse(false);
        }

        // Devolvemos datos del usuario
        return new \Mia\Core\Diactoros\MiaJsonResponse(true);
    }
}
