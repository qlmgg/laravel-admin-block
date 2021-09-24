[中文文档](./README.md)

[英文文档](./README_EN.md)

Open source address: https://github.com/tp5er/laravel-admin-block

introduce
The price of each Walden block dog ranges from 100 to 15000, which is divided into five kinds of dogs:

It’s between 100 and 300,

Yongdeng is between 301 and 900,

It’s between 901 and 2500,

It’s between 2501 and 6000,

Chengdeng is between 6001 and 15000.

Dog snatching starts every afternoon at 9 time points: 14:00, 15:00, 16:30, 17:00, 17:30, 19:30, 20:00, 20:30 and 21:00.

Profit point

The platform provides services such as pet dog trading information, player transaction information docking, transaction credit maintenance and other services, and charges transaction fees.
In the process of adopting a pet dog, everyone is consuming differential. We don’t calculate much. If 2000 people make an appointment every day, each person will recharge 30 yuan / day, 2000 * 30 = 60000 yuan / day, and the monthly income will be 1.8 million yuan

Player benefits:

1) If such a good project is shared with partners, the first level promotion reward is 8%, the second level is 3%, and the third level is 5%. For example: you have 100 people pushing the team directly, and each person adopts a block pet dog with a total value of 5000 yuan per day, so the team income is 5000 yuan3%100 = 15000 yuan

2) Daily output

Recharge differential process:

Members need to transfer offline to the designated account number of the platform and note the good ID. after receiving the payment in the background, the background will recharge the differential for the members

Rules for members to buy and sell dogs: peer to peer transactions, no central account

For individual to individual point-to-point transactions, the platform has no capital precipitation and daily settlement, safe and fast point-to-point transactions and no fund pool. All transaction amount does not pass through the platform, point-to-point transaction. That is to say, when you buy a dog, you put the money into the other party’s account; when you sell a dog, the other party puts the money into your account.

Software architecture
Laravel + laravel admin + MySQL fully open source

Installation tutorial
~~~
Git clone warehouse address your path
cd your-path
composer install
php artisan admin:install

~~~

Timed tasks

~~~
crontab -e

* * * * * php your-path/artisan schedule:run >> /dev/null 2>&1
~~~


Task queue
~~~
nohup php your-path/artisan queue:work  --tries=3 --sleep=3 &
~~~

Git deployment skills
~~~

directory right
chown -R www:www your-path
chmod 775 -R your-path/public/ your-path/storage/
chmod 775 -R your-path/storage/app/
Git changes due to ignoring permissions
git config --add core.filemode false
~~~
Clean cache command
php artisan clear-compiled
php artisan cache:clear
php artisan config:clear
php artisan route:clear
instructions
Trading rules of dog snatching dog in Huadeng block
1. Make an appointment in advance! (if not, the differential will be returned).

2. The buyer has made an appointment, and the seller will be informed by SMS

3. Every time before the start, there will be a 90 second countdown! When reading seconds to 10, refresh the page (poke the “dog market” in the lower left corner), and start to click frequently when reading 5 seconds, until then! The page begins to show “yellow box flip.”.

4. If there are too many people snatching dogs in a moment, the system may be stuck in an instant. You can exit and wait for 2 minutes to re-enter the app, and then check the “adoption record” to see if there is any dog snatched.
Daily adoption time: 14:00, 15:00, 16:30, 17:00, 17:30, 19:30, 20:00, 20:30, 21:00

5. Members are not allowed to buy their own dogs

6. The system judges the probability of a new member’s first dog snatching

statement
This project is purely involved in the laravel project and is not for commercial use
Do not use this system for commercial use, I am not responsible
Non commercial pure technology learning project, self research, no technical support! No technical support! No technical support!

# QQ Friend

[1751212020](http://wpa.qq.com/msgrd?v=3&uin=1751212020&site=qq&menu=yes)



