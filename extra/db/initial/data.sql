create table generations
(
    id    int          not null
        primary key,
    title varchar(100) not null,
    constraint generations_id_uindex
        unique (id)
);

create table offers
(
    id            int auto_increment
        primary key,
    model         varchar(100)                        not null,
    mark          varchar(100)                        null,
    year          int                                 not null,
    run           int                                 not null,
    color         varchar(100)                        not null,
    body_type     varchar(50)                         not null,
    engine_type   varchar(30)                         null,
    transmission  varchar(30)                         null,
    gear_type     varchar(20)                         null,
    generation_id int                                 null,
    created_at    timestamp default CURRENT_TIMESTAMP null,
    updated_at    timestamp                           null on update CURRENT_TIMESTAMP,
    constraint offers_generations_id_fk
        foreign key (generation_id) references generations (id)
);

create index offers__index_body_type
    on offers (body_type);

create index offers__index_color
    on offers (color);

create index offers__index_run
    on offers (run);

create index offers_engine_type_index
    on offers (engine_type);

create index offers_gear_type_index
    on offers (gear_type);

create index offers_generation_id_index
    on offers (generation_id);

create index offers_model_index
    on offers (model);

create index offers_transmission_index
    on offers (transmission);

create index offers_year_index
    on offers (year);

