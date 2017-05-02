<header class="c-banner js-banner">
    <div class="c-banner__top">
        <div class="container">
            <div class="inner">

                <a href="#" class="c-hamburger js-hamburger" aria-label="Меню">
                    <span aria-hidden="true"></span>
                </a>

                <a href="{{ action('HomeController@getIndex') }}" class="logo">{{ _('СНУ ФІСФМ') }}</a>

                <div class="c-search js-search">
                    <form action="{{ action('SearchController@getSearch') }}" type="GET">
                        <div class="form-group">
                            <input value="{{ Request::get('search') }}" name="search" type="text" class="form-control js-search-input" placeholder="Пошук">
                        </div>

                        <button type="submit" class="submit js-search-btn" aria-label="Пошук">
                            <i class="icon i-search" aria-hidden="true"></i>
                        </button>
                    </form>
                </div>

            </div>
        </div>
    </div>

    <div class="c-banner__main">
        <div class="container">
            <div class="inner">

                <nav>
                    <ul class="list navigation js-nav">
                        <li class="item">
                            <a href="{{ route('news.list') }}" class="link">{{ _('Новини') }}</a>
                        </li>

                        <li class="item">
                            <span class="link is-opening">{{ _('Навчання') }}</span>

                            <ul class="list">
                                <li class="item">
                                    <a href="{{ route('about-faculty') }}" class="link">{{ _('Про факультет') }}</a>
                                </li>

                                <li class="item">
                                    <a href="{{ route('chairs.list') }}" class="link">{{ _('Кафедри') }}</a>
                                </li>

                                <li class="item">
                                    <a href="{{ route('pages.view', ['staticPage' => 'curricula']) }}" class="link">{{ _('Навчальні плани') }}</a>
                                </li>
                            </ul>
                        </li>

                        <li class="item">
                            <span class="link is-opening">{{ _('Наукова діяльність') }}</span>

                            <ul class="list">
                                {{-- <li class="item">
                                    <a href="{{ route('publications.category', 'research-activities') }}" class="link">{{ _('Публікації') }}</a>
                                </li> --}}

                                <li class="item">
                                    <a href="{{ route('publications.category', 'conference') }}" class="link">{{ _('Конференції') }}</a>
                                </li>

                                <li class="item">
                                    <a href="{{ route('laboratories.list') }}" class="link">{{ _('Лабораторії') }}</a>
                                </li>
                            </ul>
                        </li>

                        <li class="item">
                            <span class="link is-opening">{{ _('Студенту') }}</span>

                            <ul class="list">
                                <li class="item">
                                    <a href="{{ route('timetable.lessons') }}" class="link">{{ _('Розклад занять') }}</a>
                                </li>
                                {{-- <li class="item"><a href="#" class="link">{{ _('Розклад дзвінків') }}</a></li> --}}

                                <li class="item">
                                    <a href="{{ route('pages.view', 'training-schedule') }}" class="link">{{ _('Графік навчального процесу') }}</a>
                                </li>

                                <li class="item">
                                    <a href="{{ route('timetable.examinations') }}" class="link">{{ _('Розклад сесії') }}</a>
                                </li>

                                <li class="item">
                                    <a href="{{ route('pages.view' , 'curators-list') }}" class="link">{{ _('Список кураторів груп') }}</a>
                                </li>

                                <li class="item">
                                    <a href="{{ route('students.fragment', 'leisure') }}" class="link">{{ _('Дозвілля') }}</a>
                                </li>

                                <li class="item">
                                    <a href="{{ route('students.fragment', 'student-government') }}" class="link">{{ _('Студентське самоврядування') }}</a>
                                </li>
                            </ul>
                        </li>

                        @if (config('remote-urls.for-applicant'))
                            <li class="item">
                                <a href="{{ config('remote-urls.for-applicant') }}" class="link">{{ _('Абітурієнту') }}</a>
                            </li>
                        @endif

                        <li class="item">
                            <a href="{{ route('pages.view', ['staticPage' => 'contacts']) }}" class="link">{{ _('Контакти') }}</a>
                        </li>
                    </ul>
                </nav>

            </div>
        </div>
    </div>
</header>
