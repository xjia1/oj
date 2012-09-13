create table questions (
  id serial,
  username varchar(30) not null,
  report_id bigint not null,
  category smallint not null,
  problem_id bigint null,
  ask_time timestamp not null,
  question text not null,
  answer_time timestamp null,
  answer text null,
  primary key (id)
);