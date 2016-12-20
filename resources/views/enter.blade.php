<?php
header('Access-Control-Allow-Origin: *');
?>
        <!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>JS Frontend</title>
    <link rel="stylesheet" href="{{ elixir('css/all.css') }}">
    <style>
        .panel-heading {
            cursor: pointer;
        }
        .spacer {
            margin: 20px 0;
        }
    </style>
</head>
<body>
<div class="container" id="app">

    <div class="vertical-center full-height" v-show="show.load">
        <div class="sk-folding-cube">
            <div class="sk-cube1 sk-cube"></div>
            <div class="sk-cube2 sk-cube"></div>
            <div class="sk-cube4 sk-cube"></div>
            <div class="sk-cube3 sk-cube"></div>
        </div>
    </div>

    <div class="vertical-center full-height" :class="{ 'animated zoomIn': show.form, 'hidden': !show.form }">
        <div class="col-md-12">
            <h3>Enter your URL</h3>
            {!! Form::open(['route' => 'requestReport']) !!}
            <div class="row">
                <div class="col-md-11">
                    <input class="form-control" placeholder="https://yoururl.com" v-model="formRequest.url">
                </div>
                <div class="col-md-1">
                    <button class="btn btn-primary form-control">SCAN!</button>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3">
                    <div class="checkbox">
                        <textarea v-model="formRequest.whitelist" rows="6" placeholder="sub1.example.com
sub2.example.com" class="form-control"></textarea>
                    </div>

                </div>
                <div class="col-md-3">
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" v-model="toggleScans">
                            Select all
                        </label>
                    </div>
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" v-model="formRequest.scan.images">
                            Include <b>img</b>-Tags
                        </label>
                        <label>
                            <input type="checkbox" v-model="formRequest.scan.scripts">
                            Include <b>script</b>-Tags
                        </label>
                        <label>
                            <input type="checkbox" v-model="formRequest.scan.links">
                            Include <b>link</b>-Tags
                        </label>
                        <label>
                            <input type="checkbox" v-model="formRequest.scan.media">
                            Include <b>audio</b>- and <b>video</b>-Tags
                        </label>
                        <label>
                            <input type="checkbox" v-model="formRequest.scan.area">
                            Include <b>area</b>-Tags
                        </label>
                        <label>
                            <input type="checkbox" v-model="formRequest.scan.frames">
                            Include <b>iframe</b>- and <b>frame</b>-Tags
                        </label>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" v-model="formRequest.doNotCrawl">
                            Do NOT crawl
                        </label>
                    </div>
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" v-model="formRequest.limitOn">
                            Limit scan
                        </label>
                        <span v-show="formRequest.limitOn">
                                <br><input class="form-control" v-model="formRequest.limit">
                            </span>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" v-model="formRequest.ignoreTLS">
                            Ignore SSL/TLS certificate errors
                        </label>
                    </div>
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" v-model="formRequest.proxy">
                            Use a proxy
                        </label>
                        <span v-show="formRequest.proxy">
                            <br><input type="text" class="form-control" v-model="formRequest.proxyAddress">
                        </span>
                    </div>
                </div>
            </div>
            <div class="row">

            </div>
            {!! Form::close() !!}
        </div>
    </div>

</div>
<script src="{{ elixir('js/app.js') }}"></script>
</body>
</html>
