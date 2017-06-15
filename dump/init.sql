create database agenda;

use agenda;

create table usuarios(
    id bigint not null auto_increment primary key,
    nome varchar(200) not null,
    email varchar(200) not null,
    senha varchar(200) not null,
    unique key(email)
);
