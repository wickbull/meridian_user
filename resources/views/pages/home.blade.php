<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Document</title>
    <style>
        .content {
            background: yellow;
        }

        .photo {
            margin: 0;
        }

        .right{
            float: right;
        }

        .margin_5px{
            margin-left: 5px;
        }

        .margin_top_15px{
            margin-top: 15px;
        }

        .margin_bottom_15px{
            margin-bottom: 15px;
        }

        .margin_top_30px{
            margin-top: 30px;
        }

        .margin_bottom_30px{
            margin-bottom: 30px;
        }

        .margin_top_60px{
            margin-top: 60px;
        }

        .margin_bottom_60px{
            margin-bottom: 60px;
        }

        .margin_top_5px{
            margin-top: 5px;
        }

        .margin_bottom_5px{
            margin-bottom: 5px;
        }

        .one{
            background: black;
        }

        .two{
            background: red;
        }

        .text{
            font-size: 2em;
            font-family: Times New Roman;
            color: black;
        }

        .tnr{
            font-family: Times New Roman;   
        }

        .high_register{
            text-transform: uppercase;
        }

        .size{
            font-size: 0.8em;
            font-family: Times New Roman;
            font-weight: bold;
            color: black;
        }

        .smth{
            font-family: Comic Sans MS;
        }

        .high_register:hover{
            color: #B30A08;
        }

        .red{
            color: #B40707;
        }

        .icon{
            width: 8%;
            border: 1px solid black;
            border-radius: 3px;
            padding-top: 2px;
            padding-bottom: 2px;
            padding-left: 10px;
            padding-right: 10px;
            margin-right: 15px;
        }

        .lang{
            float: right;
        }

        .grayscale{
            filter: grayscale(100%);
            transition: 1s;
        }

        .grayscale:hover{
            filter: grayscale(20%);
        }

        .nograyscale{
            filter: grayscale(40%);
            transition: 1s;
        }

        .nograyscale:hover{
            filter: grayscale(0%);
        }

        .info{
            width: 100%;
        }
        
        .bottom_first{
            width: 100%;
            background: #2B2B2B;
            float: left;
        }

        .bottom_second{
            width: 100%;
            background: #313131;
            float: left;
        }

        .white{
            color: white;
        }

        .padding_top_bottom_5px{
            padding-top: 5px;
            padding-bottom: 5px;
        }

        .padding_top_bottom_15px{
            padding-top: 15px;
            padding-bottom: 15px;
        }

        .butt_buy{
            border: 2px solid white;
            border-radius: 50px;
        }

    </style>
    <link href="{{ static_asset('src/css/bootstrap.css') }}" rel="stylesheet">
</head>
<body>

    <div class="content">
        <div class="col-md-12 margin_top_15px margin_bottom_5px">
            <div class="col-md-9">
                <a class="right" href="">
                    <i class="fa fa-search fa-2x red" aria-hidden="true"></i>
                </a>
                <div class="lang">
                    <a  href="">
                        <img class="icon lang grayscale" src="{{ static_asset('src/img/en.png') }}" alt="">
                    </a>
                    <a  href="">
                        <img class="icon lang nograyscale" src="{{ static_asset('src/img/ua.png') }}" alt="">
                    </a>
                </div>
                
            </div>
        </div>
        <div class="col-md-12 margin_bottom_15px margin_top_5px">
            <center>
                <a class="margin_5px high_register size" href="">{{ _('Команда') }}</a>
                <a class="margin_5px high_register size" href="">{{ _('Фестиваль') }}</a>
                <a class="margin_5px high_register size" href="">{{ _('Анонси') }}</a>
                <a class="margin_5px high_register size" href="">{{ _('Новини') }}</a>
                <a class="margin_5px high_register size" href="">{{ _('Галерея') }}</a>
                <a class="margin_5px high_register size" href="">{{ _('Купити квитки') }}</a>
                <a class="margin_5px high_register size" href="">{{ _('Інфо.туристу') }}</a>
                <a class="margin_5px high_register size" href="">{{ _('Партнери') }}</a>
                <a class="margin_5px high_register size" href="">{{ _('Контакти') }}</a>
            </center>
        </div>
    </div>

    <div class="photo">
        <img width="100%" height="400px" src="{{ static_asset('src/img/yIn6Zuk.jpg') }}" alt="">
    </div>

    <div class="content">
        <div class="col-md-2"></div>
        <div class="col-md-8">
            <div class="col-md-8 margin_top_60px text">

                <div class="col-md-12" style="border-bottom: 1px solid black">
                    <div style="border-bottom: 2px solid red; width: 17.5%">
                        {{ _('Новини') }}
                    </div>
                </div>
                
                
                @foreach($news as $key => $new_s)
                    <div class="col-md-12 margin_bottom_15px" style="border-top: 1px solid black">
                        <div class="col-md-5 margin_top_15px">
                            {{ dump($key) }}
                            <img class="info" src="{{ $new_s->getImage() }}" alt="">
                        </div>
                        <div class="col-md-7 margin_top_15px smth" style="font-size: 0.55em;">
                            {{ dump($new_s->getViewUrl()) }}
                            <a href="{{ $new_s->getViewUrl() }}">{{ $new_s->title }}</a>
                        </div>
                        <div class="col-md-7 margin_top_15px smth" style="font-style: italic; font-size: 0.4em;">
                            {{ $new_s->publish_at->format('d.m.y | H:i') }}
                        </div>
                        <div class="col-md-7 margin_top_15px tnr" style="font-size: 0.5em;">
                            {!! $new_s->body !!}
                        </div>
                    </div>
                @endforeach

            </div>
            <div class="col-md-4 margin_top_60px text">
                <div class="col-md-12" style="border-bottom: 1px solid black">
                    <div style="border-bottom: 2px solid red; width: 40%">
                        {{ _('Анонси') }}
                    </div>
                </div>

                @foreach($annonces as $key => $annonce)
                    <div class="col-md-12" style="padding: 10px; border-top: 1px solid black;">
                        {{ dump($key) }}
                        <div class="col-md-3 tnr smth" style="font-weight: bold; color: red; padding-bottom: 5px; padding-top: 5px; font-size: 0.45em; border: 1px solid black; border-radius: 3px;">
                            <center> {{ $annonce->event_at->format('d.m') }} </center>
                            <center> {{ $annonce->event_at->format('H:i') }} </center>
                        </div>
                        <div class="col-md-7 smth" style="font-size: 0.55em;">
                        {{ dump($annonce->getViewUrl()) }}
                        <a href="{{ $annonce->getViewUrl() }}">{{ $annonce->title }}</a></div>
                    </div>
                @endforeach

                <center><a href="" style="border-radius: 50px;" class="btn btn-danger">{{ _('Усі анонси') }}</a></center>
            </div>

            <div class="col-md-8 margin_top_30px">
                <div class="col-md-12" style="padding: 10px; border-top: 1px solid black"></div>
                <center><a href="" style="border-radius: 50px;" class="btn btn-danger">{{ _('Усі новини') }}</a></center>
            </div>

            <div class="col-md-12 margin_top_60px text">
                <div class="col-md-12" style="border-bottom: 1px solid black">
                    <div style="border-bottom: 2px solid red; width: 11.5%">
                        {{ _('Галерея') }}
                    </div>
                </div>

                <div class="col-md-12 margin_bottom_15px">
                    <div class="col-md-6">
                        <div class="col-md-12 margin_top_30px">{{ _('Фото') }}</div>
                        <div class="col-md-12">

                            <div class="col-md-4" style="background: red">
                                <center>1</center>
                            </div>
                            <div class="col-md-4" style="background: green">
                                <center>2</center>
                            </div>
                            <div class="col-md-4" style="background: blue">
                                <center>3</center>
                            </div>
                            <div class="col-md-8" style="background: yellow">
                                <center>4</center>
                                <center>4</center>
                            </div>
                            <div class="col-md-4" style="background: brown">
                                <center>5</center>
                            </div>
                            <div class="col-md-4" style="background: lime">
                                <center>6</center>
                            </div>


                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="col-md-12 margin_top_30px">{{ _('Відео') }}</div>
                        <div class="col-md-12">
                            
                            <div class="col-md-8" style="padding:0px;">

                                <div class="col-md-12" style="background: red">
                                    <center>1</center>
                                    <center>1</center>
                                </div>
                                
                                <div class="col-md-12" style="background: yellow">
                                    <center>4</center>
                                </div>

                            </div>
                            <div class="col-md-4" style="padding:0px;">
                                <div class="col-md-12" style="background: green">
                                    <center>2</center>
                                </div>
                                <div class="col-md-12" style="background: blue">
                                    <center>3</center>
                                    <center>3</center>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <div class="bottom_first">
        <center class="padding_top_bottom_5px">
            <a class="margin_5px high_register size white" href="">{{ _('Команда') }}</a>
            <a class="margin_5px high_register size white" href="">{{ _('Фестиваль') }}</a>
            <a class="margin_5px high_register size white" href="">{{ _('Анонси') }}</a>
            <a class="margin_5px high_register size white" href="">{{ _('Новини') }}</a>
            <a class="margin_5px high_register size white" href="">{{ _('Галерея') }}</a>
            <a class="margin_5px high_register size white" href="">{{ _('Купити квитки') }}</a>
            <a class="margin_5px high_register size white" href="">{{ _('Інфо.туристу') }}</a>
            <a class="margin_5px high_register size white" href="">{{ _('Партнери') }}</a>
            <a class="margin_5px high_register size white" href="">{{ _('Контакти') }}</a>
        </center>
    </div>
    <div class="bottom_second">
        <div class="col-md-4">
            <center style="padding-top: 25px; color: white;">
                © 2017 Усі права захищені        
            </center>
             
        </div>
        <div class="col-md-4">
            <center class="padding_top_bottom_15px">
                <a class="btn btn-danger" style="border-radius: 50px; border: solid white;" href="">Купити квитки</a>
            </center>
        </div>
        <div class="col-md-4">
            <center style="padding-top: 15px">
                <a href=""><img width="8%" src="{{ static_asset('src/img/social/instagram.png') }}" alt=""></a>
                <a href=""><img width="8%" src="{{ static_asset('src/img/social/odnoklassniki.png') }}" alt=""></a>
                <a href=""><img width="9.5%" src="{{ static_asset('src/img/social/vkontakte.png') }}" alt=""></a>
                <a href=""><img width="9.1%" src="{{ static_asset('src/img/social/facebook.png') }}" alt=""></a>
            </center>
        </div>
    </div>


</body>
</html>