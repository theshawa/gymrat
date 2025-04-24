# GYM මීයා

## How to get started

0. Open Docker Desktop on your pc
1. Install Composer on your pc(if it's not there)
2. Run `composer install` and below commands in root directory
3. `docker-compose -f .docker/docker-compose.yml build --no-cache`
4. `docker-compose -f .docker/docker-compose.yml up -d`
5. Open http://localhost:5000 for phpmyadmin(user: root, pw: 123456)
6. Import `db.sql` to phpmyadmin
7. Open http://localhost for application.
8. To stop containers run `docker-compose -f .docker/docker-compose.yml down`
9. To access docker console: `docker-compose -f .docker/docker-compose.yml exec gymrat_php sh`

**Keep an eye on the changes of `db.sql`**

## Logins

RATS:
mrclocktd@gmail.com
emily.carter@example.com
liam.johnson@example.co.uk

TRAINER:
johncena@example.com

STAFF:
admin@example.com
eq@example.com
wnmp@example.com

Password for All Users:
`123456`

## Payhere Dashboard

Email: 2022cs136@stu.ucsc.cmb.ac.lk
Pw: 123kngine21

## External Libraries and Packages Used

### PhpMailer

To Send emails

### Chart.js

To show charts in frontend

### Qrcode.js

To generate QR Codes

### FakerPHP

To seed database
