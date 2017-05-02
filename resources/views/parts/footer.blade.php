<footer class="c-footer">
    <div class="container">
        <div class="inner">
            <ul class="list">
                <li class="item">
                    <a href="{{ route('about-faculty') }}" class="link">{{ _('Про факультет') }}</a>
                </li>
                <li class="item">
                    <a href="{{ route('pages.view', ['staticPage' => 'contacts']) }}" class="link">{{ _('Контакти') }}</a>
                </li>
                <li class="item">
                    <a href="{{ config('remote-urls.for-applicant') }}" class="link">{{ _('Абітурієнту') }}</a>
                </li>
            </ul>

            <ul class="list o-social u-mt-xs u-mb-xs">
                <li class="item">
                    <a href="{{ setting('facebook') }}" target="_blank" class="link fb" aria-label="Facebook">
                        <span><i class="icon i-fb" aria-hidden="true"></i></span>
                        <span><i class="icon i-fb-light" aria-hidden="true"></i></span>
                    </a>
                </li>
                <li class="item">
                    <a href="{{ setting('vkontakte') }}" target="_blank" class="link vk" aria-label="Вконтакте">
                        <span><i class="icon i-vk" aria-hidden="true"></i></span>
                        <span><i class="icon i-vk-light" aria-hidden="true"></i></span>
                    </a>
                </li>
                <li class="item">
                    <a href="{{ setting('twitter') }}" target="_blank" class="link tw" aria-label="Twitter">
                        <span><i class="icon i-tw" aria-hidden="true"></i></span>
                        <span><i class="icon i-tw-light" aria-hidden="true"></i></span>
                    </a>
                </li>
            </ul>

            <div class="copyright">
                <span class="c-copy">© 2017 {{ _('Факультет інформаційних систем фізики та математики') }}</span>
                <span class="created">
                    {{ _('зроблено в ') }}
                    <a href="https://www.ideil.com/" target="_blank" class="link">
                        {{ _('ідейл') }}
                    </a>
                </span>
            </div>

        </div>
    </div>
</footer>
