<?php

return [
	'loading_data'  => 'Please wait.. loading server info could take up to a minute.',
	'no_data'       => "❌ Until now, no data was reported. Are you sure you followed all steps of the setup guide?\r\n\r\nMaybe check the functionally with running manually on your server:\r\n`masternode-health --verbose --report --api-key=your-api-key`",
	'latest_update' => "\r\n\r\nlast update: :time",

	'server_stats' => [
		'disk_usage'  => "Disk Usage:\t:used/:total GB\r\n `:progress`",
		'ram_usage'   => "\r\n\r\nRAM Usage:\t:used/:total GB\r\n `:progress`",
		'system_load' => "\r\n\r\nSystem Load:\r\n `:progress`",
	],
	'nodeInfo'     => [
		'block_height'     => "*Block Height:* :value",
		'block_hash'       => "\r\n*Block Hash:* [:value](:link)",
		'log_size'         => "\r\n*Log File Size:* :value MB",
		'connection_count' => "\r\n*Connections:* :value",
		'node_version'     => "\r\n*Node Version:* :value",
		'node_uptime'      => "\r\n*Node Uptime:* :value",
		'operator_status'  => "\r\n\r\n*Operator Status:*",
	],
];
