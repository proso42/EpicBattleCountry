<!DOCTYPE html>
<html>
<head>
        <meta charset="utf8">
        <title>EpicBattleCorp</title>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
        <script src="https://kit.fontawesome.com/345fcffdfe.js" crossorigin="anonymous"></script>
        <link rel="stylesheet" type="text/css" href="css/style.css"/>
    </head>
    <body>
        @include('default')
            <div class="offset-lg-0 offset-md-2 offset-sm-1 offset-1 col-lg-9 col-md-7 col-sm-10 col-10 center-win" style="margin-top: 50px; padding-right: 10px;">
                <div class="row" style="align-items: baseline;line-height: 31px;">
                    <select class="admin-select offset-lg-2 offset-md-2 offset-sm-2 offset-2 col-lg-2 col-md-2 col-sm-2 col-2">
                        <optgroup style="background-color: white" label="@lang('common.users')">
                            @foreach ($all_users as $user)
                                <option class="admin-option">{{ $user->login }}</option>
                            @endforeach
                        </optgroup>
                    </select>
                </div>
            </div>
        </div>
        <input id="_token" name="_token" type="hidden" value="{{csrf_token()}}">
    </body>
</html>