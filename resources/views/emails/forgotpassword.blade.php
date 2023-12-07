<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title></title>
</head>
<style>
    body {
        font-size: 14px;
        font-family: Arial, Helvetica, sans-serif;
    }
</style>

<body>
    <table border="0" cellpadding="0" cellspacing="0" width="60%" style="margin:0 auto;border:1px solid rgba(0, 0, 0, 0.2);padding:20px;">
        <tr style="border:0">
            <td style="text-align:center">
                <a href="{{ url('/') }}" target="_blank"><img src="https://admin.rediscovertv.com/site_assets/images/template/logo.png" alt="" style="width: 120px;"></a>
            </td>
        </tr>
        <tr style="border:0">
            <td>
                Hi {{$name}},
            </td>
        </tr>
        <tr>
            <td style="padding: 20px 0 30px 0;line-height:22px;">
                You have requested a password reset for your account. To help you regain access, we've generated a temporary password for you:
            </td>
        </tr>

        <tr>
            <td style="padding: 10px 0 30px 0;line-height:22px;">
                Temporary Password: <b>{{$password}}</b>
            </td>
        </tr>
                </br>


        <tr>
            <td style="padding: 10px 0 30px 0;line-height:22px;">
                Please use the temporary password to log in to your account. After logging in, we strongly recommend changing your password to something more secure by going to your account settings.
            </td>
        </tr>
        </br>

        <tr>
            <td>
            If you did not request a password reset, please disregard this email and contact our support team immediately at support@rediscovertelevision.com.
            </td>
        </tr>
        <tr>
            <td style="line-height:20px">
                Thanks!
                <br />- {{$site_name}}
            </td>
        </tr>
    </table>
    <p style="font-size: 13px;text-align: right;margin-top: 10px;position: relative;right: 40.5%;">&copy; {{$site_name}}</p>
</body>

</html>
