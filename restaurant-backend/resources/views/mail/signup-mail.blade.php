Hello {{$email_data['name']}}
<br><br>
Welcome to my Website!
<br>
Please click the below link to verify your email and activate your account!
<br><br>
<a href="http://127.0.0.1:8000/api/verify?code={{$email_data['verification_code']}}">Click Here!</a>

<br><br>
Thank you!
<br>
coder aweso.me
