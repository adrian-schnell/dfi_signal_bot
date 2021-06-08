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
        <div class="intro">
            <div class="intro__bg"><img src="{{ asset('images/icon.svg') }}" width="173" height="173"
                                        alt="DFI Signal Icon" class="intro__icon" loading="lazy"/></div>
            <div class="container">
                <div class="row">
                    <div class="col-md intro__content-col">
                        <h1>DFI Signal</h1>
                        <p class="lead"> get informed by signals on Telegram </p> <a
                            href="{{ getTelegramBotLink() }}"
                            class="btn btn-primary btn-lg btn-custom" target="_blank">
                            setup DFI Signal
                        </a></div>
                    <div class="col-md">
                        <div class="phone">
                            <div class="phone__wrapper"><img src="{{ asset('images/phone.png') }}" alt=""
                                                             class="phone__phone" width="540" height="641"
                                                             loading="lazy"/> <img
                                    src="{{ asset('images/screen.jpg') }}" alt="" class="phone__screen" loading="lazy"/>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="section">
        <div class="about">
            <div class="container">
                <h2>Masternodes</h2>
                <p>
                    Setup your DFI Signal and get informed on new minted blocks of your masternode or get
                    <code>/stats</code> about your rewards.
                </p>
                <hr/>
                <h2>Wallet (coming soon)</h2>
                <p> You can setup your wallet addresses and get your daily, weekly or monthly wallet update or a
                    push on new incoming transactions.</p>
                <hr/>
                <h2>Commands</h2>
                <p>These commands are available at the moment:</p>

                <span class="overline overline--blue">list masternodes</span>
                <p class="code">&#47;list</p>
                <p>
                    Lists your masternodes with a couple of statistics.
                </p>

                <span class="overline overline--blue">stats of your mn rewards</span>
                <p class="code">&#47;stats</p>
                <p>
                    Shows a couple of more detailed statistics of your
                    masternodes.
                </p>

                <span class="overline overline--blue">link a masternode</span>
                <p class="code">
                    &#47;link_mn &lt;OWNER_ADDRESS&gt;
                </p>
                <p>
                    Link a masternode to your account. While the setup
                    you can decide if you wish to get automatic signals
                    for this masternode.
                </p>

                <span class="overline overline--blue">unlink a masternode</span>
                <p class="code">
                    &#47;unlink_mn &lt;OWNER_ADDRESS&gt;
                </p>
                <p>Removes the masternode from your account.</p>

                <span class="overline overline--blue">sync with masternode monitor</span>
                <p class="code">&#47;sync</p>
                <p>
                    Use the masternode sync with
                    <a href="https://www.defichain-masternode-monitor.com/" target="_blank">Masternode Monitor</a>.
                    All your masternodes from the Masternode Monitor
                    are synced. While the setup you can decide if you
                    wish to get automatic signals for these masternodes.
                </p>

                <span class="overline overline--blue">change your masternode monitor sync key</span>
                <p class="code">&#47;sync_key_changed</p>
                <p>
                    If you updated your sync key, you can use this command.
                </p>

                <span class="overline overline--blue">stop the masternode monitor sync</span>
                <p class="code">&#47;sync_disable</p>
                <p>
                    Stop the masternode sync with the Masternode Monitor
                </p>

                <span class="overline overline--blue">interrupt current conversation</span>
                <p class="code">&#47;stop</p>
                <p>
                    If you wish to stop a current conversation with the bot.
                </p>

                <span class="overline overline--blue">reset all your data</span>
                <p class="code">&#47;reset</p>
                <p>
                    Removes all stored data from your account. This
                    action cannot be undone.
                </p>
            </div>
        </div>
    </section>
</x-base>
