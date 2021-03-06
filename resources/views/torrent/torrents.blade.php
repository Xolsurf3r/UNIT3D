@extends('layout.default')

@section('title')
    <title>{{ trans('torrent.torrents') }} - {{ config('other.title') }}</title>
@endsection

@section('meta')
    <meta name="description" content="{{ 'Torrents ' . config('other.title') }}">
@endsection

@section('breadcrumb')
    <li class="active">
        <a href="{{ route('torrents') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">{{ trans('torrent.torrents') }}</span>
        </a>
    </li>
@endsection

@section('content')
    <div class="container box">

        <div class="text-center">
            <h3 class="filter-title">Current Filters</h3>
            <span id="filter-item-category"></span>
            <span id="filter-item-type"></span>
        </div>
        <hr>

        {{ Form::open(['action'=>'TorrentController@torrents','method'=>'get','class'=>'form-horizontal form-condensed form-torrent-search form-bordered']) }}

        <div class="form-group">
            <label for="name" class="col-sm-1 label label-default">Name</label>
            <div class="col-sm-9">
                {{ Form::text('search',null,['id'=>'search','placeholder'=>'Name / Title','class'=>'form-control']) }}
            </div>
        </div>

        <div class="form-group">
            <label for="name" class="col-sm-1 label label-default">Description</label>
            <div class="col-sm-9">
                {{ Form::text('description',null,['id'=>'description','placeholder'=>'Mediainfo or Description','class'=>'form-control']) }}
            </div>
        </div>

        <div class="form-group">
            <label for="uploader" class="col-sm-1 label label-default">Uploader</label>
            <div class="col-sm-9">
                {{ Form::text('uploader',null,['id'=>'uploader','placeholder'=>'Uploader Username','class'=>'form-control']) }}
            </div>
        </div>

        <div class="form-group">
            <label for="imdb" class="col-sm-1 label label-default">Number</label>
            <div class="col-sm-2">
                {{ Form::text('imdb',null,['id'=>'imdb','placeholder'=>'IMDB #','class'=>'form-control']) }}
            </div>
            <div class="col-sm-2">
                {{ Form::text('tvdb',null,['id'=>'tvdb','placeholder'=>'TVDB #','class'=>'form-control']) }}
            </div>
            <div class="col-sm-2">
                {{ Form::text('tmdb',null,['id'=>'tmdb','placeholder'=>'TMDB #','class'=>'form-control']) }}
            </div>
            <div class="col-sm-2">
                {{ Form::text('mal',null,['id'=>'mal','placeholder'=>'MAL #','class'=>'form-control']) }}
            </div>
        </div>

        <div class="form-group">
            <label for="category" class="col-sm-1 label label-default">Category</label>
            <div class="col-sm-10">
                @foreach($repository->categories() as $id => $category)
                    <span class="badge-user">
                        {{ Form::checkbox($category,$id,false,['class'=>'category']) }}
                        {{ Form::label($category,$category,['class'=>'inline']) }}
                    </span>
                @endforeach
            </div>
        </div>

        <div class="form-group">
            <label for="type" class="col-sm-1 label label-default">Type</label>
            <div class="col-sm-10">
                @foreach($repository->types() as $id => $type)
                    <span class="badge-user">
                        {{ Form::checkbox($type,$type,false,['class'=>'type']) }}
                        {{ Form::label($type,$type,['class'=>'inline']) }}
                    </span>
                @endforeach
            </div>
        </div>

        <div class="form-group">
            <label for="type" class="col-sm-1 label label-default">Discount</label>
            <div class="col-sm-10">
                <span class="badge-user">
                    <label class="inline">
                        {{ Form::checkbox('freeleech','1',false,['id'=>'freeleech']) }} <span class="{{ config('other.font-awesome') }} fa-star text-gold"></span> 100% Free
                    </label>
                </span>
                <span class="badge-user">
                    <label class="inline">
                        {{ Form::checkbox('doubleupload','1',false,['id'=>'doubleupload']) }}<span class="{{ config('other.font-awesome') }} fa-gem text-green"></span> Double Upload
                    </label>
                </span>
                <span class="badge-user">
                    <label class="inline">
                        {{ Form::checkbox('featured','1',false,['id'=>'featured']) }}<span class="{{ config('other.font-awesome') }} fa-certificate text-pink"></span> Featured Torrent
                    </label>
                </span>
            </div>
        </div>

        <div class="form-group">
            <label for="type" class="col-sm-1 label label-default">Special</label>
            <div class="col-sm-10">
                <span class="badge-user">
                    <label class="inline">
                        {{ Form::checkbox('stream','1',false,['id'=>'stream']) }} <span class="{{ config('other.font-awesome') }} fa-play text-red"></span> Stream Optimized
                    </label>
                </span>
                <span class="badge-user">
                    <label class="inline">
                        {{ Form::checkbox('highspeed','1',false,['id'=>'highspeed']) }} <span class="{{ config('other.font-awesome') }} fa-tachometer text-red"></span> High Speeds
                    </label>
                </span>
                <span class="badge-user">
                    <label class="inline">
                        {{ Form::checkbox('sd','1',false,['id'=>'sd']) }} <span class="{{ config('other.font-awesome') }} fa-ticket text-orange"></span> SD Content
                    </label>
                </span>
                <span class="badge-user">
                    <label class="inline">
                        {{ Form::checkbox('internal','1',false,['id'=>'internal']) }} <span class="{{ config('other.font-awesome') }} fa-magic" style="color: #BAAF92"></span> Internal
                    </label>
                </span>
            </div>
        </div>

        <div class="form-group">
            <label for="type" class="col-sm-1 label label-default">Health</label>
            <div class="col-sm-10">
                <span class="badge-user">
                    <label class="inline">
                        {{ Form::checkbox('alive','1',false,['id'=>'alive']) }} <span class="{{ config('other.font-awesome') }} fa-smile text-green"></span> Alive
                    </label>
                </span>
                <span class="badge-user">
                    <label class="inline">
                        {{ Form::checkbox('dying','1',false,['id'=>'dying']) }} <span class="{{ config('other.font-awesome') }} fa-meh text-orange"></span> Dying
                    </label>
                </span>
                <span class="badge-user">
                    <label class="inline">
                        {{ Form::checkbox('dead','0',false,['id'=>'dead']) }} <span class="{{ config('other.font-awesome') }} fa-frown text-red"></span> Dead
                    </label>
                </span>
            </div>
        </div>

        {{ Form::close() }}
        <hr>

        <div class="form-horizontal">
            <div class="form-group">
                {{ Form::label('sorting','Sort By:',['class'=>'control-label col-sm-2']) }}
                <div class="col-sm-2">
                    {{ Form::select('sorting',$repository->sorting(),'created_at',['class'=>'form-control','id'=>'sorting','placeholder'=>'Select for sorting']) }}
                </div>
                <div class="col-sm-3">
                    {{ Form::select('direction',$repository->direction(),'desc',['class'=>'form-control','id'=>'direction']) }}
                </div>
                {{ Form::label('qty','Display:',['class'=>'control-label col-sm-2']) }}
                <div class="col-sm-2">
                    {{ Form::select('qty',[25=>25,50=>50,100=>100],25,['class'=>'form-control','id'=>'qty']) }}
                </div>
            </div>
        </div>

    </div>

    <div class="container-fluid">
        <div class="block">
            <div style="float:left;">
                <strong>Extra:</strong>
                <a href="{{ route('categories') }}" class="btn btn-xs btn-primary">
                    <i class="{{ config('other.font-awesome') }} fa-file"></i> Torrent Categories
                </a>
                <a href="{{ route('catalogs') }}" class="btn btn-xs btn-primary">
                    <i class="{{ config('other.font-awesome') }} fa-book"></i> Torrent Catalogs
                </a>
            </div>
            <div style="float:right;">
                <strong>View:</strong>
                <a href="{{ route('torrents') }}" class="btn btn-xs btn-primary">
                    <i class="{{ config('other.font-awesome') }} fa-list"></i> Torrent List
                </a>
                <a href="{{ route('cards') }}" class="btn btn-xs btn-primary">
                    <i class="{{ config('other.font-awesome') }} fa-image"></i> Torrent Cards
                </a>
                <a href="{{ route('grouping_categories') }}" class="btn btn-xs btn-primary">
                    <i class="{{ config('other.font-awesome') }} fa-list"></i> Torrent Grouping
                </a>
            </div>
            <br>
        </div>
    </div>


    <div class="container-fluid">
        <div class="block">
            <div class="header gradient blue">
                <div class="inner_content">
                    <h1>
                        {{ trans('torrent.torrents') }}
                    </h1>
                </div>
            </div>
            <div id="result">
                @include('torrent.results')
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <div class="block">
            <div class="text-center">
                <strong>Activity Legend:</strong>
                <button class='btn btn-success btn-circle' type='button' data-toggle='tooltip' title=''
                        data-original-title='Currently Seeding!'>
                    <i class='{{ config("other.font-awesome") }} fa-arrow-up'></i>
                </button>
                <button class='btn btn-warning btn-circle' type='button' data-toggle='tooltip' title=''
                        data-original-title='Currently Leeching!'>
                    <i class='{{ config("other.font-awesome") }} fa-arrow-down'></i>
                </button>
                <button class='btn btn-info btn-circle' type='button' data-toggle='tooltip' title=''
                        data-original-title='Started Downloading But Never Completed!'>
                    <i class='{{ config("other.font-awesome") }} fa-hand-paper'></i>
                </button>
                <button class='btn btn-danger btn-circle' type='button' data-toggle='tooltip' title=''
                        data-original-title='You Completed This Download But Are No Longer Seeding It!'>
                    <i class='{{ config("other.font-awesome") }} fa-thumbs-down'></i>
                </button>
            </div>
        </div>
    </div>
@endsection

@section('javascripts')
    <script>
        var xhr = new XMLHttpRequest();

        function faceted(page) {
            var csrf = "{{ csrf_token() }}";
            var search = $("#search").val();
            var description = $("#description").val();
            var uploader = $("#uploader").val();
            var imdb = $("#imdb").val();
            var tvdb = $("#tvdb").val();
            var tmdb = $("#tmdb").val();
            var mal = $("#mal").val();
            var categories = [];
            var types = [];
            var sorting = $("#sorting").val();
            var direction = $("#direction").val();
            var qty = $("#qty").val();
            var categoryName = [];
            var typeName = [];
            var freeleech = (function () {
                if ($("#freeleech").is(":checked")) {
                    return $("#freeleech").val();
                }
            })();
            var doubleupload = (function () {
                if ($("#doubleupload").is(":checked")) {
                    return $("#doubleupload").val();
                }
            })();
            var featured = (function () {
                if ($("#featured").is(":checked")) {
                    return $("#featured").val();
                }
            })();
            var stream = (function () {
                if ($("#stream").is(":checked")) {
                    return $("#stream").val();
                }
            })();
            var highspeed = (function () {
                if ($("#highspeed").is(":checked")) {
                    return $("#highspeed").val();
                }
            })();
            var sd = (function () {
                if ($("#sd").is(":checked")) {
                    return $("#sd").val();
                }
            })();
            var internal = (function () {
              if ($("#internal").is(":checked")) {
                return $("#internal").val();
              }
            })();
            var alive = (function () {
                if ($("#alive").is(":checked")) {
                    return $("#alive").val();
                }
            })();
            var dying = (function () {
                if ($("#dying").is(":checked")) {
                    return $("#dying").val();
                }
            })();
            var dead = (function () {
                if ($("#dead").is(":checked")) {
                    return $("#dead").val();
                }
            })();
            $(".category:checked").each(function () {
                categories.push($(this).val());
                categoryName.push(this.name);
                $("#filter-item-category").html('<label class="label label-default">Category:</label>' + categoryName);
            });
            $(".type:checked").each(function () {
                types.push($(this).val());
                typeName.push(this.name);
                $("#filter-item-type").html('<label class="label label-default">Type:</label>' + typeName);
            });

            if (categories.length == 0) {
                $("#filter-item-category").html('')
            }
            if (types.length == 0) {
                $("#filter-item-type").html('')
            }

            if (xhr !== 'undefined') {
                xhr.abort();
            }

            xhr = $.ajax({
                url: 'filterTorrents',
                data: {
                    _token: csrf,
                    search: search,
                    description: description,
                    uploader: uploader,
                    imdb: imdb,
                    tvdb: tvdb,
                    tmdb: tmdb,
                    mal: mal,
                    categories: categories,
                    types: types,
                    freeleech: freeleech,
                    doubleupload: doubleupload,
                    featured: featured,
                    stream: stream,
                    highspeed: highspeed,
                    sd: sd,
                    internal: internal,
                    alive: alive,
                    dying: dying,
                    dead: dead,
                    sorting: sorting,
                    direction: direction,
                    page: page,
                    qty: qty
                },
                type: 'get',
                beforeSend: function () {
                    $("#result").html('<i class="{{ config('other.font-awesome') }} fa-spinner fa-spin fa-3x fa-fw"></i>')
                }
            }).done(function (e) {
              $data = $(e);
              $("#result").html($data);
            });
        }
    </script>
    <script>
        $(window).on("load", faceted())
    </script>
    <script>
        $("#search").keyup(function () {
            faceted();
        })
    </script>
    <script>
      $("#description").keyup(function () {
        faceted();
      })
    </script>
    <script>
        $("#uploader").keyup(function () {
            faceted();
        })
    </script>
    <script>
        $("#imdb").keyup(function () {
            faceted();
        })
    </script>
    <script>
        $("#tvdb").keyup(function () {
            faceted();
        })
    </script>
    <script>
        $("#tmdb").keyup(function () {
            faceted();
        })
    </script>
    <script>
        $("#mal").keyup(function () {
            faceted();
        })
    </script>
    <script>
        $(".category,.type").on("click", function () {
            faceted();
        });
    </script>
    <script>
        $("#freeleech,#doubleupload,#featured,#stream,#highspeed,#sd,#internal,#alive,#dying,#dead").on("click", function () {
            faceted();
        });
    </script>
    <script>
        $("#sorting,#direction,#qty").on('change', function () {
            faceted();
        });
    </script>
    <script>
      $(document).on('click', '.pagination a', function (e) {
        e.preventDefault();
        var url = $(this).attr('href');
        var page = url.split('page=')[1];
        window.history.pushState("", "", url);
        faceted(page);
      })
    </script>
    <script>
      $(document).ajaxComplete(function () {
        $('[data-toggle="tooltip"]').tooltip();
      });
    </script>
@endsection
