create table category
(
    id_category   int auto_increment
        primary key,
    name_category varchar(30) not null,
    constraint name_category
        unique (name_category)
);

create table country
(
    id_country   int auto_increment
        primary key,
    name_country varchar(60) not null,
    constraint name_country
        unique (name_country)
);

create table artist
(
    id_artist     int auto_increment
        primary key,
    name_artist   varchar(100) not null,
    country_id_fk int          null,
    constraint artist_ibfk_1
        foreign key (country_id_fk) references country (id_country)
);

create table album
(
    id_album           int auto_increment
        primary key,
    name_album         varchar(100) not null,
    date_release_album char(4)      null,
    artist_id_fk       int          not null,
    constraint album_ibfk_1
        foreign key (artist_id_fk) references artist (id_artist)
);

create index artist_id_fk
    on album (artist_id_fk);

create index country_id_fk
    on artist (country_id_fk);

create table category_in_album
(
    id_category_in_album int auto_increment
        primary key,
    album_id_fk          int not null,
    category_id_fk       int not null,
    constraint category_in_album_ibfk_1
        foreign key (album_id_fk) references album (id_album),
    constraint category_in_album_ibfk_2
        foreign key (category_id_fk) references category (id_category)
);

create index album_id_fk
    on category_in_album (album_id_fk);

create index category_id_fk
    on category_in_album (category_id_fk);

create table user
(
    email_user            varchar(255) not null
        primary key,
    pseudo_user           varchar(60)  not null,
    password_user         varchar(64)  not null,
    role_user             varchar(6)   not null,
    date_inscription_user date         not null,
    token                 varchar(70)  null,
    constraint pseudo_user
        unique (pseudo_user)
);

create table list
(
    id_list            int auto_increment
        primary key,
    name_list          varchar(100) not null,
    date_creation_list date         not null,
    user_email_fk      varchar(255) not null,
    constraint list_ibfk_1
        foreign key (user_email_fk) references user (email_user)
);

create table album_in_list
(
    id_album_in_list         int auto_increment
        primary key,
    date_ajout_album_in_list datetime not null,
    grade_album_in_list      double   null,
    notes_album_in_list      text     null,
    album_id_fk              int      not null,
    list_id_fk               int      not null,
    constraint album_in_list_ibfk_1
        foreign key (album_id_fk) references album (id_album),
    constraint album_in_list_ibfk_2
        foreign key (list_id_fk) references list (id_list)
);

create index album_id_fk
    on album_in_list (album_id_fk);

create index list_id_fk
    on album_in_list (list_id_fk);

create index user_email_fk
    on list (user_email_fk);

