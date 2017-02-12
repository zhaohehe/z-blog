<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="initial-scale=1, maximum-scale=1, minimum-scale=1, width=device-width, user-scalable=no">
    <meta name="renderer" content="webkit">
    <meta name="theme-color" content="#ffffff">

    <script type="text/javascript">
        <?php
        $data = [
                'csrfToken' => csrf_token(),
                'blogTitle' => $blogTitle,
        ];
        echo 'window.Laravel = ' . json_encode($data);
        ?>
    </script>

</head>
<body>
<div id="app">
    @yield('content')
</div>
</body>
@yield('style')
<script src="{{url(elixir("js/app.js"))}}"></script>
</html>