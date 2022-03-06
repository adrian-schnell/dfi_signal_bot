<footer class="footer">
    <div class="container">
        <div class="row">
            <div class="col-sm">
                <h2>contact</h2>
                <p>
                    <strong><a href="{{ getTelegramGroupLink() }}"
                               target="_blank">&commat;{{ config('telegram.names.support_group') }}</a></strong> on
                    Telegram
                </p>
                <p>
                    <strong>buy me a coffee</strong> <br>
                    <small>
                        - DFI: dRq3HUxyZv46ZNCEpBiTKpKKdJr3iy3m2U <br>
                        - Get $30 extra on <a href="https://app.cakedefi.com?ref=472815" target="_blank">Cake DeFi
                            signup</a>
                    </small>
                </p>
            </div>
            <div class="col-sm">
                <h2>legal</h2>
                <p>
                    This application/website is is operated by:<br/>
                    Adrian Schnell
                </p>
                <a href="{{ route('learn_more') }}">learn more about this application</a>
                <p>
                    <small>
                        Responsible for the contents in the sense of
                        § 5 TMG § 18 Abs. 2 MStV: Adrian Schnell
                    </small>
                </p>
            </div>
        </div>
        <div class="footer__icon">
            <a href="{{ config('app.url') }}">
                <img src="{{ asset('images/logo.svg') }}" alt="DFI Signal logo" width="512" height="173"/>
            </a>
        </div>
    </div>
</footer>
