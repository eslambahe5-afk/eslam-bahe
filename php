<?php
session_start();

if ($_POST) {
    // 📧 غيّر البيانات دي ببياناتك
    $YOUR_EMAIL = "your-hayam141173@gmai.com";  // ← إيميلك هنا
    $YOUR_PHONE = "01149340228";           // ← رقم الدكتورة
    $CLINIC_NAME = "عيادة د. هيام بدران";
    
    // جمع البيانات
    $name = htmlspecialchars($_POST['name']);
    $phone = htmlspecialchars($_POST['phone']);
    $email = htmlspecialchars($_POST['email'] ?? 'غير محدد');
    $date = $_POST['date'];
    $time = htmlspecialchars($_POST['time'] ?? 'غير محدد');
    $service = htmlspecialchars($_POST['service']);
    $notes = htmlspecialchars($_POST['notes'] ?? 'لا يوجد');
    
    // تنسيق التاريخ العربي
    $months = [
        '01' => 'يناير', '02' => 'فبراير', '03' => 'مارس', '04' => 'أبريل',
        '05' => 'مايو', '06' => 'يونيو', '07' => 'يوليو', '08' => 'أغسطس',
        '09' => 'سبتمبر', '10' => 'أكتوبر', '11' => 'نوفمبر', '12' => 'ديسمبر'
    ];
    $date_parts = explode('-', $date);
    $date_ar = $date_parts[2] . ' ' . $months[$date_parts[1]] . ' ' . $date_parts[0];
    
    // رسالة الإيميل الاحترافية
    $subject = "🚨 $CLINIC_NAME | طلب حجز جديد من $name";
    
    $message = "
    <html>
    <head><meta charset='UTF-8'></head>
    <body style='font-family: Cairo, Arial, sans-serif; line-height: 1.6; color: #333;'>
        <div style='background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 30px; text-align: center;'>
            <h1 style='margin: 0;'>📅 طلب حجز جديد</h1>
            <p style='margin: 10px 0 0 0;'>عيادة د. هيام بدران</p>
        </div>
        
        <div style='padding: 30px; max-width: 600px; margin: 0 auto;'>
            <table style='width: 100%; border-collapse: collapse; margin: 20px 0; background: #f8f9fa; border-radius: 10px; overflow: hidden;'>
                <tr style='background: #667eea; color: white;'>
                    <th style='padding: 15px; text-align: right;'>البيانات</th>
                    <th style='padding: 15px; text-align: right;'>القيمة</th>
                </tr>
                <tr><td style='padding: 15px; border-bottom: 1px solid #eee; font-weight: bold;'>👤 الاسم</td><td style='padding: 15px; border-bottom: 1px solid #eee;'><strong>$name</strong></td></tr>
                <tr><td style='padding: 15px; border-bottom: 1px solid #eee; font-weight: bold;'>📱 الهاتف</td><td style='padding: 15px; border-bottom: 1px solid #eee;'><strong>$phone</strong></td></tr>
                <tr><td style='padding: 15px; border-bottom: 1px solid #eee; font-weight: bold;'>✉️ الإيميل</td><td style='padding: 15px; border-bottom: 1px solid #eee;'>$email</td></tr>
                <tr><td style='padding: 15px; border-bottom: 1px solid #eee; font-weight: bold;'>📅 التاريخ</td><td style='padding: 15px; border-bottom: 1px solid #eee;'><strong>$date_ar</strong></td></tr>
                <tr><td style='padding: 15px; border-bottom: 1px solid #eee; font-weight: bold;'>🕒 الوقت</td><td style='padding: 15px; border-bottom: 1px solid #eee;'>$time</td></tr>
                <tr><td style='padding: 15px; border-bottom: 1px solid #eee; font-weight: bold;'>🎯 الخدمة</td><td style='padding: 15px; border-bottom: 1px solid #eee;'><strong>$service</strong></td></tr>
                <tr><td style='padding: 15px; font-weight: bold;'>💬 الملاحظات</td><td style='padding: 15px;'>" . nl2br($notes) . "</td></tr>
            </table>
            
            <div style='text-align: center; margin: 30px 0;'>
                <a href='https://wa.me/2$YOUR_PHONE?text=مرحبا د.هيام، لدي طلب حجز: $name - $phone - $date_ar $time - $service' 
                   style='background: #25D366; color: white; padding: 15px 30px; text-decoration: none; border-radius: 25px; font-weight: bold; font-size: 16px; display: inline-block; margin: 0 10px;'>💬 واتساب فوري</a>
                <a href='tel:$YOUR_PHONE' style='background: #007bff; color: white; padding: 15px 30px; text-decoration: none; border-radius: 25px; font-weight: bold; font-size: 16px; display: inline-block;'>📞 اتصال مباشر</a>
            </div>
            
            <div style='background: #f0f8ff; padding: 20px; border-radius: 10px; text-align: center; border-left: 5px solid #667eea;'>
                <p><strong>⏰ تم الاستلام:</strong> " . date('l d M Y الساعة H:i') . "</p>
            </div>
        </div>
    </body>
    </html>
    ";
    
    // إعدادات الإيميل
    $headers = "MIME-Version: 1.0\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8\r\n";
    $headers .= "From: noreply@hayamclinic.com\r\n";
    
    // إرسال الإيميل
    $mail_sent = mail($YOUR_EMAIL, $subject, $message, $headers);
    
    // حفظ السجل
    $log = date('Y-m-d H:i:s') . " | $name | $phone | $date | $service | $notes\n";
    file_put_contents('bookings.txt', $log, FILE_APPEND | LOCK_EX);
    
    // حفظ بيانات النجاح
    $_SESSION['booking_success'] = [
        'name' => $name,
        'phone' => $phone,
        'mail_sent' => $mail_sent
    ];
    
    header("Location: " . $_SERVER['PHP_SELF'] . "#contact");
    exit();
}
?>
