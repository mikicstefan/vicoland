<?php

namespace App\Tests\VotingSystem\Action;

use App\Domain\VotingSystem\Entity\Project;
use App\Domain\VotingSystem\Fixtures\ClientFixture;
use App\Domain\VotingSystem\Fixtures\ProjectWithoutVicoFixture;
use App\Tests\BaseApiWebTestCase;
use App\Domain\Translation\ValueObject\ErrorMessage;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class RateProjectTest
 */
class RateProjectTest extends BaseApiWebTestCase
{
  public const TEST_PROJECT_RATING = 5;
  public const TEST_PROJECT_RATING_INVALID = 51;
  public const TEST_PROJECT_REVIEW_SHORT = 'Random text';
  public const TEST_PROJECT_REVIEW_LONG = 'Certainty determine at of arranging perceived situation or. Or wholly pretty county in oppose. Favour met itself wanted settle put garret twenty. In astonished apartments resolution so an it. Unsatiable on by contrasted to reasonable companions an. On otherwise no admitting to suspicion furniture it.
Turned it up should no valley cousin he. Speaking numerous ask did horrible packages set. Ashamed herself has distant can studied mrs. Led therefore its middleton perpetual fulfilled provision frankness. Small he drawn after among every three no. All having but you edward genius though remark one.
Society excited by cottage private an it esteems. Fully begin on by wound an. Girl rich in do up or both. At declared in as rejoiced of together. He impression collecting delightful unpleasant by prosperous as on. End too talent she object mrs wanted remove giving. edward genius though remark one.
Society excited by cottage private an it esteems. Fully begin on by wound an. Girl rich in do up or both. At declared in as rejoiced of together. He impression collecting delightful unpleasant by prosperous as on. End too talent she object mrs wanted remove giving. edward genius though remark one.
Society excited by cottage private an it esteems. Fully begin on by wound an. Girl rich in do up or both. At declared in as rejoiced of together. He impression collecting delightful unpleasant by prosperous as on. End too talent she object mrs wanted remove giving. edward genius though remark one.
Society excited by cottage private an it esteems. Fully begin on by wound an. Girl rich in do up or both. At declared in as rejoiced of together. He impression collecting delightful unpleasant by prosperous as on. End too talent she object mrs wanted remove giving.';
  public const TEST_PROJECT_COMMUNICATION_RATING = 4;
  public const TEST_PROJECT_QUALITY_RATING = 2;
  public const TEST_PROJECT_RETURN_VALUE_RATING = 3;

  /**
   * RateProject is successfully updated
   *
   * @return void
   */
  public function testRateProject(): void
  {
    $this->addFixture(new ClientFixture());
    $this->addFixture(new ProjectWithoutVicoFixture());
    $this->executeFixtures();

    /** @var Project $project */
    $project = $this->getFixtureByReference(ProjectWithoutVicoFixture::TEST_PROJECT_WITHOUT_VICO_REFERENCE);

    $this->client->request(
      Request::METHOD_PATCH,
      '/api/v1/project/' . $project->getId(),
      [
        'rating' => self::TEST_PROJECT_RATING,
        'review' => self::TEST_PROJECT_REVIEW_SHORT,
        'communication_rating' => self::TEST_PROJECT_COMMUNICATION_RATING,
        'quality_of_work_rating' => self::TEST_PROJECT_QUALITY_RATING,
        'value_for_money_rating' => self::TEST_PROJECT_RETURN_VALUE_RATING
      ],
      [],
      [
        'HTTP_authorization' => $this->authToken
      ]
    );

    $response = (array)json_decode((string) $this->client->getResponse()->getContent(), true);

    $this->assertResponseIsSuccessful();
    $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    $this->assertEquals(self::TEST_PROJECT_RATING, $response['rating']);
    $this->assertEquals(self::TEST_PROJECT_REVIEW_SHORT, $response['review']);
    $this->assertEquals(self::TEST_PROJECT_COMMUNICATION_RATING, $response['communication_rating']);
    $this->assertEquals(self::TEST_PROJECT_QUALITY_RATING, $response['quality_of_work_rating']);
    $this->assertEquals(self::TEST_PROJECT_RETURN_VALUE_RATING, $response['value_for_money_rating']);
  }

  /**
   * Not updated because project with provided id does not exist
   */
  public function testRateProjectWhenProjectWithProvidedIdDoesNotExist(): void
  {
    $this->client->request(
      Request::METHOD_PATCH,
      '/api/v1/project/' . rand(),
      [
        'rating' => self::TEST_PROJECT_RATING,
        'review' => self::TEST_PROJECT_REVIEW_SHORT,
        'communication_rating' => self::TEST_PROJECT_COMMUNICATION_RATING,
        'quality_of_work_rating' => self::TEST_PROJECT_QUALITY_RATING,
        'value_for_money_rating' => self::TEST_PROJECT_RETURN_VALUE_RATING
      ],
      [],
      [
        'HTTP_authorization' => $this->authToken
      ]
    );

    /** @var array<string> $response */
    $response = (array)json_decode((string) $this->client->getResponse()->getContent(), true);

    $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    $this->assertEquals(sprintf(ErrorMessage::ERR_ENTITY_NOT_FOUND, 'Project'), $response['message']);
  }

  /**
   * Not updated because id of a project is not provided
   *
   * @return void
   */
  public function testRateProjectWhenProjectIdIsNotProvided(): void
  {
    $this->client->request(
      Request::METHOD_PATCH,
      '/api/v1/project/',
      [
        'rating' => self::TEST_PROJECT_RATING,
        'review' => self::TEST_PROJECT_REVIEW_SHORT,
        'communication_rating' => self::TEST_PROJECT_COMMUNICATION_RATING,
        'quality_of_work_rating' => self::TEST_PROJECT_QUALITY_RATING,
        'value_for_money_rating' => self::TEST_PROJECT_RETURN_VALUE_RATING
      ],
      [],
      [
        'HTTP_authorization' => $this->authToken
      ]
    );

    /** @var array<string> $response */
    $response = (array)json_decode((string) $this->client->getResponse()->getContent(), true);

    $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    $this->assertEquals(ErrorMessage::ERR_ENDPOINT_NOT_FOUND, $response['message']);
  }

  /**
   * Project is not updated because provided id is non-numeric
   *
   * @return void
   */
  public function testRateProjectWhenProjectIdIsNonNumeric(): void
  {
    $invalidId = '123sas-123';

    $this->client->request(
      Request::METHOD_PATCH,
      '/api/v1/project/' . $invalidId,
      [
        'rating' => self::TEST_PROJECT_RATING,
        'review' => self::TEST_PROJECT_REVIEW_SHORT,
        'communication_rating' => self::TEST_PROJECT_COMMUNICATION_RATING,
        'quality_of_work_rating' => self::TEST_PROJECT_QUALITY_RATING,
        'value_for_money_rating' => self::TEST_PROJECT_RETURN_VALUE_RATING
      ],
      [],
      [
        'HTTP_authorization' => $this->authToken
      ]
    );


    /** @var array<string> $response */
    $response = (array)json_decode((string) $this->client->getResponse()->getContent(), true);

    $this->assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
    $this->assertStringContainsString(
      'project_id: Value "' . $invalidId . '" is not numeric',
      $response['message']
    );
  }

  /**
   * Not updated because rating is required field
   *
   * @return void
   */
  public function testRateProjectWithRatingNull(): void
  {
    $this->addFixture(new ClientFixture());
    $this->addFixture(new ProjectWithoutVicoFixture());
    $this->executeFixtures();

    /** @var Project $project */
    $project = $this->getFixtureByReference(ProjectWithoutVicoFixture::TEST_PROJECT_WITHOUT_VICO_REFERENCE);

    $this->client->request(
      Request::METHOD_PATCH,
      '/api/v1/project/' . $project->getId(),
      [
        'review' => self::TEST_PROJECT_REVIEW_SHORT,
        'communication_rating' => self::TEST_PROJECT_COMMUNICATION_RATING,
        'quality_of_work_rating' => self::TEST_PROJECT_QUALITY_RATING,
        'value_for_money_rating' => self::TEST_PROJECT_RETURN_VALUE_RATING
      ],
      [],
      [
        'HTTP_authorization' => $this->authToken
      ]
    );

    $response = (array)json_decode((string) $this->client->getResponse()->getContent(), true);

    $this->assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
    $this->assertStringContainsString('rating: Value "<NULL>" is blank', $response['message']);
  }

  /**
   * Project is not updated with empty rating
   *
   * @return void
   */
  public function testRateProjectWithEmptyRating(): void
  {
    $this->addFixture(new ClientFixture());
    $this->addFixture(new ProjectWithoutVicoFixture());
    $this->executeFixtures();

    /** @var Project $project */
    $project = $this->getFixtureByReference(ProjectWithoutVicoFixture::TEST_PROJECT_WITHOUT_VICO_REFERENCE);

    $this->client->request(
      Request::METHOD_PATCH,
      '/api/v1/project/' . $project->getId(),
      [
        'rating' => '',
        'review' => self::TEST_PROJECT_REVIEW_SHORT,
        'communication_rating' => self::TEST_PROJECT_COMMUNICATION_RATING,
        'quality_of_work_rating' => self::TEST_PROJECT_QUALITY_RATING,
        'value_for_money_rating' => self::TEST_PROJECT_RETURN_VALUE_RATING
      ],
      [],
      [
        'HTTP_authorization' => $this->authToken
      ]
    );

    $response = (array)json_decode((string) $this->client->getResponse()->getContent(), true);

    $this->assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
    $this->assertStringContainsString('rating: Value "" is blank', $response['message']);
  }

  /**
   * Project is not updated with rating value null
   *
   * @return void
   */
  public function testRateProjectWithRatingValueNull(): void
  {
    $this->addFixture(new ClientFixture());
    $this->addFixture(new ProjectWithoutVicoFixture());
    $this->executeFixtures();

    /** @var Project $project */
    $project = $this->getFixtureByReference(ProjectWithoutVicoFixture::TEST_PROJECT_WITHOUT_VICO_REFERENCE);

    $this->client->request(
      Request::METHOD_PATCH,
      '/api/v1/project/' . $project->getId(),
      [
        'rating' => null,
        'review' => self::TEST_PROJECT_REVIEW_SHORT,
        'communication_rating' => self::TEST_PROJECT_COMMUNICATION_RATING,
        'quality_of_work_rating' => self::TEST_PROJECT_QUALITY_RATING,
        'value_for_money_rating' => self::TEST_PROJECT_RETURN_VALUE_RATING
      ],
      [],
      [
        'HTTP_authorization' => $this->authToken
      ]
    );

    $response = (array)json_decode((string) $this->client->getResponse()->getContent(), true);

    $this->assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
    $this->assertStringContainsString('rating: Value "" is blank', $response['message']);
  }

  /**
   * Project is not updated because rating is off 1 to 5 range
   *
   * @return void
   */
  public function testRateProjectWithRatingValueOutOfRange(): void
  {
    $this->addFixture(new ClientFixture());
    $this->addFixture(new ProjectWithoutVicoFixture());
    $this->executeFixtures();

    /** @var Project $project */
    $project = $this->getFixtureByReference(ProjectWithoutVicoFixture::TEST_PROJECT_WITHOUT_VICO_REFERENCE);

    $this->client->request(
      Request::METHOD_PATCH,
      '/api/v1/project/' . $project->getId(),
      [
        'rating' => self::TEST_PROJECT_RATING_INVALID,
        'review' => self::TEST_PROJECT_REVIEW_SHORT,
        'communication_rating' => self::TEST_PROJECT_COMMUNICATION_RATING,
        'quality_of_work_rating' => self::TEST_PROJECT_QUALITY_RATING,
        'value_for_money_rating' => self::TEST_PROJECT_RETURN_VALUE_RATING
      ],
      [],
      [
        'HTTP_authorization' => $this->authToken
      ]
    );

    $response = (array)json_decode((string) $this->client->getResponse()->getContent(), true);

    $this->assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
    $this->assertStringContainsString(
      'rating: Provided "'. self::TEST_PROJECT_RATING_INVALID .'" is neither greater than or equal to "1" nor less than or equal to "5"',
      $response['message']
    );
  }

  /**
   * Project is not updated because review is numeric
   *
   * @return void
   */
  public function testRateProjectWithNumericReview(): void
  {
    $this->addFixture(new ClientFixture());
    $this->addFixture(new ProjectWithoutVicoFixture());
    $this->executeFixtures();

    /** @var Project $project */
    $project = $this->getFixtureByReference(ProjectWithoutVicoFixture::TEST_PROJECT_WITHOUT_VICO_REFERENCE);

    $this->client->request(
      Request::METHOD_PATCH,
      '/api/v1/project/' . $project->getId(),
      [],
      [],
      [
        'CONTENT_TYPE' => 'application/json',
        'HTTP_authorization' => $this->authToken
      ],
      json_encode([
        'rating' => self::TEST_PROJECT_RATING,
        'review' => 123456,
        'communication_rating' => self::TEST_PROJECT_COMMUNICATION_RATING,
        'quality_of_work_rating' => self::TEST_PROJECT_QUALITY_RATING,
        'value_for_money_rating' => self::TEST_PROJECT_RETURN_VALUE_RATING
      ])
    );

    $response = (array)json_decode((string) $this->client->getResponse()->getContent(), true);

    $this->assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
    $this->assertStringContainsString(
      'review: Value "123456" expected to be string, type integer given',
      $response['message']
    );
  }

  /**
   * Update project when second page is skipped
   *
   * @return void
   */
  public function testRateProjectWhenSecondPageIsSkipped(): void
  {
    $this->addFixture(new ClientFixture());
    $this->addFixture(new ProjectWithoutVicoFixture());
    $this->executeFixtures();

    /** @var Project $project */
    $project = $this->getFixtureByReference(ProjectWithoutVicoFixture::TEST_PROJECT_WITHOUT_VICO_REFERENCE);

    $this->client->request(
      Request::METHOD_PATCH,
      '/api/v1/project/' . $project->getId(),
      [],
      [],
      [
        'CONTENT_TYPE' => 'application/json',
        'HTTP_authorization' => $this->authToken
      ],
      json_encode([
        'rating' => self::TEST_PROJECT_RATING,
        'review' => self::TEST_PROJECT_REVIEW_SHORT
      ])
    );

    $response = (array)json_decode((string) $this->client->getResponse()->getContent(), true);

    $this->assertResponseIsSuccessful();
    $this->assertNull($response['communication_rating']);
    $this->assertNull($response['quality_of_work_rating']);
    $this->assertNull($response['value_for_money_rating']);
  }

  /**
   * Project is not updated because communication rating is off 1 to 5 range
   *
   * @return void
   */
  public function testRateProjectWithCommunicationRatingValueOutOfRange(): void
  {
    $this->addFixture(new ClientFixture());
    $this->addFixture(new ProjectWithoutVicoFixture());
    $this->executeFixtures();

    /** @var Project $project */
    $project = $this->getFixtureByReference(ProjectWithoutVicoFixture::TEST_PROJECT_WITHOUT_VICO_REFERENCE);

    $this->client->request(
      Request::METHOD_PATCH,
      '/api/v1/project/' . $project->getId(),
      [
        'rating' => self::TEST_PROJECT_RATING,
        'review' => self::TEST_PROJECT_REVIEW_SHORT,
        'communication_rating' => self::TEST_PROJECT_RATING_INVALID,
        'quality_of_work_rating' => self::TEST_PROJECT_QUALITY_RATING,
        'value_for_money_rating' => self::TEST_PROJECT_RETURN_VALUE_RATING
      ],
      [],
      [
        'HTTP_authorization' => $this->authToken
      ]
    );

    $response = (array)json_decode((string) $this->client->getResponse()->getContent(), true);

    $this->assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
    $this->assertStringContainsString(
      'communication_rating: Provided "'. self::TEST_PROJECT_RATING_INVALID .'" is neither greater than or equal to "1" nor less than or equal to "5"',
      $response['message']
    );
  }

  /**
   * Project updated with long review text
   *
   * @return void
   */
  public function testRateProjectWithLongReview(): void
  {
    $this->addFixture(new ClientFixture());
    $this->addFixture(new ProjectWithoutVicoFixture());
    $this->executeFixtures();

    /** @var Project $project */
    $project = $this->getFixtureByReference(ProjectWithoutVicoFixture::TEST_PROJECT_WITHOUT_VICO_REFERENCE);

    $this->client->request(
      Request::METHOD_PATCH,
      '/api/v1/project/' . $project->getId(),
      [
        'rating' => self::TEST_PROJECT_RATING,
        'review' => self::TEST_PROJECT_REVIEW_LONG
      ],
      [],
      [
        'HTTP_authorization' => $this->authToken
      ]
    );

    $this->assertResponseIsSuccessful();
  }

  /**
   * Non-valid auth token is provided.
   * Unauthorized exception is triggered
   *
   * @return void
   */
  public function testRateProjectWithoutAuthToken(): void
  {
    $this->addFixture(new ClientFixture());
    $this->addFixture(new ProjectWithoutVicoFixture());
    $this->executeFixtures();

    /** @var Project $project */
    $project = $this->getFixtureByReference(ProjectWithoutVicoFixture::TEST_PROJECT_WITHOUT_VICO_REFERENCE);

    $this->testWithoutAuthTokenBase(Request::METHOD_PATCH, 'api/v1/project/' . $project->getId());
  }

  /**
   * Non-valid auth token is provided.
   * Unauthorized exception is triggered
   *
   * @return void
   */
  public function testRateProjectWithInvalidAuthToken(): void
  {
    $this->addFixture(new ClientFixture());
    $this->addFixture(new ProjectWithoutVicoFixture());
    $this->executeFixtures();

    /** @var Project $project */
    $project = $this->getFixtureByReference(ProjectWithoutVicoFixture::TEST_PROJECT_WITHOUT_VICO_REFERENCE);

    $this->testWithInvalidAuthTokenBase(
      Request::METHOD_PATCH,
      'api/v1/project/' . $project->getId()
    );
  }

  /**
   * Testing case when there are no authorization
   *
   * @return void
   */
  public function testRateProjectWithoutAuthorization()
  {
    $this->addFixture(new ClientFixture());
    $this->addFixture(new ProjectWithoutVicoFixture());
    $this->executeFixtures();

    /** @var Project $project */
    $project = $this->getFixtureByReference(ProjectWithoutVicoFixture::TEST_PROJECT_WITHOUT_VICO_REFERENCE);

    $this->testWithoutAuthorizationBase(
      Request::METHOD_PATCH,
      'api/v1/project/' . $project->getId()
    );
  }
}
