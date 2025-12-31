CREATE DATABASE photosphere;
use photosphere;


CREATE table user (
    id INT primary key,
    user_name varchar(30) unique,
    email varchar(30) UNIQUE,
    password varchar(30),
    bio text,
    adresse varchar(30),
    role varchar(30) enum("admin","moderator","basicuser","prouser"),
    created_at date DEFAULT CURRENT_DATE,
    last_login date,
    isSuperAdmin BOOLEAN default null,
    moderator_level varchar(30) default null,
    date_debut_abonnement date,
    date_fin_abonnement date

)