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
    sala_id bigint not null,
    usuario_id bigint not null,
    inicio timestamp not null,
    constraint sala_id_fk foreign key(sala_id) references salas(id) on delete cascade,
    constraint usuario_id_fk foreign key(usuario_id) references usuarios(id) on delete cascade
);
