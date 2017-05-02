@extends('layouts.base')

@section('title')
    {{ '404' }}
@endsection

@section('content')
    <main>
        <div class="container">
            <div class="row js-parent">
                <div class="col-md-12">
                    <article>

                        <h1 class="c-title">{{ '404 Сторінка не знайдена' }}</h1>

                        <div class="s-content">
                            <p>{{ _('Ми не можемо знайти сторінку, яку ви шукаєте') }}</p>
                        </div>

                    </article>
                </div>
            </div>
        </div>
    </main>
@endsection
