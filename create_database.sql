create table artist
(
    id   int          not null
        primary key,
    name varchar(100) not null
);

create table album
(
    id        int          not null
        primary key,
    artist_id int          not null,
    title     varchar(255) null,
    year      char(4)      null,
    image     varchar(300) null,
    tracks    int          null,
    constraint album_artist_id_artist_fk
        foreign key (artist_id) references artist (id)
);

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
    cover              varchar(300) null,
    constraint list_ibfk_1
        foreign key (user_email_fk) references user (email_user)
);

create table album_in_list
(
    album_id int                                  not null,
    list_id  int                                  not null,
    grade    double                               null,
    note     text                                 null,
    date_add datetime default current_timestamp() not null,
    primary key (album_id, list_id),
    constraint album_in_list_album_id_fk
        foreign key (album_id) references album (id),
    constraint album_in_list_list_id_list_fk
        foreign key (list_id) references list (id_list)
);

create index user_email_fk
    on list (user_email_fk);

