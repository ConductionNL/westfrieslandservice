<?php

namespace App\Service;

use App\Entity\RequestConversion;
use Conduction\CommonGroundBundle\Service\CommonGroundService;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ConversionService
{
    private $commonGroundService;
    private $params;

    public function __construct(CommonGroundService $commonGroundService, ParameterBagInterface $params)
    {
        $this->commonGroundService = $commonGroundService;
        $this->params = $params;
    }

    public function convert(RequestConversion $request)
    {
        $requestData = $this->commonGroundService->getResource($request->getRequest());

        if (key_exists('gemeente', $requestData['properties'])) {
            $requestData['organization'] = $requestData['properties']['gemeente'];
        }

        try {
            unset($requestData['submitters']);
            unset($requestData['roles']);
            unset($requestData['labels']);
            $this->commonGroundService->updateResource($requestData, ['component' => 'vrc', 'type' => 'requests', 'id' => $requestData['id']]);

            $request->setStatus('OK');
            $request->setMessage('Verzoek omgezet naar de juiste gemeente');

            $token = [];
            $token['name'] = 'Gemeente';
            $token['description'] = 'Verzoek omzetten naar de juiste gemeente';
            $token['reference'] = $this->params->get('app_name');
            $token['status'] = $request->getStatus();
            $token['message'] = $request->getMessage();
            $token['resource'] = $requestData['@id'];
        } catch (HttpException $exception) {
            $request->setMessage($exception->getMessage());
            $request->setStatus('FAILED');
            $token = [];
            $token['code'] = $this->params->get('app_name');
            $token['status'] = $request->getStatus();
            $token['message'] = $request->getMessage();
        }

        $token = $this->commonGroundService->createResource($token, ['component' => 'trc', 'type' => 'tokens']);

        $request->setResult($token['@id']);

        return $request;
    }
}
