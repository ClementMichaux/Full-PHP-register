# Full-PHP-register

## How to install
1. Create DB "mydb"
2. Import membres.sql
3. Modify includes/php/connect_db.php
4. Test register.php

## Functionality
### Username
Check length of username
Check if username is valid with regex
Check if username already exists in database

### E-mail
Check if e-mail is valid with regex

### Password
Check length of password
Check if password matches password confirmation

### Registration date
Add registration date


## Note
This registration system is 100% php, so there is no javascript to send the post HTTP request to the php file register_verify, this is done by the html form
