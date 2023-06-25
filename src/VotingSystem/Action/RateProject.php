<?php

namespace App\VotingSystem\Action;

use App\Core\REST\Action\AbstractApiAction;
use App\Core\REST\ValueObject\ApiResponse;
use App\Domain\VotingSystem\Representation\ProjectRepresentation;
use App\VotingSystem\Message\RateProjectMessage;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class RateProject
 */
class RateProject extends AbstractApiAction
{
  /**
   * @param Request $request
   * @return ApiResponse
   */
  #[Route(
    '/project/{project_id}',
    name: 'vs_api_v1_update_project',
    methods: ['PATCH']
  )]
  public function __invoke(Request $request): ApiResponse
  {
    /** @var ProjectRepresentation $representation */
    $representation = $this->handle(
      RateProjectMessage::fromRequest($request)
    );

    return ApiResponse::success($representation);
  }
}
