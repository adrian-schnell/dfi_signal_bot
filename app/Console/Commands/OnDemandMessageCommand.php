<?php

namespace App\Console\Commands;

use App\Http\Service\TelegramMessageService;
use App\Models\TelegramUser;
use Illuminate\Console\Command;

class OnDemandMessageCommand extends Command
{
	protected $signature = 'signal:on-demand {--message=} {--markdown} {--language=}';
	protected $description = 'Send a custom on demand message to all users';

	public function handle(TelegramMessageService $messageService)
	{
		$message = $this->option('message');
		$parseMarkdownOption = $this->option('markdown');
		$language = $this->option('language');
		if (\Str::length($message) < 5) {
			$this->error('please enter a message');

			return;
		}
		if ($language && in_array($language, available_languages())) {
			$users = TelegramUser::where('language', $language)->get();
		} else {
			$users = TelegramUser::all();
		}

		$this->line('');
		$this->line('');
		$this->line('########################################');
		$this->line('start sending out messages...');
		$this->line('########################################');
		$this->withProgressBar($users, function (TelegramUser $user)
		use ($messageService, $message, $parseMarkdownOption) {
			$parseMarkdown = [];
			if ($parseMarkdownOption) {
				$parseMarkdown = ['parse_mode' => 'Markdown'];
			}
			$messageService->sendMessage($user, $message, $parseMarkdown);
		});

//		$users = TelegramUser::all();
//		$users->each(function (TelegramUser $user) use ($messageService, $message, $parseMarkdownOption
//		){
//			$parseMarkdown = [];
//			if ($parseMarkdownOption) {
//				$parseMarkdown = ['parse_mode' => 'Markdown'];
//			}
//			$messageService->sendMessage($user, nl2br($message), $parseMarkdown);
//		});
	}
}
