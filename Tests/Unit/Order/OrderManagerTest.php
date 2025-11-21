<?php
declare(strict_types=1);

namespace Madj2k\ShopwareConnector\Tests\Unit\Order;

use Madj2k\ShopwareConnector\Order\OrderManager;
use Madj2k\ShopwareConnector\Order\Handler\ContextHandler;
use Madj2k\ShopwareConnector\Order\Handler\CustomerHandler;
use Madj2k\ShopwareConnector\Order\Handler\CartHandler;
use Madj2k\ShopwareConnector\Order\Handler\OrderHandler;
use Madj2k\ShopwareConnector\Order\Handler\PaymentHandler;
use Madj2k\ShopwareConnector\Order\Handler\StatusHandler;
use Madj2k\ShopwareConnector\Order\Handler\DownloadHandler;

use Madj2k\ShopwareConnector\Domain\DTO\Cart;
use Madj2k\ShopwareConnector\Domain\DTO\Customer;
use Madj2k\ShopwareConnector\Domain\DTO\Context;
use Madj2k\ShopwareConnector\Domain\DTO\Order;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * Class OrderManagerTest
 *
 * Tests alle Fehlerpfade der OrderManager::processFreeDownloads Methode
 *
 * @author
 * @package
 */
class OrderManagerTest extends TestCase
{

    /**
     * Baut den Manager für Tests
     */
    private function buildManager(
        $contextHandler,
        $customerHandler,
        $cartHandler,
        $orderHandler,
        $paymentHandler,
        $statusHandler,
        $downloadHandler
    ): OrderManager {

        return new OrderManager(
            $contextHandler,
            $customerHandler,
            $cartHandler,
            $orderHandler,
            $paymentHandler,
            $statusHandler,
            $downloadHandler
        );
    }


    // -------------------------------------------------------------------------
    // SUCCESS CASE
    // -------------------------------------------------------------------------

    /**
     * @return void
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Madj2k\ShopwareConnector\Exception
     * @throws \PHPUnit\Framework\MockObject\Exception
     * @throws \Throwable
     */
    #[Test]
    public function processFreeDownloadsSuccess(): void
    {
        $contextHandler = $this->createMock(ContextHandler::class);
        $customerHandler = $this->createMock(CustomerHandler::class);
        $cartHandler = $this->createMock(CartHandler::class);
        $orderHandler = $this->createMock(OrderHandler::class);
        $paymentHandler = $this->createMock(PaymentHandler::class);
        $statusHandler = $this->createMock(StatusHandler::class);
        $downloadHandler = $this->createMock(DownloadHandler::class);

        $cartDto = new Cart();
        $customerDto = new Customer();
        $contextDto = new Context();
        $orderData = ['transactions' => [['id' => 'txn-123']]];
        $orderDto = new Order($orderData);

        $downloads = ['file.pdf' => 'CONTENT'];

        $contextHandler->method('provide')->willReturn($contextDto);
        $customerHandler->method('register')->willReturn($customerDto);
        $cartHandler->method('addToCart')->willReturn($cartDto);
        $orderHandler->method('createOrder')->willReturn($orderDto);
        $paymentHandler->method('setPaymentState')->willReturn(true);
        $statusHandler->method('setStatus')->willReturn(true);
        $downloadHandler->method('getDownloads')->willReturn($downloads);

        $manager = $this->buildManager(
            $contextHandler,
            $customerHandler,
            $cartHandler,
            $orderHandler,
            $paymentHandler,
            $statusHandler,
            $downloadHandler
        );

        $result = $manager->processFreeDownloads($cartDto, $customerDto);

        $this->assertEquals($downloads, $result);
    }


    // -------------------------------------------------------------------------
    // FAILURE CASES
    // -------------------------------------------------------------------------

    /**
     * @return void
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Madj2k\ShopwareConnector\Exception
     * @throws \PHPUnit\Framework\MockObject\Exception
     * @throws \Throwable
     */
    #[Test]
    public function processFreeDownloadsFailsOnCustomerRegistration(): void
    {
        $contextHandler = $this->createMock(ContextHandler::class);
        $customerHandler = $this->createMock(CustomerHandler::class);
        $cartHandler = $this->createMock(CartHandler::class);
        $orderHandler = $this->createMock(OrderHandler::class);
        $paymentHandler = $this->createMock(PaymentHandler::class);
        $statusHandler = $this->createMock(StatusHandler::class);
        $downloadHandler = $this->createMock(DownloadHandler::class);

        $cartDto = new Cart();
        $customerDto = new Customer();
        $contextDto = new Context();

        $contextHandler->method('provide')->willReturn($contextDto);
        $customerHandler->method('register')->willReturn(null);

        $manager = $this->buildManager(
            $contextHandler,
            $customerHandler,
            $cartHandler,
            $orderHandler,
            $paymentHandler,
            $statusHandler,
            $downloadHandler
        );

        $result = $manager->processFreeDownloads($cartDto, $customerDto);

        $this->assertNull($result);
    }


    /**
     * @return void
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Madj2k\ShopwareConnector\Exception
     * @throws \PHPUnit\Framework\MockObject\Exception
     * @throws \Throwable
     */
    #[Test]
    public function processFreeDownloadsFailsOnCart(): void
    {
        $contextHandler = $this->createMock(ContextHandler::class);
        $customerHandler = $this->createMock(CustomerHandler::class);
        $cartHandler = $this->createMock(CartHandler::class);
        $orderHandler = $this->createMock(OrderHandler::class);
        $paymentHandler = $this->createMock(PaymentHandler::class);
        $statusHandler = $this->createMock(StatusHandler::class);
        $downloadHandler = $this->createMock(DownloadHandler::class);

        $cartDto = new Cart();
        $customerDto = new Customer();
        $contextDto = new Context();

        $contextHandler->method('provide')->willReturn($contextDto);
        $customerHandler->method('register')->willReturn($customerDto);
        $cartHandler->method('addToCart')->willReturn(null);

        $manager = $this->buildManager(
            $contextHandler,
            $customerHandler,
            $cartHandler,
            $orderHandler,
            $paymentHandler,
            $statusHandler,
            $downloadHandler
        );

        $result = $manager->processFreeDownloads($cartDto, $customerDto);

        $this->assertNull($result);
    }


    /**
     * @return void
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Madj2k\ShopwareConnector\Exception
     * @throws \PHPUnit\Framework\MockObject\Exception
     * @throws \Throwable
     */
    #[Test]
    public function processFreeDownloadsFailsOnOrderCreation(): void
    {
        $contextHandler = $this->createMock(ContextHandler::class);
        $customerHandler = $this->createMock(CustomerHandler::class);
        $cartHandler = $this->createMock(CartHandler::class);
        $orderHandler = $this->createMock(OrderHandler::class);
        $paymentHandler = $this->createMock(PaymentHandler::class);
        $statusHandler = $this->createMock(StatusHandler::class);
        $downloadHandler = $this->createMock(DownloadHandler::class);

        $cartDto = new Cart();
        $customerDto = new Customer();
        $contextDto = new Context();

        $contextHandler->method('provide')->willReturn($contextDto);
        $customerHandler->method('register')->willReturn($customerDto);
        $cartHandler->method('addToCart')->willReturn($cartDto);
        $orderHandler->method('createOrder')->willReturn(null);

        $manager = $this->buildManager(
            $contextHandler,
            $customerHandler,
            $cartHandler,
            $orderHandler,
            $paymentHandler,
            $statusHandler,
            $downloadHandler
        );

        $result = $manager->processFreeDownloads($cartDto, $customerDto);

        $this->assertNull($result);
    }


    /**
     * @return void
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Madj2k\ShopwareConnector\Exception
     * @throws \PHPUnit\Framework\MockObject\Exception
     * @throws \Throwable
     */
    #[Test]
    public function processFreeDownloadsFailsOnMissingTransactionId(): void
    {
        $contextHandler = $this->createMock(ContextHandler::class);
        $customerHandler = $this->createMock(CustomerHandler::class);
        $cartHandler = $this->createMock(CartHandler::class);
        $orderHandler = $this->createMock(OrderHandler::class);
        $paymentHandler = $this->createMock(PaymentHandler::class);
        $statusHandler = $this->createMock(StatusHandler::class);
        $downloadHandler = $this->createMock(DownloadHandler::class);

        $cartDto = new Cart();
        $customerDto = new Customer();
        $contextDto = new Context();

        $orderData = ['transactions' => []];
        $orderDto = new Order($orderData);

        $contextHandler->method('provide')->willReturn($contextDto);
        $customerHandler->method('register')->willReturn($customerDto);
        $cartHandler->method('addToCart')->willReturn($cartDto);
        $orderHandler->method('createOrder')->willReturn($orderDto);

        $manager = $this->buildManager(
            $contextHandler,
            $customerHandler,
            $cartHandler,
            $orderHandler,
            $paymentHandler,
            $statusHandler,
            $downloadHandler
        );

        $result = $manager->processFreeDownloads($cartDto, $customerDto);

        $this->assertNull($result);
    }


    /**
     * @return void
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Madj2k\ShopwareConnector\Exception
     * @throws \PHPUnit\Framework\MockObject\Exception
     * @throws \Throwable
     */
    #[Test]
    public function processFreeDownloadsFailsOnPayment(): void
    {
        $contextHandler = $this->createMock(ContextHandler::class);
        $customerHandler = $this->createMock(CustomerHandler::class);
        $cartHandler = $this->createMock(CartHandler::class);
        $orderHandler = $this->createMock(OrderHandler::class);
        $paymentHandler = $this->createMock(PaymentHandler::class);
        $statusHandler = $this->createMock(StatusHandler::class);
        $downloadHandler = $this->createMock(DownloadHandler::class);

        $cartDto = new Cart();
        $customerDto = new Customer();
        $contextDto = new Context();

        $orderData = ['transactions' => [['id' => 'txn-123']]];
        $orderDto = new Order($orderData);

        $contextHandler->method('provide')->willReturn($contextDto);
        $customerHandler->method('register')->willReturn($customerDto);
        $cartHandler->method('addToCart')->willReturn($cartDto);
        $orderHandler->method('createOrder')->willReturn($orderDto);
        $paymentHandler->method('setPaymentState')->willReturn(false);

        $manager = $this->buildManager(
            $contextHandler,
            $customerHandler,
            $cartHandler,
            $orderHandler,
            $paymentHandler,
            $statusHandler,
            $downloadHandler
        );

        $result = $manager->processFreeDownloads($cartDto, $customerDto);

        $this->assertNull($result);
    }


    /**
     * @return void
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Madj2k\ShopwareConnector\Exception
     * @throws \PHPUnit\Framework\MockObject\Exception
     * @throws \Throwable
     */
    #[Test]
    public function processFreeDownloadsFailsOnOrderStatus(): void
    {
        $contextHandler = $this->createMock(ContextHandler::class);
        $customerHandler = $this->createMock(CustomerHandler::class);
        $cartHandler = $this->createMock(CartHandler::class);
        $orderHandler = $this->createMock(OrderHandler::class);
        $paymentHandler = $this->createMock(PaymentHandler::class);
        $statusHandler = $this->createMock(StatusHandler::class);
        $downloadHandler = $this->createMock(DownloadHandler::class);

        $cartDto = new Cart();
        $customerDto = new Customer();
        $contextDto = new Context();

        $orderData = ['transactions' => [['id' => 'txn-123']]];
        $orderDto = new Order($orderData);

        $contextHandler->method('provide')->willReturn($contextDto);
        $customerHandler->method('register')->willReturn($customerDto);
        $cartHandler->method('addToCart')->willReturn($cartDto);
        $orderHandler->method('createOrder')->willReturn($orderDto);
        $paymentHandler->method('setPaymentState')->willReturn(true);
        $statusHandler->method('setStatus')->willReturn(false);

        $manager = $this->buildManager(
            $contextHandler,
            $customerHandler,
            $cartHandler,
            $orderHandler,
            $paymentHandler,
            $statusHandler,
            $downloadHandler
        );

        $result = $manager->processFreeDownloads($cartDto, $customerDto);

        $this->assertNull($result);
    }
}
