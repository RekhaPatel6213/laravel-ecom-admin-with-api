<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <meta http-equiv="X-UA-Compatible" content="ie=edge" />
        <title>{{ getSettingData("company_name") }} OTP</title>
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap"rel="stylesheet" />
    </head>

    <body style="margin: 0; font-family: 'Poppins', sans-serif; background: #ffffff; font-size: 14px;">
        <div style="max-width: 680px;margin: 0 auto;padding: 45px 30px 60px;background: #f4f7ff;background-image: url('frontend/images/email-banner-img.jpg');background-repeat: no-repeat;background-size: 800px 280px;background-position: top center;font-size: 14px;color: #434343;border-radius: 10px;">
            <header>
                <table style="width: 100%;">
                    <tbody>
                        <tr style="height: 0;">
                            <td style="text-align: center;">
                                <a href="{{-- route('home') --}}" target="_blank"><img alt="" src="{{ asset('storage/'.getSettingData('company_logo')) }}" height="60px" /></a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </header>
            <main>
                <div style="margin: 0; margin-top: 20px; padding: 30px; background: #ffffff; border-radius: 30px; text-align: left;">
                    <div style="width: 100%; max-width: 489px; margin: 0 auto;">
                        <p style="margin: 0;margin-top: 17px;font-size: 17px;font-weight: 600;color: #e31e2a;margin-bottom: 10px;">
                            Dear {{$orderData->firstname.' '.$orderData->lastname}},
                        </p>
                        <h1 style="margin: 0; font-size: 20px; font-weight: 500; color: #1f1f1f;"> Your Order <strong style="color: #e31e2a;">#{{$orderData->order_no}}</strong> has canceled.</h1>
                        <p style="margin: 0; margin-top: 17px; font-weight: 500; letter-spacing: 0.56px;"><strong>Order ID:</strong> #{{$orderData->order_no}}</p>
                        <p style="margin: 0; margin-top: 17px; font-weight: 500; letter-spacing: 0.56px;"><strong>Order Status:</strong> Canceled</p>
                        <p style="margin: 0; margin-top: 17px; font-weight: 500; letter-spacing: 0.56px;"><strong>Order Comments:</strong> Canceled by customer end.</p>
                    </div>
                </div>
            </main>
        </div>
    </body>
</html>
