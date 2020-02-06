<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
     <title>{{ $deck['title'] }}</title>
     <link rel="stylesheet" href="{{storage_path('app/public/pdf/bootstrap.min.css')}}">
    <style type="text/css">
        @font-face {
            font-family: 'DejaVu Sans';
            font-style: normal;
            font-weight: normal;
            src: url('{{storage_path('app/public/pdf/dejavu-sans.ttf')}}') format('truetype');
        }

        @page {
            margin: 0px;
            margin-left: 20px;
            margin-right: 20px;
        }

        body {
            font-family: 'DejaVu Sans'!important;
        }

        hr {
            border: 1px solid grey;
        }
        .front {
    transform: rotate(180deg);
        }

        .font-bold {
  font-weight: bold;
}

.bordered {
    border: 2px dashed grey!important;
}

.custom-col {
    margin: 0px!important;
    padding: 0px!important;
}

.caption { margin: 0px!important;
padding: 0px!important;
}
.page-break { page-break-after: always; }


    </style>
</head>

<body>
@foreach(array_chunk($deck['cards'], 2) as $cards_chunk)
    <div class="row">
        @foreach($cards_chunk as $card)
            <div class="col-xs-6 custom-col">
                <div class="thumbnail bordered">
                    <div class="caption front">
                        <p class="text-center font-bold">{{$card['question']}}</p>
                        <p class="text-center">{{$card['example_question']}}</p>
                </div>
            <hr>
                <div class="caption back">
                    <p class="text-center font-bold">{{$card['answer']}}</p>
                    <p class="text-center">{{$card['example_answer']}}</p>
            </div>
                </div>
            </div>
        @endforeach
    </div>
    @if ($loop->iteration % 2 == 0)
    <div class="page-break"></div>
    @endif
@endforeach


</body>

</html>
