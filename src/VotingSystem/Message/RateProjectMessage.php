<?php

namespace App\VotingSystem\Message;

use Symfony\Component\HttpFoundation\Request;
use Assert\Assert;

/**
 * Class RateProjectMessage
 */
class RateProjectMessage
{
  /**
   * @param int $projectId
   * @param int $rating
   * @param string $review
   * @param int|null $communicationRating
   * @param int|null $qualityOfWorkRating
   * @param int|null $valueForMoneyRating
   */
  public function __construct(
    private readonly int      $projectId,
    private readonly int      $rating,
    private readonly string   $review,
    private readonly ?int     $communicationRating,
    private readonly ?int     $qualityOfWorkRating,
    private readonly ?int     $valueForMoneyRating
  )
  {
  }

  /**
   * @param Request $request
   * @return RateProjectMessage
   */
  public static function fromRequest(Request $request): self
  {
    /** @var integer $projectId */
    $projectId = $request->attributes->get('project_id');

    $body = $request->request;

    /** @var string $rating */
    $rating = $body->get('rating');

    /** @var string $address */
    $review = $body->get('review');

    /** @var string $communicationRating */
    $communicationRating = $body->get('communication_rating');

    /** @var string $qualityOfWorkRating */
    $qualityOfWorkRating = $body->get('quality_of_work_rating');

    /** @var string $valueForMoneyRating */
    $valueForMoneyRating = $body->get('value_for_money_rating');

    $assert = Assert::lazy();

    $assert->that($projectId, 'project_id')
        ->notBlank()
        ->numeric()
      ->that($rating, 'rating')
        ->notBlank()
        ->numeric()
        ->between(1, 5)
      ->that($review, 'review')
        ->notBlank()
        ->string();

    if ($body->has('communication_rating')) {
      $assert->that($communicationRating, 'communication_rating')
        ->notBlank()
        ->numeric()
        ->between(1, 5);
    }

    if ($body->has('quality_of_work_rating')) {
      $assert->that($qualityOfWorkRating, 'quality_of_work_rating')
        ->notBlank()
        ->numeric()
        ->between(1, 5);
    }

    if ($body->has('value_for_money_rating')) {
      $assert->that($valueForMoneyRating, 'value_for_money_rating')
        ->notBlank()
        ->numeric()
        ->between(1, 5);
    }

    $assert->verifyNow();

    return new self(
      projectId: $projectId,
      rating: $rating,
      review: $review,
      communicationRating: $communicationRating,
      qualityOfWorkRating: $qualityOfWorkRating,
      valueForMoneyRating: $valueForMoneyRating
    );
  }

  /**
   * @return int
   */
  public function projectId(): int
  {
    return $this->projectId;
  }

  /**
   * @return int
   */
  public function rating(): int
  {
    return $this->rating;
  }

  /**
   * @return string
   */
  public function review(): string
  {
    return $this->review;
  }

  /**
   * @return int|null
   */
  public function communicationRating(): ?int
  {
    return $this->communicationRating;
  }

  /**
   * @return int|null
   */
  public function qualityOfWorkRating(): ?int
  {
    return $this->qualityOfWorkRating;
  }

  /**
   * @return int|null
   */
  public function valueForMoneyRating(): ?int
  {
    return $this->valueForMoneyRating;
  }
}
