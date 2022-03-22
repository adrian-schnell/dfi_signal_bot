<?php

namespace App\Console\Commands;

use App\Http\Service\TelegramMessageService;
use App\Models\TelegramUser;
use Illuminate\Console\Command;
use Str;

class BroadcastMessageCommand extends Command
{
	protected $signature = 'broadcast:message {--markdown} {--language=} {--debug}';
	protected $description = 'Send a custom on demand message to all users';

	public function handle(TelegramMessageService $messageService): void
	{
		$message = $this->ask('Enter your wished message now:');
		$language = $this->option('language');
		$debug = $this->option('debug');
		$parseMarkdown = $this->option('markdown')
			? ['parse_mode' => 'Markdown','disable_web_page_preview' => true]
			: ['disable_web_page_preview' => true];
		if (Str::length($message) < 5) {
			$this->error('please enter a message');

			return;
		}

		if ($language && in_array($language, available_languages())) {
			$users = TelegramUser::where('language', $language)->get();
		} else {
			$users = TelegramUser::all();
		}

		if (!$this->confirm('Sure you want to send out this message to all users?', false)) {
			$this->info('cancelling broadcasting message');

			return;
		}

		$message = implode("\n", explode('\n', $message));

		$this->line('');
		$this->line('');
		$this->line('########################################');
		$this->line('start sending out messages...');
		$this->line('########################################');
		$this->withProgressBar($users, function (TelegramUser $user)
		use ($messageService, $message, $parseMarkdown, $debug) {
			if (!$debug) {
				$messageService->sendMessage($user, $message, $parseMarkdown);
			}
		});
	}
}
