<?php

namespace App\Http\Service;

use App\Exceptions\MasternodeHealthApiException;
use App\Models\TelegramUser;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use Log;
use Throwable;

class MasternodeHealthApiService
{
	protected ClientInterface $client;
	protected TelegramUser $user;

	public function __construct(TelegramUser $user)
	{
		$this->client = new Client([
			'base_uri'        => config('masternode_health.base_uri'),
			'timeout'         => 5,
			'connect_timeout' => 5,
		]);
		$this->user = $user;
	}

	/**
	 * @throws MasternodeHealthApiException
	 */
	public function setupApiKey(): self
	{
		try {
			$response = json_decode(
				$this->client->get(config('masternode_health.endpoints.setup_api_key'), [
					'timeout'            => 5,
					'connection_timeout' => 5,
				])->getBody()->getContents(),
				true
			);
			$this->user->update([
				'server_health_api_key' => $response['api_key'],
			]);

			return $this;
		} catch (Throwable $e) {
			Log::error('failed creating api key', [
				'file'    => $e->getFile(),
				'message' => $e->getMessage(),
				'line'    => $e->getLine(),
			]);
			throw MasternodeHealthApiException::generic($e->getMessage(), $e);
		}
	}

	/**
	 * @throws MasternodeHealthApiException
	 */
	public function setupWebhook(): self
	{
		try {
			$this->client->post(config('masternode_health.endpoints.webhook_create'), [
				'timeout'            => 5,
				'connection_timeout' => 5,
				'headers'            => [
					'x-api-key' => $this->user->server_health_api_key,
				],
				'form_params'        => [
					'url'       => route('webhook.masternode_health'),
					'reference' => encrypt($this->user->id),
				],
			]);

			return $this;
		} catch (Throwable $e) {
			Log::error('failed creating webhook', [
				'file'    => $e->getFile(),
				'message' => $e->getMessage(),
				'line'    => $e->getLine(),
			]);
			throw MasternodeHealthApiException::generic($e->getMessage(), $e);
		}
	}

	/**
	 * @throws MasternodeHealthApiException
	 */
	public function deleteWebhook(): self
	{
		try {
			$this->client->delete(config('masternode_health.endpoints.webhook_delete'), [
				'timeout'            => 5,
				'connection_timeout' => 5,
				'headers'            => [
					'x-api-key' => $this->user->server_health_api_key,
				],
			]);

			return $this;
		} catch (Throwable $e) {
			Log::error('failed deleting webhook', [
				'file'    => $e->getFile(),
				'message' => $e->getMessage(),
				'line'    => $e->getLine(),
			]);
			throw MasternodeHealthApiException::generic($e->getMessage(), $e);
		}
	}

	/**
	 * @throws MasternodeHealthApiException
	 */
	public function getNodeInfo(): array
	{
		try {
			return json_decode(
				$this->client->get(config('masternode_health.endpoints.pull_node_info'), [
					'timeout'            => 30,
					'connection_timeout' => 5,
					'headers'            => [
						'x-api-key' => $this->user->server_health_api_key,
					],
				])->getBody()->getContents(),
				true
			);
		} catch (Throwable $e) {
			Log::error('failed loading node info', [
				'file'    => $e->getFile(),
				'message' => $e->getMessage(),
				'line'    => $e->getLine(),
			]);
			throw MasternodeHealthApiException::generic($e->getMessage(), $e);
		}
	}

	/**
	 * @throws MasternodeHealthApiException
	 */
	public function getServerStats(): array
	{
		try {
			return json_decode(
				$this->client->get(config('masternode_health.endpoints.pull_server_stats'), [
					'timeout'            => 30,
					'connection_timeout' => 5,
					'headers'            => [
						'x-api-key' => $this->user->server_health_api_key,
					],
				])->getBody()->getContents(),
				true
			);
		} catch (Throwable $e) {
			Log::error('failed loading server stats', [
				'file'    => $e->getFile(),
				'message' => $e->getMessage(),
				'line'    => $e->getLine(),
			]);
			throw MasternodeHealthApiException::generic($e->getMessage(), $e);
		}
	}
}
