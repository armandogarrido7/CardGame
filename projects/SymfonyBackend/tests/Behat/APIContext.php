<?php


declare(strict_types=1);

namespace App\Tests\Behat;

use App\Entity\Card;
use App\Entity\Game;
use App\Entity\Player;
use App\Entity\Round;
use App\Repository\GameRepository;
use Behat\Behat\Context\Context;
use Behat\Behat\Tester\Exception\PendingException;
use Behat\Gherkin\Node\TableNode;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query\ResultSetMapping;
use Doctrine\ORM\EntityManager;
use Behat\Gherkin\Node\PyStringNode;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelInterface;
use App\Entity\Account;
use function PHPUnit\Framework\assertArrayHasKey;
use function PHPUnit\Framework\assertContains;
use function PHPUnit\Framework\assertSame;
use function PHPUnit\Framework\assertStringContainsString;

/**
 * This context class contains the definitions of the steps used by the demo
 * feature file. Learn how to get started with Behat and BDD on Behat's website.
 *
 * @see http://behat.org/en/latest/quick_start.html
 */
final class APIContext implements Context
{
    /** @var KernelInterface */
    private KernelInterface $kernel;

    /** @var Response|null */
    private ?Response $response;
    private EntityManagerInterface $em;

    public function __construct(KernelInterface $kernel, EntityManagerInterface $entityManager)
    {
        $this->kernel = $kernel;
        $this->em = $entityManager;
    }
    /**
     *  @BeforeScenario
     */
    public function cleanupDB()
    {
        $rsm = new ResultSetMapping();
        $this->em->createNativeQuery('TRUNCATE TABLE Account RESTART IDENTITY CASCADE;', $rsm)->getResult();
        $this->em->createNativeQuery('TRUNCATE TABLE Game RESTART IDENTITY CASCADE;', $rsm)->getResult();
        $this->em->createNativeQuery('TRUNCATE TABLE Card RESTART IDENTITY CASCADE;', $rsm)->getResult();
        $this->em->createNativeQuery('TRUNCATE TABLE Player RESTART IDENTITY CASCADE;', $rsm)->getResult();
        $this->em->createNativeQuery('TRUNCATE TABLE Round RESTART IDENTITY CASCADE;', $rsm)->getResult();
        $this->em->createNativeQuery('TRUNCATE TABLE Subround RESTART IDENTITY CASCADE;', $rsm)->getResult();
        $this->em->createNativeQuery('TRUNCATE TABLE Play RESTART IDENTITY CASCADE;', $rsm)->getResult();
        $this->em->createNativeQuery('TRUNCATE TABLE Prediction RESTART IDENTITY CASCADE;', $rsm)->getResult();
    }

    /**
     * @When I request :resource with method :httpMethod
     */
    public function iRequestWithMethod(string $resource, string $httpMethod)
    {
        $this->response = $this->kernel->handle(Request::create($resource, $httpMethod));
    }
    /**
     * @When I request :resource with method :httpMethod and parameters
     */
    public function iRequestWithMethodAndParameters(string $resource, string $httpMethod, TableNode $table)
    {
        $parameters = [];
         foreach ($table as $row) {
             $parameters = array_merge($parameters, $row);
         }
         $parameters = json_encode($parameters);
        $this->response = $this->kernel->handle(Request::create($resource, $httpMethod, content: $parameters));
    }




    /**
     * @Given There is an Account with params
     */
    public function thereIsAnAccountWithParams(TableNode $table): void
    {
        $params = [];
        foreach ($table as $row) {
            $params = array_merge($params, $row);
        }
        $account = new Account($params['email'], $params['name'], $params['profile_pic']);
        $account->setId(intval($params['account_id']));
        $this->em->persist($account);
        $this->em->flush();
    }

    /**
     * @Given There is a Game with params
     */
    public function thereIsAGameWithParams(TableNode $table): void
    {
        $params = [];
        foreach ($table as $row) {
            $params = array_merge($params, $row);
        }
        $gameStatus = $this->em->find('App\Entity\GameStatus', 1);
        $game = new Game($params['name'], intval($params['players_num']), intval($params['account_id']), $gameStatus);
        $game->setId(intval($params['game_id']));
        $this->em->persist($game);
        $this->em->flush();
    }

    /**
     * @Given There is a Player with params
     */
    public function thereIsAPlayerWithParams(TableNode $table): void
    {
        $params = [];
        foreach ($table as $row) {
            $params = array_merge($params, $row);
        }
        $game = $this->em->find(Game::class, $params['game_id']);
        $account = $this->em->find(Account::class, $params['account_id']);
        $player = new Player($account, $game);
        $player->setId(intval($params['player_id']));
        $this->em->persist($player);
        $this->em->flush();
    }

    /**
     * @Given There is a Card with params
     */
    public function thereIsACardWithParams(TableNode $table): void
    {
        $params = [];
        foreach ($table as $row) {
            $params = array_merge($params, $row);
        }
        $card = new Card(intval($params['number']), $params['suit']);
        $card->setId(intval($params['card_id']));
        $this->em->persist($card);
        $this->em->flush();
    }

    /**
     * @Then the Response Code Should Be :expectedResponse
     */
    public function theResponseCodeShouldBe(int $expectedCode): void
    {
        assertSame($expectedCode, $this->response->getStatusCode());
    }

    /**
     * @Then the response body should contain
     */
    public function theResponseBodyShouldContain(PyStringNode $string): void
    {
        $expected_content = json_decode($string->getRaw(), true, 512, JSON_THROW_ON_ERROR);
        $response_content = json_decode($this->response->getContent(), true, 512, JSON_THROW_ON_ERROR);
        assertContains($expected_content, $response_content['data']);
    }

    /**
     * @Then the response body should have the following values
     */
    public function theResponseBodyShouldHaveTheFollowingValues(PyStringNode $string): void
    {
        $expected_content = json_decode($string->getRaw(), true, 512, JSON_THROW_ON_ERROR);
        $response_content = json_decode($this->response->getContent(), true, 512, JSON_THROW_ON_ERROR);
        foreach ($expected_content as $key => $value) {
            assertArrayHasKey($key, $response_content['data']);
            assertSame($value, $response_content['data'][$key]);
        }
    }

    /**
     * @Then the JSON response body should contain
     */
    public function theJSONResponseBodyShouldContain(PyStringNode $string): void
    {
        $expected_content = $string->getRaw();
        $response_content = $this->response->getContent();
        assertStringContainsString($expected_content, $response_content);
    }
}
