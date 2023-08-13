<p align="center">Documentation</p>
 <br>
<p align="center">After git clone set this parametors in youre .env</p>
<p align="center">
   FACEBOOK_CLIENT_ID=666015528446777
   FACEBOOK_CLIENT_SECRET=803c6895b38bb85fa401790ca6a7d225
   FACEBOOK_REDIRECT=https://localhost:8000/facebook/callback
</p>
<p align="center">
   GOOGLE_CLIENT_ID=463174558696-ec1n7etkdl514gu603e94lql9678aq8o.apps.googleusercontent.com
   GOOGLE_CLIENT_SECRET=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
   GOOGLE_REDIRECT=https://localhost:8000/google/callback
</p>

<p align="center">
    QUEUE_CONNECTION=database
</p>

<p align="center">
  SRIPE_SECRET_KEY = "sk_test_51KJDRFKNYfpQaKy1Xta6s6EXzHZlK1p5vh6ackiJeBep8mAm96pyCn1Yvlp1zIzBb0ijxsXGzIzmyiXvO0iAD2zi00VpzQq6XV"
</p>

<p align="center">After finish .env configuration create DB and make this comand</p>
<p align="center">
  php artisan migrate:fresh && php artisan passport:install && php artisan serve
</p>

<p align="center">
  http://localhost:8000/  -> this url created for login user<br>
  http://localhost:8000/user/register/show -> this url created for register user<br>
</p>

<p align="center">
  http://localhost:8000/admin  -> this url created for login admin<br>
  http://localhost:8000/admin/register/show -> this url created for register admin<br>
</p>

<p align="center">
  http://localhost:8000/admin/dashboard-public  -> in this url you can see button set products that will create inside youre DB products<br>
  After click make this comand [ php artisan queue:work ] and then you can see <br> 
  App\Jobs\ProcessProductData ............................................ 12s DONE -> then make [ Ctrl+c ] and then [ php artisan serve ]
  for make job automatic make this comand in youre server sudo apt-get install superviso 
</p>




















