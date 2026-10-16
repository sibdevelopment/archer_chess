<?php

namespace Tests\Unit;

use App\Models\WatiNotification;
use App\Services\WatiNotificationService;
use App\Services\WatiService;
use Illuminate\Config\Repository;
use Illuminate\Container\Container;
use Illuminate\Http\Client\Factory;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Facade;
use PHPUnit\Framework\TestCase;

class WatiServiceTest extends TestCase
{
    private Container $container;

    protected function setUp(): void
    {
        parent::setUp();

        $this->container = new Container();
        Container::setInstance($this->container);
        Facade::setFacadeApplication($this->container);
        $this->container->instance('config', new Repository([
            'services' => [
                'wati' => [
                    'enabled' => false,
                    'base_url' => 'https://live-mt-server.wati.io',
                    'token' => null,
                    'channel_number' => null,
                    'timeout' => 15,
                    'templates' => [
                        'class_reminder' => ['name' => null, 'language' => 'en'],
                        'fee_due' => ['name' => null, 'language' => 'en'],
                    ],
                ],
            ],
        ]));
        $this->container->instance(Factory::class, new Factory());
    }

    protected function tearDown(): void
    {
        Facade::clearResolvedInstances();
        Facade::setFacadeApplication(null);
        Container::setInstance();
        parent::tearDown();
    }

    public function test_wati_is_disabled_without_explicit_configuration(): void
    {
        config()->set('services.wati.enabled', false);
        config()->set('services.wati.token', 'token');
        config()->set('services.wati.channel_number', '919999999999');
        config()->set('services.wati.templates.fee_due.name', 'fee_due');

        $this->assertFalse((new WatiService())->configuredFor(WatiNotification::TYPE_FEE_DUE));
    }

    public function test_it_sends_the_expected_v3_template_payload(): void
    {
        config()->set('services.wati.enabled', true);
        config()->set('services.wati.base_url', 'https://live-mt-server.wati.io');
        config()->set('services.wati.token', 'secret-token');
        config()->set('services.wati.channel_number', '919999999999');
        config()->set('services.wati.templates.fee_due.name', 'fee_due_reminder');

        Http::fake([
            'https://live-mt-server.wati.io/*' => Http::response([
                'success' => true,
                'broadcast_id' => 'broadcast-123',
                'recipients' => [[
                    'local_message_id' => '4d853b09-d62e-4ea6-bce4-d27280f51951',
                    'phone_number' => '919876543210',
                    'errors' => [],
                ]],
            ]),
        ]);

        $notification = new WatiNotification([
            'local_message_id' => '4d853b09-d62e-4ea6-bce4-d27280f51951',
            'type' => WatiNotification::TYPE_FEE_DUE,
            'recipient' => '919876543210',
            'template_name' => 'fee_due_reminder',
            'broadcast_name' => 'fee-due-test',
            'parameters' => ['student_name' => 'Test Student', 'amount' => '100'],
        ]);

        $result = (new WatiService())->sendTemplate($notification);

        $this->assertSame(200, $result['status']);
        Http::assertSent(function ($request) {
            return $request->url() === 'https://live-mt-server.wati.io/api/ext/v3/messageTemplates/send'
                && $request->hasHeader('Authorization', 'Bearer secret-token')
                && $request['template_name'] === 'fee_due_reminder'
                && $request['recipients'][0]['phone_number'] === '919876543210'
                && $request['recipients'][0]['local_message_id'] === '4d853b09-d62e-4ea6-bce4-d27280f51951';
        });
    }

    public function test_phone_numbers_are_normalized_and_invalid_values_are_rejected(): void
    {
        $service = new WatiNotificationService(new WatiService());

        $this->assertSame('919876543210', $service->normalizePhone('+91 98765-43210'));

        $this->expectException(\InvalidArgumentException::class);
        $service->normalizePhone('1234');
    }

    public function test_it_rejects_recipient_errors_even_when_wati_returns_http_200(): void
    {
        config()->set('services.wati.enabled', true);
        config()->set('services.wati.token', 'secret-token');
        config()->set('services.wati.channel_number', '919999999999');
        config()->set('services.wati.templates.fee_due.name', 'fee_due_reminder');

        Http::fake([
            '*' => Http::response([
                'success' => true,
                'recipients' => [['errors' => ['Invalid phone number format.']]],
            ]),
        ]);

        $notification = new WatiNotification([
            'local_message_id' => '4d853b09-d62e-4ea6-bce4-d27280f51951',
            'type' => WatiNotification::TYPE_FEE_DUE,
            'recipient' => '919876543210',
            'template_name' => 'fee_due_reminder',
            'broadcast_name' => 'fee-due-test',
            'parameters' => [],
        ]);

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Invalid phone number format.');

        (new WatiService())->sendTemplate($notification);
    }

}
