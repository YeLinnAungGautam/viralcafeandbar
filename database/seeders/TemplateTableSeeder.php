<?php

namespace Database\Seeders;

use App\Models\Template;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TemplateTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'title'   => 'otp',
                'content' => '<h3>Your OTP Code&nbsp;</h3><p>Hello, Here is your one-time password (OTP) code:&nbsp;{code}&nbsp;</p><p>Please use this code to complete your verification process.&nbsp;</p><p>Thank you for using our service!</p>',
            ],
            [
                'title'   => 'Order cancelled',
                'content' => '<p>Hey, {customer}&nbsp;</p><p><span style="color:red;">Your order # {order_code} has been cancelled.</span>&nbsp;</p><hr><p>Order details are as follows: #{order_code} {date} .</p>',
            ],
            [
                'title'   => 'Order confirmed',
                'content' => '<p>Hey, {customer}</p><p>&nbsp;Your order #{order_code} has been confirmed.&nbsp;</p><hr><p>Order details are as follows: #{order_code} {date} .</p>',
            ],
            [
                'title'   => 'Order pending',
                'content' => '<p>Hey, {customer}&nbsp;</p><p>Your order #{order_code} has been pending.&nbsp;</p><hr><p>Order details are as follows: #{order_code} {date} .</p>',
            ],
            [
                'title'   => 'Terms and condition',
                'content' => '<p>The information contained in this email is confidential and intended solely for the addressee(s).</p><p>&nbsp;Unauthorized use, disclosure, or copying of this communication is prohibited. By using our services or purchasing any products, you agree to our terms of service and privacy policy. For more information, please visit our application or contact our support team.</p>',
            ],
            [
                'title'   => 'Order footer',
                'content' => '<p>Feel free to contact us if you have any questions!</p><p>Sincerely</p><p>Admin</p>',
            ],
        ];
        Template::insert($data);
    }
}
