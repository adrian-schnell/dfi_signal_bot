<x-base>
    <section class="section">
        <div class="header">
            <nav class="header__navbar navbar navbar-expand-lg navbar-dark bg-dark">
                <div class="container-fluid">
                    <a class="navbar-brand" href="{{ config('app.url') }}"> <img src="{{ asset('images/logo.svg') }}"
                                                                                 alt="DFI Signal logo"
                                                                                 class="header__logo" width="512"
                                                                                 height="173"/> </a>
                </div>
            </nav>
        </div>
    </section>
    <section class="section">
        <div class="about">
            <div class="container">
                <h2>What is DFI Signal?</h2>
                <p>
                    DFI Signal is a <a href="{{ getTelegramBotLink() }}" target="_blank">Telegram bot</a>, which informs
                    you on specific events. For now, it informs you on
                    new minted blocks of your masternode and calculated statistics of your nodes (command
                    <code>/stats</code>). You get notifications on state changes (like change from
                    <code>PRE_ENABLED</code> to <code>ENABLED</code> or from <code>ENABLED</code> to
                    <code>PRE_RESIGNED</code>), too.
                </p>

                <hr/>
                <h2>How to use this bot?</h2>
                <p>
                    After <a href="{{ getTelegramBotLink() }}" target="_blank">starting the bot</a>, you can choose if
                    you like to setup your masternode manually by entering the owner address (command
                    <code>/link_mn &ltOWNER_ADDRESS&gt;</code>) or if you'd like to sync
                    your masternodes from
                    <a href="https://www.DeFichain-masternode-monitor.com" target="_blank">
                        Masternode Monitor
                    </a>(start it later with the command <code>/sync</code>).
                </p>
                <p>
                    If you want to see, which masternodes are linked with the bot, use the command <code>/list</code>.
                </p>
                <p>
                    For more detailled statistics of your nodes, use the command <code>/stats</code>.
                </p>
                <p>All other commands are available inside telegram when typing <code>/</code> or on the
                    <a href="{{route('home') }}">startpage</a>.
                </p>

                <hr/>
                <h2>Does this service affect my masternode?</h2>
                <p>
                    No! - This service is using public APIs (see below) to send you signals or generates the
                    statistics. <br> At no time your funds are touched.
                </p>
                <hr/>
                <h2>What's about privacy?</h2>
                <p>
                    The website and the application does no tracking - your usage is completly in your own hands!
                </p>
                <p>
                    As far as I support decentralized services, this service  needs to store a couple of
                    data in a database to get to work.
                </p>
                <p>
                    These are:
                </p>
                <ul>
                    <li>your telegram id</li>
                    <li>your masternode's owner address</li>
                    <li>minted blocks by your masternode's</li>
                </ul>
                <p>
                    All notifications and calculations are based on these data, the usage of public, read-only APIs and
                    some fancy magic. Neither your masternode nor anything else can be changed by using these APIs.
                    <br>Used APIs are:
                </p>
                <ul>
                    <li>https://api.DeFiChain.io/v1/</li>
                    <li>https://mainnet-api.DeFiChain.io/api/DFI/mainnet/</li>
                    <li>http://api.mydeficha.in</li>
                </ul>
                <p>
                    On top, it's optionally using the sync service provided by Masternode Monitor to read (onetime)
                    your linked masternodes:
                </p>
                <ul>
                    <li>https://sync.DeFichain-masternode-monitor.com (optional for masternode sync)</li>
                </ul>
                <p>
                    This service is hosted by Hetzner in Germany and all communication is SSL-encrypted with a
                    certificate by <a href="https://letsencrypt.org/" target="_blank">Let's Encrypt</a>.<br>
                </p>

                <hr>
                <h2>I'd love to stop using this service - howto?</h2>
                <p>
                    If you'd like to stop the bot and remove all (!) data, which is connected to your telegram id,
                    enter the command <code>/reset</code>. <br> All data will be removed immediately without the
                    possibility to restore it.
                </p>

                <hr>
                <h2>Some details about the dev</h2>
                <p>
                    My name is Adrian and I'm based in the south of Germany. I'm a professional web developer since
                    2006.
                </p>
                <p>
                    I'm in crypto since a couple of years and right now I'm running my own DFI masternode. That's the
                    reason why I created this bot: <br>
                    I wanted to get informed after my node minted a new block.
                </p>
                <p>
                    If you'd like to talk with me, send me feedback or if you'd like to see a new feature, send me a <a
                        href="{{ getTelegramGroupLink() }}">PM on
                        Telegram</a>.
                </p>
            </div>
        </div>
    </section>
</x-base>
