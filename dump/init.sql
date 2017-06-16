create database agenda;

use agenda;

SET sql_mode = '';

create table usuarios(
    id bigint not null auto_increment primary key,
    nome varchar(200) not null,
    email varchar(200) not null,
    senha varchar(200) not null,
    unique key(email)
);

create table salas(
    id bigint not null auto_increment primary key,
    descricao varchar(200) not null
);

insert into salas(descricao) values ("Sala 1"), ("Sala 2"), ("Sala 3");

create table reservas(
    id bigint auto_increment primary key,
    sala_id bigint not null references salas(id) on delete cascade,
    usuario_id bigint not null references usuarios(id) on delete cascade,
    inicio timestamp not null
);
